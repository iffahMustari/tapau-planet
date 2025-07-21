<?php
session_start();
include 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['user']['userId'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['item_id'])) {
    $userId = $_SESSION['user']['userId'];
    $menuId = intval($_POST['item_id']);

    // 1. Delete item from cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE userId = ? AND menuId = ?");
    $stmt->bind_param("ii", $userId, $menuId);
    $stmt->execute();
    $stmt->close();

    // 2. Check if cart is now empty
    $check = $conn->prepare("SELECT COUNT(*) AS total FROM cart WHERE userId = ?");
    $check->bind_param("i", $userId);
    $check->execute();
    $result = $check->get_result();
    $row = $result->fetch_assoc();
    $check->close();

    $conn->close();

    // 3. Redirect based on cart status
    if ($row['total'] == 0) {
        header("Location: index.php");
    } else {
        header("Location: checkout.php");
    }
    exit;
}
?>
