<?php

require dirname(__DIR__) . "/parts/header.php";

?>

<div class="container mx-auto my-5" style="max-width: 500px;">
    <h1 class="h1 mb-4 text-center">My Blog</h1>

    <?php foreach (Post::getPublishedPost() as $posts) : ?>
        <div class="card mb-2">
            <div class="card-body">
                <h5 class="card-title"><?= $posts['title'] ?></h5>
                <div class="row">
                    <p class="card-text"><?= substr(nl2br($posts['content']), 0, 100) . "..." ?></p> ?>
                </div>
                <div class="text-end mt-2">
                    <a href="/post?id=<?= $posts['id'] ?>" class="btn btn-primary btn-sm">Read More</a>
                </div>
            </div>
        </div>
    <?php endforeach ?>

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