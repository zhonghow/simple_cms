<?php

if (!Authentication::accessControl('admin')) {
    header("Location: /login");
    exit;
}

CSRF::generateToken('add_user_form');

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $rules = [
        'name' => 'required',
        'email' => 'email_required',
        'role' => 'required',
        'csrf_token' => 'add_user_form_csrf_token',
        'password' => 'passwordLength',
        'confirm_password' => 'password_match'
    ];

    $error = formValidation::errorValidate(
        $_POST,
        $rules
    );

    if (formValidation::emailUnique($_POST['email'])) {
        $error .= formValidation::emailUnique($_POST['email']);
    }

    if (!$error) { // Make sure there is no error

        User::addUser(
            $_POST['name'],
            $_POST['email'],
            $_POST['password'],
            $_POST['role']
        );
        // Remove Csrf
        CSRF::removeToken('add_user_form');

        // Redirect back to the same page
        header('Location: /manage-users');
        exit;
    }
}

require dirname(__DIR__) . "/parts/header.php";
?>

<div class="container mx-auto my-5" style="max-width: 700px;">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Add New User</h1>
    </div>
    <div class="card mb-2 p-4">
        <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
            <?php require dirname(__DIR__) . "/parts/error.php" ?>
            <div class="mb-3">
                <div class="row">
                    <div class="col">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" />
                    </div>
                    <div class="col">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" />
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <div class="row">
                    <div class="col">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" />
                    </div>
                    <div class="col">
                        <label for="confirm-password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm-password" name="confirm_password" />
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-control" id="role" name="role">
                    <option selected disabled value="">Select an option</option>
                    <option value="user">User</option>
                    <option value="editor">Editor</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Add</button>
            </div>
            <input type="hidden" name="csrf_token" value="<?= CSRF::getToken('add_user_form') ?>">
        </form>
    </div>
    <div class="text-center">
        <a href="/manage-users" class="btn btn-link btn-sm"><i class="bi bi-arrow-left"></i> Back to Users</a>
    </div>
</div>

<?php
require dirname(__DIR__) . "/parts/footer.php";
?>