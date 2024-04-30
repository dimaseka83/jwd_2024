<?php

class Database
{
    // private is only accessible within the class
    private $serverName = "localhost";
    private $username   = "root";
    private $password   = "";
    private $database   = "db_wisata";

    // public is accessible from anywhere
    public $con = null;

    // constructor is a special type of method which is called when an object is created
    public function getConnection()
    {
        try {
            // mysqli is a standard database driver for PHP
            $this->con = new mysqli($this->serverName, $this->username, $this->password, $this->database);
            // connect_error returns the error description from the last connection error, if any
            if ($this->con->connect_error) {
                throw new Exception("Could not connect to database.");
            }
        } catch (Exception $e) {
            // getMessage() returns the message of the exception
            throw new Exception($e->getMessage());
        }
        return $this->con;
    }
}
