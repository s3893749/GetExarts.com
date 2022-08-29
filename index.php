<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - A1_Q3
 * Last Updated - 9/06/2022
 */

//***** HASHING SECURITY *****\\
const hash_type = "sha256";
const pepper = "BeefPies&Spuds";


//***** REQUIRE ALL CLASSES *****\\
require_once __DIR__ . DIRECTORY_SEPARATOR."classes".DIRECTORY_SEPARATOR."User.php";
require_once __DIR__ . DIRECTORY_SEPARATOR."classes".DIRECTORY_SEPARATOR."Database.php";
require_once __DIR__ . DIRECTORY_SEPARATOR."classes".DIRECTORY_SEPARATOR."Task.php";

//***** POST REQUEST PROCESSING *****\\

//Define our application variables
$action_folder = __DIR__.DIRECTORY_SEPARATOR."actions";
//initialize our action variable
$action = null;

//check if the method is post, if so run this code, else assume this is a get request
if($_SERVER["REQUEST_METHOD"] === "POST"){
    header('Content-type: application/json');
    $response = [];

    //return a no action error if no action is provided
    if(!isset($_POST["action"])) {
        $response["outcome"] = false;
        $response["error"] = "no action target provided";
        echo json_encode($response);
        die;
    }else{
        $action = $_POST["action"];
    }

    //check to make sure the action is not null
    if($action == null){
        $response["outcome"] = false;
        $response["error"] = "action cannot be null";
        echo json_encode($response);
        die;
    }

    //next if we have an action provided validate that it is a valid action
    if(findFile($action.".php",$action_folder)){
        //if valid include our action file
        require_once __DIR__.DIRECTORY_SEPARATOR."actions".DIRECTORY_SEPARATOR.$action.".php";
    }else{
        $response["outcome"] = false;
        $response["error"] = "404 requested action is not found";
        echo json_encode($response);
        die;
    }

    die;

}

//***** GET REQUEST PROCESSING *****\\
if($_SERVER["REQUEST_METHOD"] === "GET") {
    $user = null;
    $authed = false;

    //check authentication
    if (isset($_COOKIE["token"])) {
        $result = User::getUserFromSessionToken($_COOKIE["token"]);

        if ($result !== null) {
            $user = $result;
            $authed = true;
        }
    }


    //get component
    if(isset($_GET["component"])){
        $components_directory = __DIR__.DIRECTORY_SEPARATOR."components";
        $component = $_GET["component"];

        if(findFile($component.".php",$components_directory)){
            require_once __dir__ . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . $component . ".php";
            die;
        }else{
            echo "error component not found";
            http_response_code(404);
        }
    }

    //process get request code.
    if (isset($_GET["page"])) {

        //Define our pages directory
        $pages_directory = __DIR__ . DIRECTORY_SEPARATOR . "pages";
        //initialize our page variable
        $page = $_GET["page"];

        //page lookup outcome
        $outcome = false;


        if (findFile($page . ".php", $pages_directory)) {
            $outcome = true;
            require_once __dir__ . DIRECTORY_SEPARATOR . "pages" . DIRECTORY_SEPARATOR . $page . ".php";
        }

        if (!$outcome) {
            echo "<main>
                <section>
                       <h1>404 page not found</h1>
                       <p>Sorry, it looks like the requested page is not found, please check the address or contact support</p>
                </section>
              </main>";
            http_response_code(404);
        }

    } else {

        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>';
        require_once "components" . DIRECTORY_SEPARATOR . "head.php";
        echo '<title>Get Exarts -> Home</title>';
        echo '</head>';
        echo '<div id="header-container"></div>';
        echo '<body>
        <div id="container"></div>
        </body>
        <script src="javascript/Application.js"></script>
        </html>';
    }

    //else if we reach this step kill the program
    die;
}

//finally, in this code is reached we can see that this is an invalid request.

echo "invalid method request";

//***** FUNCTIONS REQUEST PROCESSING *****\\


//find file function, searches for a file in a specific folder and returns true or false if it exists in that location.
function findFile($file, $folder): bool
{
    //if null return false
    if($file === null){
        return false;
    }
    $outcome = false;
    //check if the requested page is a valid page
    foreach (scandir($folder) as $files){
        if($file === $files){
            $outcome = true;
        }
    }
    return $outcome;
}

//return response function, is used by our action scripts to force our json response to be returned and the script terminated
function returnResponse($response){
    if(isset($response["error"])){
        $response["outcome"] = false;
    }else{
        $response["outcome"] = true;
    }

    echo json_encode($response);
    die;
}


