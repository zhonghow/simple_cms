<?php

class Database
{
    private $database;

    public function __construct()
    {
        try {
            $this->database = new PDO(
                'mysql:host=devkinsta_db;dbname=Simple_CMS',
                'root',
                'qQs06NBbdQOEMav6'
            );
        } catch (Exception) {
            die("Database Connection Failed");
        }
    }

    public static function connectDatabase()
    {
        return new self();
        // Equal to new Database();
        // Database::connectDatabase == $database = new Database(); 
    }

    /* -------------------------------------------------------------------------- */
    /*                       Trigger SELECT command via PDO                       */
    /* -------------------------------------------------------------------------- */

    public function selectData($sql, $data = [], $list = false)
    {
        // Prepare
        $statement = $this->database->prepare($sql);
        // Execute
        $statement->execute($data);
        // Fetch
        if ($list) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return $statement->fetch(PDO::FETCH_ASSOC);
        }
    }

    /* -------------------------------------------------------------------------- */
    /*                       Trigger INSERT command via PDO                       */
    /*                            $sql = Insert Command                           */
    /*                           $data = used in execute                          */
    /* -------------------------------------------------------------------------- */

    public function insertData($sql, $data = [])
    {
        $statement = $this->database->prepare($sql);
        $statement->execute($data);
        return $this->database->lastInsertId();
    }

    /* -------------------------------------------------------------------------- */
    /*                       Trigger UPDATE command via PDO                       */
    /* -------------------------------------------------------------------------- */

    public function updateData($sql, $data = [])
    {
        $statement = $this->database->prepare($sql);
        $statement->execute($data);
        return $statement->rowCount();
    }

    /* -------------------------------------------------------------------------- */
    /*                       Trigger DELETE command via PDO                       */
    /* -------------------------------------------------------------------------- */

    public function deleteData($sql, $data = [])
    {
        $statement = $this->database->prepare($sql);
        $statement->execute($data);
        return $statement->rowCount();
    }
}
