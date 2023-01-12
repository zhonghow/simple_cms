<?php

require dirname(__DIR__) . "/parts/header.php";

?>

<div class="container mx-auto my-5" style="max-width: 500px;">
    <h1 class="h1 mb-4 text-center">My Blog</h1>
    
    <div class="card mb-2">
        <div class="card-body">
            <h5 class="card-title">Post 4</h5>
            <p class="card-text">Here's some content about post 4</p>
            <div class="text-end">
                <a href="/post" class="btn btn-primary btn-sm">Read More</a>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center gap-3">
        <?php if (Authentication::isLoggedIn()) : ?>
            <a href="/dashboard" class="btn btn-link btn-sm">Dashboard</a>
            <a href="/logout" class="btn btn-link btn-sm">Log Out</a>
        <?php else : ?>
            <a href="/login" class="btn btn-link btn-sm">Login</a>
            <a href="/signup" class="btn btn-link btn-sm">Sign Up</a>
        <?php endif ?>
    </div>
</div>

<?php
require dirname(__DIR__) . "/parts/footer.php";
?>