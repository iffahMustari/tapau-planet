<?php
ob_start(); // clear any unexpected output
session_start();
include 'connection.php';
header('Content-Type: application/json');

// Check user session
if (!isset($_SESSION['user']['userId'])) {
    ob_clean();
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);
$address = $data['address'] ?? '';
$lat = $data['lat'] ?? null;
$lng = $data['lng'] ?? null;
$userId = $_SESSION['user']['userId'];

// Get city (last part of address)
$parts = explode(",", $address);
$city = trim(end($parts));

// Use correct field names in your DB
$stmt = $conn->prepare("UPDATE users SET userAddress = ?, userCity = ? WHERE userId = ?");
$stmt->bind_param("ssi", $address, $city, $userId);

// Clear output before sending JSON
ob_clean();
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

$stmt->close();
$conn->close();
