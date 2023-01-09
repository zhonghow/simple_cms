<?php



// set CSRF Token

// Check if user is already logged in
if (Authentication::isLoggedIn()) {
    header('Location: /dashboard');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Step 1 -> Error Check //

    // Step 2 -> Check if email already exist in database //

    // Step 3 -> Insert user into database //
    $user_id = Authentication::signUp(
        $name,
        $email,
        $password
    );
    // Step 4 -> Assign user data to $_SESSION['user] data //
    Authentication::setUserSession($user_id);

    // Step 5 -> Redirect user to dashboard //
    // |-> 5.1 -> Remove CSRF Token
    // |-> 5.2 -> Redirect user to dashboard
    header('Location: /dashboard');
    exit;
}

require dirname(__DIR__) . "/parts/header.php";
?>

<div class="container my-5 mx-auto" style="max-width: 500px;">
    <h1 class="h1 mb-4 text-center">Sign Up a New Account</h1>

    <div class="card p-4">
        <form method="POST" action="<?= $_SERVER['REQUEST_URI']; ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" />
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" />
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" />
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" />
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-fu">
                    Sign Up
                </button>
            </div>
            <!-- Insert CSRF Token input -->
        </form>
    </div>

    <!-- links -->
    <div class="d-flex justify-content-between align-items-center gap-3 mx-auto pt-3">
        <a href="/" class="text-decoration-none small"><i class="bi bi-arrow-left-circle"></i> Go back</a>
        <a href="/login" class="text-decoration-none small">Already have an account? Login here
            <i class="bi bi-arrow-right-circle"></i></a>
    </div>
</div>


<?php
require dirname(__DIR__) . "/parts/footer.php";
?>