<?php

// Static Class
class formValidation
{

    public static function errorValidate($data, $rules = [])
    {
        $error = false;
        foreach ($rules as $key => $condition) {
            switch ($condition) {
                case 'required':
                    if (empty($data[$key])) {
                        $error .=  ucwords($key) . " field is required. <br />";
                    }
                    break;
                case 'passwordLength':
                    if (empty($data[$key])) {
                        $error .= ucwords($key) . " field is required.";
                    } else if (strlen($data[$key]) < 8) {
                        $error .= "Password must be more than 8 characters <br />";
                    }
                    break;
                case 'password_match':
                    if ($data['password'] !== $data['confirm_password']) {
                        $error .= 'Password & Confirmation Password does not match. <br />';
                    }
                    break;
                case 'email_required':
                    if (empty($data[$key])) {
                        $error .=  ucwords($key) . " field is required. <br />";
                    } else if (!filter_var($data[$key], FILTER_VALIDATE_EMAIL)) {
                        $error .= 'Email provided is invalid. <br />';
                    }
                    break;
                case 'login_form_csrf_token':
                    if (!CSRF::verifyToken($data[$key], 'login_form')) {
                        $error .= 'Invalid CSRF Token <br />';
                    }
                    break;
                case 'signup_form_csrf_token':
                    if (!CSRF::verifyToken($data[$key], 'signup_form')) {
                        $error .= 'Invalid CSRF Token <br />';
                    }
                    break;
                case 'edit_user_form_csrf_token':
                    if (!CSRF::verifyToken($data[$key], 'edit_user_form')) {
                        $error .= 'Invalid CSRF Token <br />';
                    }
                    break;
                case 'add_user_form_csrf_token':
                    if (!CSRF::verifyToken($data[$key], 'add_user_form')) {
                        $error .= 'Invalid CSRF Token <br />';
                    }
                    break;
                case 'delete_user_form_csrf_token':
                    if (!CSRF::verifyToken($data[$key], 'delete_user_form')) {
                        $error .= 'Invalid CSRF Token <br />';
                    }
                    break;
                case 'add_post_form_csrf_token':
                    if (!CSRF::verifyToken($data[$key], 'add_post_form')) {
                        $error .= 'Invalid CSRF Token <br />';
                    }
                    break;
                case 'edit_post_form_csrf_token':
                    if (!CSRF::verifyToken($data[$key], 'edit_post_form')) {
                        $error .= 'Invalid CSRF Token <br />';
                    }
                    break;
                case 'delete_post_form_csrf_token':
                    if (!CSRF::verifyToken($data[$key], 'delete_post_form')) {
                        $error .= 'Invalid CSRF Token <br />';
                    }
                    break;
            }
        }
        // Do Form Validation

        return $error;
    }

    public static function emailUnique($email)
    {
        // Check if email is already used by another user.
        $user = Database::connectDatabase()->selectData(
            'SELECT * FROM users WHERE email = :email',
            [
                'email' => $email
            ]
        );

        // If user with the same email already exist
        if ($user) {
            return 'Email already exist';
        }

        return false;
    }
}
