<?php
session_start();
require_once 'data.php';
$session_id = session_id();

// Fetch cart items
$cart_items = [];
$total_price = 0;
$result = $conn->query("SELECT c.id, c.product_id, c.quantity, p.name, p.price, p.image_url FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_session_id = '$session_id'");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['subtotal'] = $row['price'] * $row['quantity'];
        $cart_items[] = $row;
        $total_price += $row['subtotal'];
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@300..700&family=Inconsolata:wght@200..900&family=Lato:wght@100;300;400;700;900&family=ZCOOL+KuaiLe&display=swap" rel="stylesheet">
    <title>Cart - ALTERNATE</title>
</head>
<body>
    <nav class="navbar">
        <a href="index.html" class="brandContainer navLink">
            <img src="img/icon.png" alt="icon" class="navItem" id="icon">
            <h2 class="navItem" id="brand">ALTERNATE</h2>
        </a>
        <h2 class="navItem"><a href="pages/gadgets.html" class="navLink">GADGETS</a></h2>
        <h2 class="navItem"><a href="pages/others.html" class="navLink">OTHERS</a></h2>
        <h2 class="navItem"><a href="cart_view.php" class="navLink">CART</a></h2>
        <h2 class="navItem"><a href="admin.php" class="navLink">ADMIN</a></h2>
        <button id="themeToggle" class="navItem">☀️</button>
    </nav>

    <div class="mainContent">
        <h1 class="title2">Your Cart</h1>
        <?php if (empty($cart_items)): ?>
            <p class="subtitle2">Your cart is empty.</p>
        <?php else: ?>
            <div class="products">
                <?php foreach ($cart_items as $item): ?>
                    <div class="productElement">
                        <img src="<?php echo htmlspecialchars($item['image_url'] ?: 'img/placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="productImage">
                        <h2 class="productTitle"><?php echo htmlspecialchars($item['name']); ?></h2>
                        <p class="productDescription">Quantity: <?php echo $item['quantity']; ?></p>
                        <p class="productPrice">Price: $<?php echo number_format($item['price'], 2); ?></p>
                        <p class="productPrice">Total: $<?php echo number_format($item['subtotal'], 2); ?></p>
                        <form class="removeForm" method="post" action="remove_from_cart.php">
                            <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                            <input type="submit" value="Remove" class="buyButton removeButton">
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
            <p class="subtitle2">Total Price: $<?php echo number_format($total_price, 2); ?></p>
            <form id="checkoutForm" class="checkoutForm" method="post" action="checkout.php">
                <input type="submit" value="Checkout" class="buyButton checkoutButton">
            </form>
        <?php endif; ?>
    </div>

    <footer class="footer">
        <p>&copy; 2025 ALTERNATE</p>
        <div class="footerContent">
            <p class="footerHeader">About Us</p>
            <p class="footerText">Your trusted source for high-quality gaming peripherals and electronics.</p>
        </div>
        <div class="footerContent">
            <p class="footerHeader">Contact Us</p>
            <p class="footerText">Email: support@alternate.com</p>
        </div>
    </footer>
    <script src="app.js"></script>
</body>
</html>