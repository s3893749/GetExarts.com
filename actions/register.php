<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - A1_Q3
 * Last Updated - 12/06/2022
 */

//***** CREATE OUR LOGIN SCRIPT VARIABLES *****\\

//response array containing json response data
$response = [];
//first name from post request
$first_name = null;
//last name from post request
$last_name = null;
//email address containing the submitted email
$email = null;
//password containing the submitted password
$password = null;

//***** VALIDATE OUR INPUT FIELDS *****\\

//validate our first name input
if(isset($_POST["first_name"])){
    $first_name = $_POST["first_name"];
}
//return our error to the user
if($first_name === null || $first_name === "" || strlen($first_name) < 2){
    $response["error"]["first_name"] = "first name must be not null and two or more characters";
    $response["error"]["general"] = "first name must be not null and two or more characters";

    returnResponse($response);
}

//validate our last name input
if(isset($_POST["last_name"])){
    $last_name = $_POST["last_name"];
}
//return our error to the user
if($last_name === null || $last_name === "" || strlen($last_name) < 2){
    $response["error"]["last_name"] = "last name must be not null and two or more characters";
    $response["error"]["general"] = "last name must be not null and two or more characters";

    returnResponse($response);
}

//validate our email
if(isset($_POST["email"])){
    $email = $_POST["email"];
}
//return our error to the user
if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    $response["error"]["email"] = "invalid email provided, email address must be valid and not empty";
    $response["error"]["general"] = "invalid email provided, email address must be valid and not empty";
    returnResponse($response);
}

//with the email we need to check It's not already taken
if(User::validateEmail($email)){
    $response["error"]["email"] = "that email address has already been taken";
    $response["error"]["general"] = "that email address has already been taken";
    returnResponse($response);
}

//finally, we validate our password
if(isset($_POST["password"])){
    $password = $_POST["password"];
}
//validate our password
if($password === null || $password === "" || strlen($password) < 5){
    $response["error"]["password"] = "password must be 5 or more characters and not empty";
    $response["error"]["general"] = "password must be 5 or more characters and not empty";

    returnResponse($response);
}

//hash our password
$password = hash(hash_type,pepper.$password);

//finally, return our result of the user creation
User::createNewUser($first_name,$last_name,$email,$password);

if(User::validateEmail($email)){
    $response["redirect"] = "login";
}else{
    $response["error"]["general"] = "failed to register new user account";
}

returnResponse($response);
