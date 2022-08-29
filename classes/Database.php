<?php

/**
 * Copyright Jack Harris
 * Peninsula Interactive - A1_Q3
 * Last Updated - 11/06/2022
 */
class Database
{

    static function connect(): mysqli
    {
        return new mysqli("localhost","root","","getexarts");
    }

    static function query($sql): mysqli_result|bool
    {
        return Database::connect()->query($sql);
    }

}