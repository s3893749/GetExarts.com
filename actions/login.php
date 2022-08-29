<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - A1_Q3
 * Last Updated - 10/06/2022
 */

//***** CREATE OUR LOGIN SCRIPT VARIABLES *****\\

//response array containing json response data
$response = [];
//email address containing the submitted email
$email = null;
//password containing the submitted password
$password = null;

//***** VALIDATE OUR EMAIL ADDRESS *****\\

//if we receive an email address by post we set it to our local var
if(isset($_POST["email"])){
    $email = $_POST["email"];
}

//next we check to ensure the email address is not null
if($email === null || $email === "" || $email === " "){
    $response["error"]["email"] = "No email address provided";
    returnResponse($response);
}

//next we check to make sure its a valid email address
if(!User::validateEmail($email)){
    $response["error"]["email"] = "Invalid email address or password";
    returnResponse($response);
}

//***** VALIDATE OUR PASSWORD *****\\

//if we receive a password in the post request check then set it to the local var
if(isset($_POST["password"])){
    $password = $_POST["password"];
}

//validate the password is not null
if($password === null || $password === "" || $password === " ") {
    $response["error"]["password"] = "No password provided";
    returnResponse($response);
}

//validate the password matches the users password
if(!User::validatePassword($password,$email)){
    $response["error"]["email"] = "Invalid email address or password";
    returnResponse($response);
}

$token = User::createSessionToken(User::getUserIdFromEmail($email));
$response["token"] = $token;
$response["redirect"] = "account";

returnResponse($response);
