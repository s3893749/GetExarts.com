class Application {

    currentPage;
    static instance = new Application();
    domainLocation = "/A1_Q3";
    domain = "http://localhost"

    constructor() {

        let uri = window.location.pathname.split("/");
        if(uri[2] !== ""){
            this.currentPage = uri[2];
            this.setPage(this.currentPage);
        }else{
            this.currentPage = "home";
            this.setPage(this.currentPage);
        }
        //call our update component once, this is needed as we need to render it once to start.
        this.updateComponent("header","header-container");
    }

    setSelectedTask(id){
        this.updateComponent("task&id="+id,"task-display");
    }

    placeBid(){
        let id =  document.getElementById("bid-task-id").valueOf().value;
        let bid = document.getElementById("bid-amount-input").valueOf().value;

        this.postFormData(this.domain + this.domainLocation + "/",
            "action=bid" +
            "&task_id=" + id +
            "&amount=" + bid +
            "&token=" + this.getCookie("token")
        ).then(function (outcome) {
            let response = JSON.parse(outcome);
            if (response.outcome) {
                Application.instance.updateComponent("task&id="+id,"task-display")
            } else {
                console.log("bid failed to be placed")
            }
        });
    }

    showNewTaskWindow(){
        document.getElementById("new-task-window").style.display = "block";
    }

    cancelNewTaskWindowButton(){
        document.getElementById("new-task-window").style.display = "none";
    }

    saveNewTaskWindowButton(){
        let name = document.getElementById("task-name").valueOf().value;
        let description = document.getElementById("task-description").valueOf().value;
        let image =  document.getElementById("task-image").valueOf().value;
        let end_date = document.getElementById("task-date").valueOf().value;
        let unix_end_date = new Date(end_date).valueOf();

        console.log(unix_end_date);

        let validDate = false;

        if(new Date(end_date).getTime() <= new Date().getTime()){
            document.getElementById("new-task-window-error").innerText = "please select a valid upcoming date";
        }else{
            validDate = true;
        }

        if(validDate) {
            this.postFormData(this.domain + this.domainLocation + "/",
                "action=new-task" +
                "&name=" + name +
                "&description=" + description +
                "&image=" + image +
                "&end_date=" + unix_end_date +
                "&token=" + this.getCookie("token")
            ).then(function (outcome) {
                let response = JSON.parse(outcome);
                if (response.outcome) {
                    Application.instance.loadPage("my-tasks");
                    document.getElementById("new-task-window").style.display = "none";
                } else {
                    if (response.error.image !== null) {
                        document.getElementById("new-task-window-error").innerText = response.error.image;
                    }
                }
            });
        }
    }

    async setPage(page) {
        this.currentPage = page;
        let outcome = await this.loadPage(page);

        if (!outcome) {
            page = "404";
        }

        //check if we have any components to update
        let componentUpdates = Array.from(document.getElementsByClassName("update-component"));
        if(componentUpdates !== null){
            componentUpdates.forEach(function (component) {
                let componentTarget = component.id;
                let componentName = component.innerText;
                Application.instance.updateComponent(componentName,componentTarget);
            })
        }

        window.history.pushState({}, page, new URL(this.domain + this.domainLocation + "/" + page).toString());
        document.title = "Get Exarts | " + page;
    }

    async loadPage(page) {
        return fetch(this.domainLocation+"/?page=" + page).then(async response => {

            //set the document body html to the response
            document.getElementById("container").innerHTML = await response.text();

            //check if the page has a redirect call, if so then redirect to it
            if(document.getElementById("redirect") !== null){
                this.setPage(document.getElementById("redirect").innerText);
            }
            return response.ok;
        })
    }

    async updateComponent(name, placeholder){
        return fetch(this.domainLocation+"/?component="+name).then(async response =>{
            document.getElementById(placeholder).innerHTML = await response.text();
        })
    }

    logout(){
        document.cookie = "token=; expires=0; path=/";
        this.setPage("login");
        this.updateComponent("header","header-container");
    }

    login(){
        let email = document.getElementById("login-email");
        let password = document.getElementById("login-password");

        //validate our input is not null
        if(email.valueOf().value === null || email.valueOf().value === ""){
            document.getElementById("login-general-error").innerText = "Email address cannot be empty";
        }
        if(password.valueOf().value === null || password.valueOf().value === ""){
            document.getElementById("login-general-error").innerText = "password cannot be empty";
        }

        this.postFormData(this.domain+this.domainLocation+"/",
            "action=login"+
                 "&email="+email.valueOf().value+
                 "&password="+password.valueOf().value
        ).then(function (outcome){
            let response = JSON.parse(outcome);
            if(!response.outcome){
                document.getElementById("login-general-error").style.display = "block";
                document.getElementById("login-general-error").innerText = response.error.email;
            }else{

                //hide our error
                document.getElementById("login-general-error").style.display = "none";

                //create our expiry for the cookie
                let date = new Date();
                date.setTime(date.getTime() +(30*24*60*60*1000));
                let expires = "expires="+date.toUTCString();

                //create our cookie
                document.cookie = "token" + "=" +response.token +";"+expires+";path=/";

                //update our required components
                Application.instance.updateComponent("header","header-container");

                //redirect the user
                Application.instance.setPage(response.redirect);
            }

        })
    }

    register(){
        let first_name = document.getElementById("register-first-name");
        let last_name = document.getElementById("register-last-name");
        let email = document.getElementById("register-email");
        let password = document.getElementById("register-password");

        this.postFormData(this.domain+this.domainLocation+"/",
            "action=register"+
                 "&first_name="+first_name.valueOf().value+
                 "&last_name="+last_name.valueOf().value+
                 "&email="+email.valueOf().value+
                 "&password="+password.valueOf().value
        ).then(outcome => {
            let response = JSON.parse(outcome);
            if(!response.outcome){
                document.getElementById("register-general-error").style.display = "block";
                document.getElementById("register-general-error").innerText = response.error.general;
            }else{
                document.getElementById("register-general-error").style.display = "none";
                document.getElementById("login-email").valueOf().value = email.valueOf().value;
                document.getElementById("login-password").valueOf().value = password.valueOf().value;
                this.login();
            }
        })
    }

    postFormData(uri, data){
        return fetch(uri,{
            method: 'post',
            body: data,
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            }
        }).then(async function (response) {
            return await response.text();
        })
    }

    //get cookie function created by Artnikpro
    //https://stackoverflow.com/questions/4825683/how-do-i-create-and-read-a-value-from-cookie
    getCookie = (name) => {
        return document.cookie.split('; ').reduce((r, v) => {
            const parts = v.split('=')
            return parts[0] === name ? decodeURIComponent(parts[1]) : r
        }, '')
    }


}