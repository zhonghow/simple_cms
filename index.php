<?php

session_start();

require "includes/class-database.php"; // Require all the files
require "includes/class-user.php";
require "includes/class-authentication.php";



$path = trim($_SERVER['REQUEST_URI'], "/");
$path = parse_url($path, PHP_URL_PATH);



switch ($path) {
    case 'login':
        require "pages/login.php";
        break;
    case 'signup':
        require "pages/signup.php";
        break;
    case 'logout':
        require "pages/logout.php";
        break;
    case 'dashboard':
        require "pages/dashboard.php";
        break;
    case 'post':
        require "pages/post.php";
        break;
    case 'manage-users':
        require "pages/manage-users.php";
        break;
    case 'manage-users-add':
        require "pages/manage-users-add.php";
        break;
    case 'manage-users-edit':
        require "pages/manage-users-edit.php";
        break;
    case 'manage-posts':
        require "pages/manage-posts.php";
        break;
    case 'manage-posts-add':
        require "pages/manage-posts-add.php";
        break;
    case 'manage-posts-edit':
        require "pages/manage-posts-edit.php";
        break;
    default:
        require "pages/home.php";
        break;
}
