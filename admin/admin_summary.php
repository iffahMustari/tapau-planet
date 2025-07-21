<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "tapauplanet");

$response = [];

$response['totalOrders'] = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'] ?? 0;
$response['totalSales'] = $conn->query("SELECT SUM(payAmount) AS total FROM payment")->fetch_assoc()['total'] ?? 0;
$response['activeMenu'] = $conn->query("SELECT COUNT(*) AS total FROM menu")->fetch_assoc()['total'] ?? 0;
$response['activeUsers'] = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'] ?? 0;
$response['totalStaff'] = $conn->query("SELECT COUNT(*) AS total FROM staff")->fetch_assoc()['total'] ?? 0;
$response['totalFeedback'] = $conn->query("SELECT COUNT(*) AS total FROM menu_rating")->fetch_assoc()['total'] ?? 0;

echo json_encode($response);
?>
