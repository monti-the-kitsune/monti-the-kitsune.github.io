<?php
ob_start();
session_start();
include 'data.php';
header('Content-Type: application/json');

// Initialize response
$response = ['success' => false, 'message' => 'Unknown error occurred.'];

// Enable error logging
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'C:/xampp/htdocs/CS squared/php_errors.log');
error_reporting(E_ALL);

// Validate request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method.';
    error_log('Checkout error: Invalid request method.');
    echo json_encode($response);
    ob_end_flush();
    exit;
}

$session_id = session_id();

// Check database connection
if ($conn->connect_error) {
    $response['message'] = 'Database connection failed: ' . $conn->connect_error;
    error_log('Checkout error: Database connection failed: ' . $conn->connect_error);
    echo json_encode($response);
    ob_end_flush();
    exit;
}

// Check tables
$tables = ['cart', 'orders'];
foreach ($tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if (!$result || $result->num_rows === 0) {
        $response['message'] = "Database error: Table '$table' does not exist.";
        error_log("Checkout error: Table '$table' does not exist.");
        echo json_encode($response);
        ob_end_flush();
        exit;
    }
}

// Fetch cart items
$cart_query = $conn->query("SELECT product_id, quantity FROM cart WHERE user_session_id = '$session_id'");
if ($cart_query === false) {
    $response['message'] = 'Database error: Failed to fetch cart - ' . $conn->error;
    error_log('Checkout error: Failed to fetch cart - ' . $conn->error);
    echo json_encode($response);
    ob_end_flush();
    exit;
}

if ($cart_query->num_rows === 0) {
    $response['message'] = 'Cart is empty.';
    error_log('Checkout error: Cart is empty.');
    echo json_encode($response);
    ob_end_flush();
    exit;
}

// Save to orders
$success = true;
while ($cart_item = $cart_query->fetch_assoc()) {
    $product_id = (int)$cart_item['product_id'];
    $quantity = (int)$cart_item['quantity'];
    $result = $conn->query("INSERT INTO orders (user_session_id, product_id, quantity) VALUES ('$session_id', $product_id, $quantity)");
    if ($result === false) {
        $response['message'] = 'Database error: Failed to save order - ' . $conn->error;
        error_log('Checkout error: Failed to save order - ' . $conn->error);
        $success = false;
        break;
    }
}

if ($success) {
    // Clear cart
    $result = $conn->query("DELETE FROM cart WHERE user_session_id = '$session_id'");
    if ($result === false) {
        $response['message'] = 'Database error: Failed to clear cart - ' . $conn->error;
        error_log('Checkout error: Failed to clear cart - ' . $conn->error);
    } else {
        $response = ['success' => true, 'message' => 'Checkout successful!'];
    }
}

$conn->close();
echo json_encode($response);
ob_end_flush();
?>