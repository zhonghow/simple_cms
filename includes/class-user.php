<?php

class User
{

    private $database;

    /* -------------------------------------------------------------------------- */
    /*                             Connect to database                            */
    /* -------------------------------------------------------------------------- */
    public function __construct()
    {
        $this->database = new Database();
    }

    public function listAllUser()
    {
        
    }
}
