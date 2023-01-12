<?php

class CSRF
{
    // Generate New Token //
    public static function generateToken($prefix = '')
    {
        if (!isset($_SESSION[$prefix . '_csrf_token'])) {
            $_SESSION[$prefix . '_csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    // Verify Token - Make sure it matches the one provided in form data //
    public static function verifyToken($formToken, $prefix = '')
    {
        if (isset($_SESSION[$prefix . '_csrf_token']) && $formToken === $_SESSION[$prefix . '_csrf_token']) {
            return true;
        }
        return false;
    }

    // Retrieve the existing token //
    public static function getToken($prefix = '')
    {
        if (isset($_SESSION[$prefix . '_csrf_token'])) {
            return $_SESSION[$prefix . '_csrf_token'];
        }
        return false;
    }

    // Remove the existing token //
    public static function removeToken($prefix = '')
    {
        if (isset($_SESSION[$prefix . '_csrf_token'])) {
            unset($_SESSION[$prefix . '_csrf_token']);
        }
    }
}
