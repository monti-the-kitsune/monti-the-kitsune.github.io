<?php
ob_start();
session_start();
include 'data.php';
header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Unknown error occurred.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_id'])) {
    $cart_id = intval($_POST['cart_id']);
    $session_id = session_id();

    // Verify the cart item belongs to the current session
    $check = $conn->prepare("SELECT id FROM cart WHERE id = ? AND user_session_id = ?");
    $check->bind_param("is", $cart_id, $session_id);
    if ($check->execute()) {
        $result = $check->get_result();
        if ($result->num_rows > 0) {
            $delete = $conn->prepare("DELETE FROM cart WHERE id = ?");
            $delete->bind_param("i", $cart_id);
            if ($delete->execute()) {
                $response = ['success' => true, 'message' => 'Item removed from cart!'];
            } else {
                $response['message'] = 'Database error: Failed to remove item.';
            }
            $delete->close();
        } else {
            $response['message'] = 'Invalid cart item.';
        }
        $check->close();
    } else {
        $response['message'] = 'Database error: Failed to verify cart item.';
    }
} else {
    $response['message'] = 'Invalid request: Missing cart_id.';
}

$conn->close();
echo json_encode($response);
ob_end_flush();
?>