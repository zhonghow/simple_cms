<?php

class User
{
    public static function getAllUsers()
    {
        return Database::connectDatabase()->selectData(
            'SELECT * FROM users ORDER BY id DESC',
            [],
            true
        );
    }

    public static function userID($user_id)
    {
        return Database::connectDatabase()->selectData(
            'SELECT * FROM users WHERE id = :id',
            [
                'id' => $user_id
            ],
        );
    }

    public static function updateUser($id, $name, $email, $role, $password = null)
    {
        // Setup Params
        $params = [
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'role' => $role,
        ];

        // If no password, skip this v
        if ($password) {
            $params['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Update user data into database
        return Database::connectDatabase()->updateData(
            'UPDATE users SET name = :name, email = :email,' . ($password ? 'password = :password,' : '') . 'role = :role WHERE id = :id',
            $params
        );
    }

    public static function addUser($name, $email, $password, $role)
    {
        return Database::connectDatabase()->insertData(
            'INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)',
            [
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => $role
            ]
        );
    }

    public static function deleteUser($id)
    {
        Database::connectDatabase()->deleteData(
            'DELETE FROM users WHERE id = :id',
            [
                'id' => $id
            ]
        );
    }
}
