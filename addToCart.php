<?php
session_start();
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user']['userId'] ?? null;
    $menuId = $_POST['menuId'] ?? null;
    $cartQuantity = $_POST['cartQuantity'] ?? null;

    if (!$userId || !$menuId || !$cartQuantity) {
        echo "Missing required fields.";
        exit;
    }

    if (!is_numeric($menuId) || !is_numeric($cartQuantity)) {
        echo "Invalid data types.";
        exit;
    }

    // Check if item already exists in cart
    $check = $conn->prepare("SELECT cartQuantity FROM cart WHERE userId = ? AND menuId = ?");
    $check->bind_param("ii", $userId, $menuId);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        // Update quantity
        $update = $conn->prepare("UPDATE cart SET cartQuantity = cartQuantity + ? WHERE userId = ? AND menuId = ?");
        $update->bind_param("iii", $cartQuantity, $userId, $menuId);
        if ($update->execute()) {
            echo "🛒 Cart updated successfully!";
        } else {
            echo "❌ Update failed: " . $update->error;
        }
        $update->close();
    } else {
        // Insert new item
        $insert = $conn->prepare("INSERT INTO cart (userId, menuId, cartQuantity) VALUES (?, ?, ?)");
        $insert->bind_param("iii", $userId, $menuId, $cartQuantity);
        if ($insert->execute()) {
            echo "✅ Item added to cart!";
        } else {
            echo "❌ Insert failed: " . $insert->error;
        }
        $insert->close();
    }

    $check->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
