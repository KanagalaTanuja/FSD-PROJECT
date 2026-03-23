<?php require_once 'includes/functions.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Products - ModernMart</title>
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
                <?php if (isLoggedIn()): ?>
                    <a href="logout.php">Logout (<?= $_SESSION['full_name'] ?>)</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="container">
        <h1 style="color:white; margin:2rem 0;">All Products (15 Items)</h1>
        <div class="products-grid">
            <?php foreach (getProducts($conn) as $p): ?>
            <div class="product-card">
                <div class="product-image">
                    <img src="<?= generateProductImage($p['name']) ?>" alt="<?= $p['name'] ?>">
                </div>
                <div class="product-info">
                    <h3><?= htmlspecialchars($p['name']) ?></h3>
                    <p><?= htmlspecialchars(substr($p['description'], 0, 60)) ?>...</p>
                    <div class="price">$<?= number_format($p['price'], 2) ?></div>
                    <button class="btn add-to-cart" data-id="<?= $p['id'] ?>">Add to Cart</button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="assets/js/script.js"></script>
</body>
</html>