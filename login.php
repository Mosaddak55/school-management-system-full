<?php
require_once 'includes/header.php';

if (isLoggedIn()) {
    redirect(getUserType() . '/dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'includes/auth.php';
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (loginUser($username, $password)) {
        redirect(getUserType() . '/dashboard.php');
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<section class="auth-form">
    <h2>Login</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="login.php">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <button type="submit" class="btn">Login</button>
    </form>
    
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</section>

<?php require_once 'includes/footer.php'; ?>