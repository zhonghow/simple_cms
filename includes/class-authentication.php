<?php

class Authentication
{
    /* -------------------------------------------------------------------------- */
    /*                                User Sign Up                                */
    /* -------------------------------------------------------------------------- */
    public static function signUp($name, $email, $password)
    {
        return Database::connectDatabase()->insertData(
            'INSERT INTO users (name, email, password) VALUES (:name, :email, :password)',
            [
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ]
        );
    }

    /* -------------------------------------------------------------------------- */
    /*                                 User Log In                                */
    /* -------------------------------------------------------------------------- */
    public static function logIn($email, $password)
    {
        return Database::connectDatabase()->selectData(
            'SELECT * FROM users WHERE email = :email',
            [
                'email' => $email,
                'password' => $password
            ]
        );
    }

    /* -------------------------------------------------------------------------- */
    /*                                User Log Out                                */
    /* -------------------------------------------------------------------------- */
    public static function logOut()
    {
        unset($_SESSION['user']);
    }

    /* -------------------------------------------------------------------------- */
    /*                             Assign User Session                            */
    /* -------------------------------------------------------------------------- */
    public static function setUserSession($user_id)
    {
        // Step 1 -> Load User Data from database based on the $user_id provided
        $user = Database::connectDatabase()->selectData(
            'SELECT * FROM users WHERE id = :id',
            [
                'id' => $user_id
            ]
        );
        var_dump($user);
        // Step 2 -> Assign to session data
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['password']
        ];
    }

    /* -------------------------------------------------------------------------- */
    /*                     Check if user is already logged in                     */
    /* -------------------------------------------------------------------------- */
    public static function isLoggedIn()
    {
        return isset($_SESSION['user']);
    }
}
