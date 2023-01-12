<?php

if (!Authentication::accessControl('admin')) {
    header("Location: /login");
    exit;
}

CSRF::generateToken('delete_user_form');

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $error = formValidation::errorValidate(
        $_POST,
        [
            'id' => 'required',
            'csrf_token' => 'delete_user_form_csrf_token'
        ]
    );

    if (!$error) {

        User::deleteUser($_POST['id']);

        CSRF::removeToken('delete_user_form');

        header('Location: /manage-users');
        exit;
    }
}


require dirname(__DIR__) . "/parts/header.php";
?>

<div class="container mx-auto my-5" style="max-width: 700px;">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Manage Users</h1>
        <div class="text-end">
            <a href="/manage-users-add" class="btn btn-primary btn-sm">Add New User</a>
        </div>
    </div>
    <div class="card mb-2 p-4">
        <?php require dirname(__DIR__) . "/parts/error.php" ?>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col" class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach (User::getAllUsers() as $index => $user) : ?>
                    <tr>
                        <th scope="row"><?= $index + 1 ?></th>
                        <td><?= $user['name'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <?php if ($user['role'] == 'user') : ?>
                            <td><span class="badge bg-success"><?= ucwords($user['role']) ?></span></td>
                        <?php elseif ($user['role'] == 'editor') : ?>
                            <td><span class="badge bg-primary"><?= ucwords($user['role']) ?></span></td>
                        <?php elseif ($user['role'] == 'admin') : ?>
                            <td><span class="badge bg-danger"><?= ucwords($user['role']) ?></span></td>
                        <?php endif ?>
                        <td class="text-end">
                            <?php if ($_SESSION['user']['id'] !== $user['id'] && $user['role'] !== "admin") : ?>
                                <div class="buttons">
                                    <a value href="/manage-users-edit?id=<?= $user['id'] ?>" class="btn btn-success btn-sm me-2"><i class="bi bi-pencil"></i></a>

                                    <!-- Delete Button -->
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#user-<?= $user['id'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                    <div class="modal fade" id="user-<?= $user['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content text-bg-dark">
                                                <div class="modal-header border border-0">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Delete User</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-bg-dark text-start border border-0">
                                                    User ID = #<?= $user['id'] ?> <br />
                                                    User Name = <?= $user['name'] ?> <br />
                                                    User Email = <?= $user['email'] ?> <br /> <br />
                                                    Are you sure that you want to delete this user? <br> Action can't be undone after confirmation.
                                                    Press 'Confirm' to confirm deletion
                                                </div>
                                                <div class="modal-footer border border-0">
                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>

                                                    <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
                                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                                        <input type="hidden" name="csrf_token" value="<?= CSRF::getToken('delete_user_form') ?>">
                                                        <button type="submit" class="btn btn-sm btn-danger">Confirm</button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- Delete Button -->

                                </div>
                            <?php endif ?>
                        </td>
                    </tr>

                <?php endforeach; ?>

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