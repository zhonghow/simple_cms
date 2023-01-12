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
        $user_id = false;

        $user = Database::connectDatabase()->selectData(
            'SELECT * FROM users WHERE email = :email',
            [
                'email' => $email
            ]
        );

        // If $user is valid, then return $user array
        if ($user) {
            if (password_verify($password, $user['password'])) {
                $user_id = $user['id'];
            }
        }
        // Return user ID
        return $user_id;
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
        // Step 2 -> Assign to session data
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['password'],
            'role' => $user['role']
        ];
    }

    /* -------------------------------------------------------------------------- */
    /*                     Check if user is already logged in                     */
    /* -------------------------------------------------------------------------- */
    public static function isLoggedIn()
    {
        return isset($_SESSION['user']);
    }

    // Retrieve user role from $_SESSION['user]
    public static function getRole()
    {
        if (self::isLoggedIn()) {
            return $_SESSION['user']['role'];
        }

        return false;
    }

    // Check if user is admin
    public static function roleAdmin()
    {
        return self::getRole() == 'admin';
    }

    // Check if user is editor
    public static function roleEditor()
    {
        return self::getRole() == 'editor';
    }

    // Check if user is user
    public static function roleUser()
    {
        return self::getRole() == 'user';
    }

    // Control user's accessibility
    // Role can be admin / editor / user
    public static function accessControl($role)
    {
        if (self::isLoggedIn()) {
            switch ($role) {
                case 'admin':
                    return self::roleAdmin();
                case 'editor':
                    return self::roleEditor() || self::roleAdmin();
                case 'user':
                    return self::roleUser() || self::roleEditor() || self::roleAdmin();
            }
        }
        return false;
    }
}
