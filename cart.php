<?php
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $action = $_POST['action'] ?? '';
    if ($action === 'add') addToCart($_POST['product_id']);
    if ($action === 'update') updateCartQuantity($_POST['product_id'], $_POST['quantity']);
    if ($action === 'remove') removeFromCart($_POST['product_id']);
    echo json_encode(['success' => true]);
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'get_count') {
    echo json_encode(['count' => array_sum($_SESSION['cart'])]);
    exit;
}

$items = getCartItems($conn);
$total = getCartTotal($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cart - ModernMart</title>
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
        <h1 style="color:white; margin:2rem 0;">Shopping Cart</h1>
        
        <?php if (empty($items)): ?>
            <div class="cart-container" style="text-align:center; padding:4rem;">
                <h2>Your cart is empty</h2>
                <a href="products.php" class="btn" style="margin-top:1rem;">Start Shopping</a>
            </div>
        <?php else: ?>
            <div class="cart-container">
                <?php foreach ($items as $item): ?>
                <div class="cart-item">
                    <div class="cart-item-image">
                        <img src="<?= generateProductImage($item['name']) ?>" alt="<?= $item['name'] ?>">
                    </div>
                    <div>
                        <h3><?= htmlspecialchars($item['name']) ?></h3>
                        <p style="color:#666;">$<?= number_format($item['price'], 2) ?></p>
                    </div>
                    <div>
                        <div class="quantity-control">
                            <button class="qty-btn" onclick="updateQty(<?= $item['id'] ?>, <?= $item['quantity']-1 ?>)">-</button>
                            <span style="min-width: 40px; text-align: center;"><?= $item['quantity'] ?></span>
                            <button class="qty-btn" onclick="updateQty(<?= $item['id'] ?>, <?= $item['quantity']+1 ?>)">+</button>
                        </div>
                        <button class="remove-btn" onclick="removeItem(<?= $item['id'] ?>)">Remove</button>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <div style="text-align:right; margin-top:2rem; padding-top:1rem; border-top:2px solid #eee;">
                    <h2 style="color:#667eea;">Total: $<?= number_format($total, 2) ?></h2>
                    <a href="checkout.php" class="btn" style="margin-top:1rem;">Proceed to Checkout</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
    function updateQty(id, qty) {
        if (qty < 1) return;
        fetch('cart.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'action=update&product_id=' + id + '&quantity=' + qty
        }).then(() => location.reload());
    }
    
    function removeItem(id) {
        if (confirm('Remove this item?')) {
            fetch('cart.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=remove&product_id=' + id
            }).then(() => location.reload());
        }
    }
    </script>
    <script src="assets/js/script.js"></script>
</body>
</html>