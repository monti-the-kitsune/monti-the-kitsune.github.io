<?php
session_start();
include 'data.php';
$products_result = $conn->query("SELECT id, name, price, stock FROM products");
$orders_result = $conn->query("SELECT o.id, o.user_session_id, o.product_id, o.quantity, o.order_date, p.name, p.price FROM orders o JOIN products p ON o.product_id = p.id ORDER BY o.order_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@300..700&family=Inconsolata:wght@200..900&family=Lato:wght@100;300;400;700;900&family=ZCOOL+KuaiLe&display=swap" rel="stylesheet">
    <title>Admin - ALTERNATE</title>
</head>
<body>
    <nav class="navbar">
        <a href="index.html" class="brandContainer navLink">
            <img src="img/icon.png" alt="icon" class="navItem" id="icon">
            <h2 class="navItem" id="brand">ALTERNATE</h2>
        </a>
        <h2 class="navItem"><a href="pages/gadgets.html" class="navLink">GADGETS</a></h2>
        <h2 class="navItem"><a href="cart_view.php" class="navLink">CART</a></h2>
        <h2 class="navItem"><a href="admin.php" class="navLink">ADMIN</a></h2>
        <button id="themeToggle" class="navItem">☀️</button>
    </nav>

    <div class="mainContent">
        <h1 class="title2">Admin - Product Stock</h1>
        <div class="products">
            <?php if ($products_result->num_rows > 0): ?>
                <table class="adminTable">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                    </tr>
                    <?php while ($row = $products_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td>$<?php echo number_format($row['price'], 2); ?></td>
                            <td><?php echo $row['stock']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p class="subtitle2">No products available.</p>
            <?php endif; ?>
        </div>

        <h1 class="title2">User Orders</h1>
        <div class="products">
            <?php if ($orders_result->num_rows > 0): ?>
                <table class="adminTable">
                    <tr>
                        <th>Order ID</th>
                        <th>User Session ID</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Order Date</th>
                    </tr>
                    <?php while ($row = $orders_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars(substr($row['user_session_id'], 0, 8)) . '...'; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td>$<?php echo number_format($row['price'], 2); ?></td>
                            <td>$<?php echo number_format($row['price'] * $row['quantity'], 2); ?></td>
                            <td><?php echo $row['order_date']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p class="subtitle2">No orders placed yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const toggleButton = document.getElementById("themeToggle");
            const body = document.body;
            const savedTheme = localStorage.getItem("theme");
            if (savedTheme === "light") {
                body.classList.add("light-mode");
                toggleButton.textContent = "🌙";
            }
            toggleButton?.addEventListener("click", () => {
                const isLight = body.classList.toggle("light-mode");
                toggleButton.textContent = isLight ? "🌙" : "☀️";
                localStorage.setItem("theme", isLight ? "light" : "dark");
            });
        });
    </script>
</body>
</html>
<?php
$products_result->close();
$orders_result->close();
$conn->close();
?>