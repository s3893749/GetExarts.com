<?php

/**
 * Copyright Jack Harris
 * Peninsula Interactive - A1_Q3
 * Last Updated - 11/06/2022
 */
class User
{
    private int $user_id;
    private string $email;
    private string $password;
    private string $first_name;
    private string $last_name;
    private string $password_reset_token;

    public function __construct($user_data){

        $this->user_id = $user_data["user_id"];
        $this->email = $user_data["email"];
        $this->last_name = $user_data["last_name"];
        $this->first_name = $user_data["first_name"];
        $this->password = $user_data["password"];
        $this->password_reset_token = $user_data["password_reset_token"];

    }

    static function getUserFromSessionToken($token): User|null
    {
        $user = null;

        $sql = "SELECT * FROM sessions where token ='$token'";
        $user_data = Database::query($sql)->fetch_assoc();

        if($user_data !== null){

            $user_id = $user_data["user_id"];
            $sql = "SELECT * FROM users where user_id='$user_id'";
            $user_data =  Database::query($sql)->fetch_assoc();

            if($user_data !== []) {
                $user = new User($user_data);
            }
        }

        return $user;
    }

    static function getUserIdFromEmail($email){
        $sql = "SELECT * FROM users WHERE email='$email'";
        $user_data = Database::query($sql)->fetch_assoc();
        if($user_data !== null){
            return $user_data["user_id"];
        }else{
            return false;
        }
    }

    static function validateEmail($email): bool
    {
        $sql = "SELECT * FROM users WHERE email='$email'";
        if(Database::query($sql)->fetch_assoc() !== null){
            return true;
        }else{
            return false;
        }
    }

    static function validatePassword($password, $email): bool
    {
        $password = hash(hash_type,pepper.$password);

        $sql = "SELECT * FROM users WHERE password='$password' AND email='$email'";
        if(Database::query($sql)->fetch_assoc() !== null){
            return true;
        }else{
            return false;
        }
    }

    static function createSessionToken($user_id): string
    {
        $token = hash(hash_type,time().$user_id);
        $expirary = time()+86400*30;
        $sql = "DELETE FROM sessions WHERE user_id='$user_id'";
        Database::query($sql);
        $sql = "INSERT INTO sessions (`user_id`,`token`,`expirary`) VALUES('$user_id','$token','$expirary')";
        Database::query($sql);
        return $token;
    }

    static function createNewUser($first_name, $last_name, $email, $password): mysqli_result|bool
    {

        $sql = "INSERT INTO users (`first_name`,`last_name`,`email`,`password`) VALUES ('$first_name','$last_name','$email','$password')";
        return Database::query($sql);

    }

    static function addBid(string $task_id, int $amount, string $token): mysqli_result|bool
    {
        $user_id = User::getUserFromSessionToken($token)->getId();
        $hash = hash(hash_type,pepper.$amount);
        $sql = "INSERT INTO bids (`user_id`,`task_id`,`hash`) VALUES ('$user_id','$task_id','$hash')";
        return Database::query($sql);
    }

    static function getBid($user_id,$task_id)
    {
        $sql = "SELECT * FROM bids WHERE task_id='$task_id' AND user_id='$user_id'";
        return Database::query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function getId(){
        return $this->user_id;
    }

}