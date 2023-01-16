<?php

if (!Authentication::accessControl('user')) {
    header("Location: /login");
    exit;
}

$published_post = Post::getPublishedPost();

CSRF::generateToken('delete_post_form');

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $error = formValidation::errorValidate(
        $_POST,
        [
            'id' => 'required',
            'csrf_token' => 'delete_post_form_csrf_token'
        ]
    );

    if (!$error) {
        Post::deletePost($_POST['id']);

        CSRF::removeToken('delete_post_form');

        header('Location: /manage-posts');
        exit;
    }
}



require dirname(__DIR__) . "/parts/header.php";
?>

<div class="container mx-auto my-5" style="max-width: 700px;">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Manage Posts</h1>
        <div class="text-end">
            <a href="/manage-posts-add" class="btn btn-primary btn-sm">Add New Post</a>
        </div>
    </div>
    <div class="card mb-2 p-4">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col" style="width: 40%;">Title</th>
                    <th scope="col">Status</th>
                    <th scope="col" class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (Post::getAllPosts() as $index => $post) : ?>
                    <?php if ($post['user_id'] == $_SESSION['user']['id'] || Authentication::accessControl('editor')) : ?>
                        <tr>
                            <th scope="row"><?= $index + 1 ?></th>
                            <td><?= $post['title'] ?></td>
                            <?php if ($post['status'] == 'review') : ?>
                                <td><span class="badge bg-warning"><?= ucwords($post['status']) ?></span></td>
                            <?php elseif ($post['status'] == 'publish') : ?>
                                <td><span class="badge bg-success"><?= ucwords($post['status']) ?></span></td>
                            <?php endif ?>
                            <td class="text-end">
                                <div class="buttons">

                                    <a href="/post?id=<?= $post['id']; ?>" target="_blank" class="btn btn-primary btn-sm me-2  <?= ($post['status'] == 'review' ? 'disabled' : '') ?>"><i class="bi bi-eye"></i></a>
                                    <a href="/manage-posts-edit?id=<?= $post['id'] ?>" class="btn btn-secondary btn-sm me-2"><i class="bi bi-pencil"></i></a>

                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#post-<?= $post['id'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                    <div class="modal fade" id="post-<?= $post['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content text-bg-dark">
                                                <div class="modal-header border border-0">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Post</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-bg-dark text-start border border-0">
                                                    Post ID = #<?= $post['id'] ?> <br />
                                                    Post Title = <?= $post['title'] ?> <br /> <br>
                                                    Are you sure that you want to delete this post? <br> Action can't be undone after confirmation.
                                                    Press 'Confirm' to confirm deletion
                                                </div>
                                                <div class="modal-footer border border-0">
                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>

                                                    <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
                                                        <input type="hidden" name="id" value="<?= $post['id'] ?>">
                                                        <input type="hidden" name="csrf_token" value="<?= CSRF::getToken('delete_post_form') ?>">
                                                        <button type="submit" class="btn btn-sm btn-danger">Confirm</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </td>
                        </tr>
                    <?php endif ?>
                <?php endforeach ?>


            </tbody>
        </table>
    </div>
    <div class="text-center">
        <a href="/dashboard" class="btn btn-link btn-sm"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>


<?php
require dirname(__DIR__) . "/parts/footer.php";
?>