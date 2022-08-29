<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - A1_Q3
 * Last Updated - 20/06/2022
 */

//***** CREATE OUR NEW TASK SCRIPT VARIABLES *****\\

//response array containing json response data
$response = [];
//name of the new task
$name = null;
//description of the new task
$description = null;
//image link for the new task page
$image = null;
//end date for the task
$end_date = null;
//author of the tas
$author = null;
//user_id of the task
$user_id = null;
//user login token
$token = null;

//***** VALIDATE OUR NAME *****\\
if(isset($_POST["name"])){
    $name = trim($_POST["name"]);
}
//check for name errors
if($name === null || $name === "") {
    $response["error"]["name"] = "task name not provided";
    returnResponse($response);
}

//***** VALIDATE OUR DESCRIPTION *****\\
if(isset($_POST["description"])){
    $description = trim($_POST["description"]);
}
//check for description errors
if($description === null || $description === ""){
    $response["error"]["description"] = "no description provided for task";
    returnResponse($response);
}

//***** VALIDATE OUR IMAGE *****\\
if(isset($_POST["image"])){
    $image = $_POST["image"];
}
//check for image errors
if(!filter_var($image,FILTER_VALIDATE_URL)){
    $response["error"]["image"] = "invalid image provided, please provide a valid url format using https";
    returnResponse($response);
}

//***** VALIDATE OUR END DATE *****\\
if(isset($_POST["end_date"])){
    $end_date = $_POST["end_date"];
}
//check for errors
if($end_date === ""){
    $response["error"]["end_date"] = "no end date provided, date must be in unix time";
    returnResponse($response);
}
if($end_date < time()){
    $response["error"]["end_date"] = "end date cannot be in the past, please select a upcoming date";
    returnResponse($response);
}
//check to ensure the unix time end date is an int
if(!is_numeric($end_date)){
    $response["error"]["end_date"] = "date must be a unix time integer";
    returnResponse($response);
}

//***** VALIDATE OUR TOKEN *****\\
if(isset($_POST["token"])){
    $token =  $_POST["token"];
}
//validate our token is valid by attempting to get the user from the token
if(User::getUserFromSessionToken($token) === null){
    $response["error"]["token"] = "invalid login token provided";
    returnResponse($response);
}


//***** CREATE OUR NEW TASK! *****\\
$outcome = Task::createTask($name,$description,$end_date,$image,$token,$end_date);
//validate the task was created successfully
if($outcome){
    returnResponse($response);
}



