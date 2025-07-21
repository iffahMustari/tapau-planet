<?php
include 'connection.php';
session_start();

$userId = $_SESSION['user']['userId'] ?? 0;

if (!$userId) {
    echo "You must be logged in.";
    exit;
}

// Dapatkan data dari POST
$menuId = $_POST['menuId'] ?? 0;
$orderId = $_POST['orderId'] ?? 0;
$rating = $_POST['rating'] ?? 0;
$comment = $_POST['comment'] ?? null;

// Validate
if (!$menuId || !$orderId || !$rating) {
    echo "Invalid data submitted.";
    exit;
}

// Elak duplicate rating
$check = $conn->prepare("SELECT * FROM menu_rating WHERE userId = ? AND menuId = ? AND orderId = ?");
$check->bind_param("iii", $userId, $menuId, $orderId);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "You have already rated this item.";
    exit;
}

// Masukkan rating ke dalam `menu_rating`
$stmt = $conn->prepare("INSERT INTO menu_rating (orderId, menuId, userId, menuRateRating, menuRateComment, created_at) 
                        VALUES (?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("iiiis", $orderId, $menuId, $userId, $rating, $comment);

if ($stmt->execute()) {
    header("Location: userOrder.php");
    exit;
} else {
    echo "Failed to submit rating.";
}
?>
