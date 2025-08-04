<?php
ob_start(); // Start output buffering to prevent stray output
session_start();
include 'data.php'; // Includes database connection

header('Content-Type: application/json'); // Set JSON response type

// Initialize response array
$response = ['success' => false, 'message' => 'Unknown error occurred.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $session_id = session_id();

    // Validate product_id exists in products table
    $check_product = $conn->prepare("SELECT id FROM products WHERE id = ?");
    $check_product->bind_param("i", $product_id);
    if (!$check_product->execute()) {
        $response['message'] = 'Database error: Failed to verify product.';
        echo json_encode($response);
        $check_product->close();
        ob_end_flush();
        exit;
    }
    $product_result = $check_product->get_result();
    if ($product_result->num_rows === 0) {
        $response['message'] = 'Invalid product ID.';
        echo json_encode($response);
        $check_product->close();
        ob_end_flush();
        exit;
    }
    $check_product->close();

    // Check if the product already exists in cart
    $check = $conn->prepare("SELECT id, quantity FROM cart WHERE user_session_id = ? AND product_id = ?");
    $check->bind_param("si", $session_id, $product_id);
    if (!$check->execute()) {
        $response['message'] = 'Database error: Failed to check cart.';
        echo json_encode($response);
        $check->close();
        ob_end_flush();
        exit;
    }
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // Update quantity if already exists
        $row = $result->fetch_assoc();
        $new_qty = $row['quantity'] + $quantity;

        $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $update->bind_param("ii", $new_qty, $row['id']);
        if ($update->execute()) {
            $response = ['success' => true, 'message' => 'Item added to cart!'];
        } else {
            $response['message'] = 'Database error: Failed to update cart.';
        }
        $update->close();
    } else {
        // Insert new row
        $insert = $conn->prepare("INSERT INTO cart (user_session_id, product_id, quantity) VALUES (?, ?, ?)");
        $insert->bind_param("sii", $session_id, $product_id, $quantity);
        if ($insert->execute()) {
            $response = ['success' => true, 'message' => 'Item added to cart!'];
        } else {
            $response['message'] = 'Database error: Failed to add item to cart.';
        }
        $insert->close();
    }
    $check->close();
} else {
    $response['message'] = 'Invalid request: Missing product_id.';
}

$conn->close();
echo json_encode($response);
ob_end_flush(); // Send output and clear buffer
?>