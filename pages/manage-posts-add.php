<?php

if (!Authentication::accessControl('user')) {
    header("Location: /login");
    exit;
}

CSRF::generateToken('add_post_form');

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $rules = [
        'title' => 'required',
        'content' => 'required',
        'csrf_token' => 'add_post_form_csrf_token'
    ];

    $error = formValidation::errorValidate(
        $_POST,
        $rules
    );

    if (!$error) {
        Post::addPost(
            $_POST['content'],
            $_POST['title'],
            $_SESSION['user']['id']
        );

        CSRF::removeToken('add_post_token');

        header('Location: /manage-posts');
        exit;
    }
}

require dirname(__DIR__) . "/parts/header.php";
?>
<div class="container mx-auto my-5" style="max-width: 700px;">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Add New Post</h1>
    </div>
    <div class="card mb-2 p-4">
        <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
            <?php require dirname(__DIR__) . "/parts/error.php" ?>
            <div class="mb-3">
                <label for="post-title" class="form-label">Title</label>
                <input type="text" class="form-control" id="post-title" name="title" />
            </div>
            <div class="mb-3">
                <label for="post-content" class="form-label">Content</label>
                <textarea class="form-control" id="post-content" rows="10" name="content"></textarea>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-primary">Add</button>
            </div>
            <input type="hidden" name="csrf_token" value="<?= CSRF::getToken('add_post_form') ?>">
        </form>
    </div>
    <div class="text-center">
        <a href="/manage-posts" class="btn btn-link btn-sm"><i class="bi bi-arrow-left"></i> Back to Posts</a>
    </div>
</div>


<?php
require dirname(__DIR__) . "/parts/footer.php";
?>