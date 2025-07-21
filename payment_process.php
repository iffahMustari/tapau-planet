<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user']['userId'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user']['userId'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = $_POST['payMethod'] ?? null;
    $amount = $_POST['payAmount'] ?? null;
    $orderType = $_POST['orderType'] ?? 'Pickup'; // fallback to Pickup if not set

    if (!$method || !$amount) {
        die("Missing payment data.");
    }

    try {
        $conn->begin_transaction();

        // ✅ Fix: Use actual orderType from POST
        $stmt = $conn->prepare("INSERT INTO orders (userId, orderDate, orderType) VALUES (?, NOW(), ?)");
        $stmt->bind_param("is", $userId, $orderType);
        $stmt->execute();
        $orderId = $conn->insert_id;

        // Transfer cart to ordermenu
        $stmt = $conn->prepare("SELECT menuId, cartQuantity FROM cart WHERE userId = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($item = $result->fetch_assoc()) {
            $ins = $conn->prepare("INSERT INTO ordermenu (orderId, menuId, quantity) VALUES (?, ?, ?)");
            $ins->bind_param("iii", $orderId, $item['menuId'], $item['cartQuantity']);
            $ins->execute();
        }

        // Insert payment record
        $status = ($method === 'cod') ? 'Pending' : 'Paid';
        $date = date("Y-m-d");
        $time = date("H:i:s");
        $stmt = $conn->prepare("INSERT INTO payment (orderId, payMethod, payAmount, payStatus, payDate, payTime) 
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isdsss", $orderId, $method, $amount, $status, $date, $time);
        $stmt->execute();

        // Clear cart
        $stmt = $conn->prepare("DELETE FROM cart WHERE userId = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $conn->commit();

        $_SESSION['last_order_id'] = $orderId; // store for receipt fallback
        header("Location: receipt.php?orderId=$orderId");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        die("❌ Error: " . $e->getMessage());
    }
}
?>
