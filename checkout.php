<?php
require_once 'includes/functions.php';
$items = getCartItems($conn);
if (empty($items)) header('Location: products.php');

$subtotal = getCartTotal($conn);
$shipping = $subtotal >= 100 ? 0 : 10;
$tax = $subtotal * 0.1;
$total = $subtotal + $shipping + $tax;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    clearCart();
    echo "<script>alert('Order placed successfully! (Demo Mode)'); window.location='index.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout - ModernMart</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="header">
        <div class="container nav">
            <a href="index.php" class="logo">ModernMart</a>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="products.php">Products</a>
                <a href="cart.php" class="cart-icon">🛒<span class="cart-count"><?= array_sum($_SESSION['cart']) ?></span></a>
                <?php if (isLoggedIn()): ?>
                    <a href="logout.php">Logout (<?= $_SESSION['full_name'] ?>)</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="container">
        <div style="display:grid; grid-template-columns:2fr 1fr; gap:2rem;">
            <div class="cart-container">
                <h2 style="margin-bottom:2rem;">Checkout</h2>
                <form method="POST">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" value="<?= isLoggedIn() ? $_SESSION['full_name'] : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" rows="3" required></textarea>
                    </div>
                    
                    <h3 style="margin:2rem 0 1rem;">Payment Method</h3>
                    <div style="margin-bottom:2rem;">
                        <label style="display:block; border:2px solid #e5e7eb; border-radius:0.5rem; padding:1rem; text-align:center; background:#f0f7ff;">
                            <input type="radio" name="payment" value="demo" checked> 💳 Demo Payment (Test Mode)
                        </label>
                    </div>
                    
                    <button type="submit" class="btn" style="width:100%;">Place Order • $<?= number_format($total, 2) ?></button>
                </form>
            </div>
            
            <div class="cart-container">
                <h3 style="margin-bottom:1rem;">Order Summary</h3>
                <?php foreach ($items as $item): ?>
                <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem;">
                    <span><?= $item['name'] ?> x<?= $item['quantity'] ?></span>
                    <span>$<?= number_format($item['subtotal'], 2) ?></span>
                </div>
                <?php endforeach; ?>
                <hr style="margin:1rem 0;">
                <div style="display:flex; justify-content:space-between;">
                    <span>Subtotal</span>
                    <span>$<?= number_format($subtotal, 2) ?></span>
                </div>
                <div style="display:flex; justify-content:space-between;">
                    <span>Shipping</span>
                    <span><?= $shipping > 0 ? '$'.number_format($shipping,2) : 'Free' ?></span>
                </div>
                <div style="display:flex; justify-content:space-between;">
                    <span>Tax (10%)</span>
                    <span>$<?= number_format($tax, 2) ?></span>
                </div>
                <hr style="margin:1rem 0;">
                <div style="display:flex; justify-content:space-between; font-size:1.3rem; font-weight:bold; color:#667eea;">
                    <span>Total</span>
                    <span>$<?= number_format($total, 2) ?></span>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/script.js"></script>
</body>
</html>