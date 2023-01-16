<?php

if (!Authentication::accessControl('user')) {
  header("Location: /login");
  exit;
}

CSRF::generateToken('edit_post_form');

$post = Post::getPostID($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === "POST") {

  $error = formValidation::errorValidate(
    $_POST,
    [
      'title' => 'required',
      'content' => 'required',
      'status' => 'required',
      'csrf_token' => 'edit_post_form_csrf_token'
    ]
  );

  if (!$error) {

    Post::updatePost(
      $post['id'],
      $_POST['title'],
      $_POST['content'],
      $_POST['status']
    );

    CSRF::removeToken('edit_post_form');

    header('Location: /manage-posts');
    exit;
  }
}


require dirname(__DIR__) . "/parts/header.php";
?>


<div class="container mx-auto my-5" style="max-width: 700px;">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h1 class="h1">Edit Post</h1>
  </div>
  <div class="card mb-2 p-4">
    <?php require dirname(__DIR__) . "/parts/error.php"; ?>
    <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
      <div class="mb-3">
        <label for="post-title" class="form-label">Title</label>
        <input type="text" class="form-control" id="post-title" name="title" value="<?= $post['title'] ?>" />
      </div>
      <div class="mb-3">
        <label for="post-content" class="form-label">Content</label>
        <textarea class="form-control" id="post-content" rows="10" name="content"><?= $post['content'] ?></textarea>
      </div>
      <div class="mb-3">
        <label for="post-content" class="form-label">Status</label>
        <select class="form-control" id="post-status" name="status">
          <?php if (Authentication::accessControl('editor')) : ?>
            <option value="review" <?= $post['status'] == 'review' ? 'selected' : '' ?>>Pending for Review</option>
            <option value="publish" <?= $post['status'] == 'publish' ? 'selected' : '' ?>>Publish</option>
          <?php else : ?>
            <option value="review" <?= $post['status'] == 'review' ? 'selected' : '' ?>>Pending for Review</option>
          <?php endif ?>
        </select>
      </div>
      <div class="text-end">
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
      <input type="hidden" name="csrf_token" value="<?= CSRF::getToken('edit_post_form') ?>">
    </form>

  </div>
  <div class="text-center">
    <a href="/manage-posts" class="btn btn-link btn-sm"><i class="bi bi-arrow-left"></i> Back to Posts</a>
  </div>
</div>


<?php
require dirname(__DIR__) . "/parts/footer.php";
?>