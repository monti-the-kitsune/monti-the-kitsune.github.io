<?php
session_start();
include 'data.php';
$session_id = session_id();
$result = $conn->query("SELECT c.id, c.product_id, c.quantity, p.name, p.price, p.image_url FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_session_id = '$session_id'");
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
        <h2 class="navItem"><a href="" class="navLink">HARDWARE</a></h2>
        <h2 class="navItem"><a href="pages/gadgets.html" class="navLink">GADGETS</a></h2>
        <h2 class="navItem"><a href="#" class="navLink">OTHERS</a></h2>
        <h2 class="navItem"><a href="cart_view.php" class="navLink">CART</a></h2>
        <button id="themeToggle" class="navItem">☀️</button>
    </nav>

    <div class="mainContent">
        <h1 class="title2">Your Cart</h1>
        <div class="products">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="productElement">
                        <img src="<?php echo htmlspecialchars($row['image_url'] ?: ''); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="productImage">
                        <h2 class="productTitle"><?php echo htmlspecialchars($row['name']); ?></h2>
                        <p class="productDescription">Quantity: <?php echo $row['quantity']; ?></p>
                        <p class="productPrice">Price: $<?php echo number_format($row['price'], 2); ?></p>
                        <p class="productPrice">Total: $<?php echo number_format($row['price'] * $row['quantity'], 2); ?></p>
                        <form class="removeForm" data-cart-id="<?php echo $row['id']; ?>">
                            <input type="hidden" name="cart_id" value="<?php echo $row['id']; ?>">
                            <input type="submit" value="Remove" class="buyButton removeButton">
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="subtitle2">Your cart is empty.</p>
            <?php endif; ?>
        </div>
        <?php if ($result->num_rows > 0): ?>
            <form id="checkoutForm" class="checkoutForm">
                <input type="submit" value="Checkout" class="buyButton checkoutButton">
            </form>
        <?php endif; ?>
    </div>

    <div class="toast" id="toast" style="display: none;"></div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
    // Theme toggle
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

    // Handle remove from cart
    const removeForms = document.querySelectorAll(".removeForm");
    removeForms.forEach(form => {
        form.addEventListener("submit", async (event) => {
            event.preventDefault();
            const formData = new FormData(form);
            try {
                const response = await fetch("remove_from_cart.php", {
                    method: "POST",
                    body: formData
                });
                if (!response.ok) throw new Error(`HTTP error: ${response.status}`);
                const result = await response.json();
                if (result.success) {
                    showToast("Item removed from cart!");
                    form.closest(".productElement").remove();
                    if (!document.querySelector(".productElement")) {
                        document.querySelector(".products").innerHTML = '<p class="subtitle2">Your cart is empty.</p>';
                        document.querySelector(".checkoutForm").style.display = "none";
                    }
                } else {
                    showToast("Error: " + result.message);
                }
            } catch (error) {
                showToast("Error removing item: " + error.message);
            }
        });
    });

    // Handle checkout
    const checkoutForm = document.getElementById("checkoutForm");
    checkoutForm?.addEventListener("submit", async (event) => {
        event.preventDefault();
        try {
            const response = await fetch("checkout.php", {
                method: "POST"
            });
            if (!response.ok) throw new Error(`HTTP error: ${response.status}`);
            const result = await response.json();
            if (result.success) {
                showToast("Checkout successful! Your cart has been cleared.");
                document.querySelector(".products").innerHTML = '<p class="subtitle2">Your cart is empty.</p>';
                checkoutForm.style.display = "none";
            } else {
                showToast("Error: " + result.message);
            }
        } catch (error) {
            showToast("Error during checkout: " + error.message);
        }
    });

    // Toast notification
    function showToast(message) {
        const toast = document.getElementById("toast");
        toast.textContent = message;
        toast.style.display = "block";
        setTimeout(() => {
            toast.style.display = "none";
        }, 3000);
    }
});
</script>
</body>
</html>
<?php $conn->close(); ?>