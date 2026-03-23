<?php require_once 'includes/functions.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>ModernMart - Home</title>
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

    <div class="container" style="text-align:center; padding:4rem 0; color:white;">
        <h1 style="font-size:3rem; margin-bottom:1rem;">Welcome to ModernMart</h1>
        <p style="font-size:1.2rem; margin-bottom:2rem;">Discover amazing products at great prices</p>
        <a href="products.php" class="btn" style="padding:12px 30px;">Shop Now</a>
    </div>

    <div class="container">
        <h2 style="color:white; margin-bottom:2rem;">Featured Products</h2>
        <div class="products-grid">
            <?php 
            $products = getProducts($conn);
            foreach (array_slice($products, 0, 4) as $p): 
            ?>
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