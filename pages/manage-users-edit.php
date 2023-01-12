<?php

if (!Authentication::accessControl('admin')) {
    header("Location: /login");
    exit;
}

// set CSRF token
CSRF::generateToken('edit_user_form');

// Load User Data
$user = User::userID($_GET['id']);
// Post request
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $password_change = (isset($_POST['password']) && !empty($_POST['password'])
        || isset($_POST['confirm_password']) && !empty($_POST['confirm_password']) ? true : false
    );
    // Error Check
    // If Both password is empty, skip error checking for both field

    $rules = [
        'name' => 'required',
        'email' => 'email_required',
        'role' => 'required',
        'csrf_token' => 'edit_user_form_csrf_token'
    ];

    if ($password_change) {
        $rules['password'] = 'passwordLength'; // Check password length >= 8
        $rules['confirm_password'] = 'password_match'; // Check confirm_password matches password_match;
    }

    $error = formValidation::errorValidate(
        $_POST,
        $rules
    );

    // Check for email changes
    // $user['email'] is from database
    // $_POST['email'] is from form submit
    if ($user['email'] !== $_POST['email']) {
        // Check email doesnt belongs to another user
        $error .= formValidation::emailUnique($_POST['email']);
    }

    if (!$error) { // Make sure there is no error

        // Update User
        User::updateUser(
            $user['id'],
            $_POST['name'],
            $_POST['email'],
            $_POST['role'],
            ($password_change ? $_POST['password'] : null)
            // Password update
        );
        // Remove Csrf
        CSRF::removeToken('edit_user_form');

        // Redirect back to the same page
        header('Location: /manage-users');
        exit;
    }
}

require dirname(__DIR__) . "/parts/header.php";
?>

<div class="container mx-auto my-5" style="max-width: 700px;">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Edit User</h1>
    </div>
    <div class="card mb-2 p-4">
        <?php require dirname(__DIR__) . "/parts/error.php"; ?>
        <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
            <div class="mb-3">
                <div class="row">
                    <div class="col">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= $user['name'] ?>" />
                    </div>
                    <div class="col">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>" />
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
                    <option value="">Select an option</option>
                    <option value="user" <?= ($user['role'] == 'user' ? 'selected' : ''); ?>>User</option>
                    <option value="editor" <?= ($user['role'] == 'editor' ? 'selected' : ''); ?>>Editor</option>
                    <option value="admin" <?= ($user['role'] == 'admin' ? 'selected' : ''); ?>>Admin</option>
                </select>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
            <input type="hidden" name="csrf_token" value="<?= CSRF::getToken('edit_user_form') ?>">
        </form>
    </div>
    <div class="text-center">
        <a href="/manage-users" class="btn btn-link btn-sm"><i class="bi bi-arrow-left"></i> Back to Users</a>
    </div>
</div>


<?php
require dirname(__DIR__) . "/parts/footer.php";
?>