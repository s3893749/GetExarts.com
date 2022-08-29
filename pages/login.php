<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - A1_Q3
 * Last Updated - 9/06/2022
 */

if($authed){
    echo "<div id=redirect>home</div>";
}else{
?>
    <main>
        <section>
            <div class="flex">
                <div>
                <h1>Login</h1>
                <form action="javascript:Application.instance.login()">
                    <p id="login-general-error">Login failed</p>
                    <label>
                        Email
                        <input type="email" id="login-email" required>
                    </label>
                    <label>
                        Password
                        <input type="password" id="login-password" required>
                    </label>
                    <button>Login</button>
                </form>
                </div>
                <div>
                    <h1>Register</h1>
                    <form action="javascript:Application.instance.register()">
                        <p id="register-general-error">Registration failed!</p>

                        <label style="width:calc(50% - 16px)">
                            First name
                            <input type="text" id="register-first-name" required>
                        </label>
                        <label style="width:calc(50% - 16px)">
                            Last name
                            <input type="text" id="register-last-name" required>
                        </label>
                        <label>
                            Email
                            <input type="email" id="register-email" required>
                        </label>
                        <label>
                            Password
                            <input type="password" id="register-password" required>
                        </label>

                        <button>Register</button>
                    </form>
                </div>
            </div>
        </section>
    </main>
<?php
}