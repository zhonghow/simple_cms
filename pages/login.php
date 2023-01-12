<?php

// Set CSRF Token
CSRF::generateToken('login_form');

// Make sure user is not logged in, else redirect to dashboard page
if (Authentication::isLoggedIn()) {
  header('Location: /dashboard');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $email = $_POST['email'];
  $password = $_POST['password'];
  // Step 1 -> Do error check
  $error = formValidation::errorValidate(
    $_POST,
    [
      'email' => 'required', // Email = Key, 'required' = Condition
      'password' => 'required', // Password = Key, 'required' = Condition
      'csrf_token' => 'login_form_csrf_token'
    ]
  );

  // Make sure there is no error.
  if (!$error) {
    /* -------------------------------------------------------------------------- */
    // Step 2 -> Login the user
    // If $user_id is false, either email or password is incorrect.
    // Step 3 -> Assign the user to $_SESSION['user'];
    // Step 4 -> Remove CSRF Token & Redirect the user to dashboard
    // |-> Step 4.1 -> Remove CSRF Token
    // |-> Step 4.2 -> Redirect To Dashboard
    /* -------------------------------------------------------------------------- */

    $user_id = Authentication::logIn($email, $password);

    if (!$user_id) {
      $error = "Email or Password is incorrect";
    } else {
      Authentication::setUserSession($user_id);

      CSRF::removeToken('login_form');
      header('Location: /dashboard');
      exit;
    }
  }
}
require dirname(__DIR__) . "/parts/header.php";
?>

<div class="container my-5 mx-auto" style="max-width: 500px;">
  <h1 class="h1 mb-4 text-center">Login</h1>

  <div class="card p-4">
    <?php require dirname(__DIR__) . "/parts/error.php" ?>
    <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
      <div class="mb-2">
        <label for="email" class="visually-hidden">Email</label>
        <input type="text" class="form-control" id="email" name="email" placeholder="email@example.com" />
      </div>
      <div class="mb-2">
        <label for="password" class="visually-hidden">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Password" />
      </div>
      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Login</button>
      </div>
      <input type="hidden" name="csrf_token" value="<?= CSRF::getToken('login_form') ?>">
    </form>
  </div>

  <!-- links -->
  <div class="d-flex justify-content-between align-items-center gap-3 mx-auto pt-3">
    <a href="/" class="text-decoration-none small"><i class="bi bi-arrow-left-circle"></i> Go back</a>
    <a href="/signup" class="text-decoration-none small">Don't have an account? Sign up here
      <i class="bi bi-arrow-right-circle"></i></a>
  </div>
</div>


<?php
require dirname(__DIR__) . "/parts/footer.php";
?>