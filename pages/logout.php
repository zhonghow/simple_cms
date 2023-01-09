<?php

// User is logged in

// var_dump(Authentication::isLoggedIn());
if (Authentication::isLoggedIn()) {
    Authentication::logOut();
}

header('Location: /login');
exit;
