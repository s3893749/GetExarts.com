<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - A1_Q3
 * Last Updated - 20/06/2022
 */

//***** CREATE OUR PLACE BID SCRIPT VARIABLES *****\\

//response array containing json response data
$response = [];
//name of the new task
$token = null;
//description of the new task
$task_id = null;
//image link for the new task page
$amount = null;


//***** VALIDATE OUR TOKEN INPUT *****\\
if(isset($_POST["token"])){
    $token = trim($_POST["token"]);
}
//check the token for errors
if($token === null || $token === ""){
    $response["error"]["token"] = "no login token provided";
    returnResponse($response);
}
//validate our token is valid by attempting to get the user from the token
if(User::getUserFromSessionToken($token) === null){
    $response["error"]["token"] = "invalid login token provided";
    returnResponse($response);
}

//***** VALIDATE OUR BID AMOUNT INPUT *****\\
if(isset($_POST["amount"])){
    $amount = $_POST["amount"];
}
//check the token for errors
if($amount === null || $amount === ""){
    $response["error"]["amount"] = "no bid amount provided";
    returnResponse($response);
}
//check a number has been received not a letter
if(!is_numeric($amount)){
    $response["error"]["amount"] = "amount must be a numeric number";
    returnResponse($response);
}

//***** VALIDATE OUR TASK ID *****\\
if(isset($_POST["task_id"])){
    $task_id = $_POST["task_id"];
}
if($task_id ===  null || $task_id === ""){
    $response["error"]["task_id"] = "no task id provided";
    returnResponse($response);
}
//check a number has been received not a letter
if(!is_numeric($task_id)){
    $response["error"]["task_id"] = "task id must be numeric integer";
    returnResponse($response);
}
//check it's a valid task id that matches a task
if(Task::getTaskById($task_id) === []){
    $response["error"]["task_id"] = "invalid task id provided, please provide a valid task id";
    returnResponse($response);
}

//***** CHECK THE USER HAS NOT ALREADY BID ON THIS TASK *****\\
//get user object
$user = User::getUserFromSessionToken($token);

if(User::getBid($user->getId(),$task_id) != []){
    $response["error"]["task_id"] = "error, you have already bid on that tasks, you can only bid once per task";
    returnResponse($response);
}

if(!User::addBid($task_id,$amount,$token)){
    $response["error"]["bid"] = "failed to place bid, please contact support";

}
returnResponse($response);