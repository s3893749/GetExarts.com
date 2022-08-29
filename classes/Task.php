<?php

/**
 * Copyright Jack Harris
 * Peninsula Interactive - A1_Q3
 * Last Updated - 20/06/2022
 */
class Task
{
    private int $user_id;
    private int $id;
    private string $name;
    private string $description;
    private string $image;
    private string $author;
    private int $created_at;
    private int $end_date;

    function __construct(int $user_id, int $id, string $name, string $description, string $author, int $created_at, int $end_date, string $image)
    {
        $this->user_id = $user_id;
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->author = $author;
        $this->created_at = $created_at;
        $this->end_date = $end_date;
        $this->image = $image;
    }

    public static function createTask(string $name, string $description,int $end_date, string $image, string $token): bool
    {
        $user = User::getUserFromSessionToken($token);
        if(!$user){
            return false;
        }
        $user_id = $user->getId();
        $author =  $user->getFirstName()." ".$user->getLastName();
        $created_at = time();
        $end_date = $end_date/1000;

        return Database::query("INSERT INTO tasks (`user_id`,`name`,`description`,`author`,`created_date`,`end_date`,`image`) VALUES ('$user_id','$name','$description','$author','$created_at','$end_date','$image')");
    }

    public static function getTaskById($id){

        $sql = "SELECT * FROM tasks WHERE id='$id'";
        return  Database::query($sql)->fetch_all(MYSQLI_ASSOC);
    }

}