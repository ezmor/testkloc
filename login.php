<?php
include_once 'includes/config.php';
include_once 'includes/db.php';
include_once 'includes/functions.php';

session_start();

// Redirect to dashboard if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Attempt to log in the user
    $user_id = authenticate_user($username, $password);

    if ($user_id) {
        // User authentication successful, set session and redirect to dashboard
        $_SESSION['user_id'] = $user_id;
        header('Location: dashboard.php');
        exit;
    } else {
        // User authentication failed, show error message
        $error_message = 'Invalid username or password. Please try again.';
    }
}

include 'templates/header.php';
?>

<h2>Login</h2>

<?php if (isset($error_message)): ?>
    <div class="alert alert-danger" role="alert">
        <?=htmlspecialchars($error_message)?>
    </div>
<?php endif; ?>

<form action="login.php" method="post">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
</form>

<p class="mt-3">
    Don't have an account? <a href="register.php">Register here</a>.
</p>

<?php include 'templates/footer.php'; ?>
