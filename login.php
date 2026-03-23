<?php
require_once 'includes/functions.php';
if (isLoggedIn()) header('Location: index.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        if (loginUser($conn, $_POST['username'], $_POST['password'])) {
            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid username or password';
        }
    } elseif (isset($_POST['register'])) {
        if ($_POST['password'] !== $_POST['confirm_password']) {
            $error = 'Passwords do not match';
        } elseif (strlen($_POST['password']) < 6) {
            $error = 'Password must be at least 6 characters';
        } else {
            if (registerUser($conn, $_POST['username'], $_POST['email'], $_POST['password'], $_POST['full_name'])) {
                $success = 'Registration successful! You can now login.';
            } else {
                $error = 'Username or email already exists';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login / Register - ModernMart</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="header">
        <div class="container nav">
            <a href="index.php" class="logo">ModernMart</a>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="products.php">Products</a>
                <a href="cart.php" class="cart-icon">🛒<span class="cart-count">0</span></a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="auth-container">
            <div style="display:flex; gap:1rem; margin-bottom:2rem;">
                <button class="btn" style="flex:1;" onclick="showLogin()">Login</button>
                <button class="btn btn-outline" style="flex:1; background:transparent; border:2px solid #667eea; color:#667eea;" onclick="showRegister()">Register</button>
            </div>
            
            <?php if ($error): ?>
                <div class="error">⚠️ <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="success">✓ <?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            
            <div id="login-form">
                <form method="POST">
                    <input type="hidden" name="login" value="1">
                    <div class="form-group">
                        <label>Username or Email</label>
                        <input type="text" name="username" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>
                    <button type="submit" class="btn" style="width:100%;">Login</button>
                </form>
                <div class="demo-info">
                    <strong>Demo Account:</strong><br>
                    Username: john_doe<br>
                    Password: password123
                </div>
            </div>
            
            <div id="register-form" style="display:none;">
                <form method="POST">
                    <input type="hidden" name="register" value="1">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" required>
                    </div>
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="full_name" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn" style="width:100%;">Create Account</button>
                </form>
            </div>
        </div>
    </div>

    <script>
    function showLogin() {
        document.getElementById('login-form').style.display = 'block';
        document.getElementById('register-form').style.display = 'none';
    }
    function showRegister() {
        document.getElementById('login-form').style.display = 'none';
        document.getElementById('register-form').style.display = 'block';
    }
    </script>
    <script src="assets/js/script.js"></script>
</body>
</html>