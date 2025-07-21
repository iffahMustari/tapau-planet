<?php
session_start();
include 'connection.php'; // connect DB


// Check if user is logged in
if (!isset($_SESSION['user']['userId'])) {
    header("Location: login.php");
    exit;
}
$orderId = intval($_GET['orderId']);

// Get order data based on orderId
$stmt = $conn->prepare("SELECT * FROM orders WHERE orderId = ?");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows == 0) {
    die("Order not found.");
}

$order = $result->fetch_assoc();

// Get order items (join ordermenu and menu)
$items = [];
$item_sql = "SELECT m.menuName, om.quantity 
             FROM ordermenu om 
             JOIN menu m ON om.menuId = m.menuId 
             WHERE om.orderId = ?";
$stmt = $conn->prepare($item_sql);
$stmt->bind_param("i", $orderId);
$stmt->execute();
$item_result = $stmt->get_result();
while ($row = $item_result->fetch_assoc()) {
    $items[] = $row;
}

// Get total price (from payment table)
$total_price = 0.00;
$pay_sql = "SELECT payAmount FROM payment WHERE orderId = ? LIMIT 1";
$stmt = $conn->prepare($pay_sql);
$stmt->bind_param("i", $orderId);
$stmt->execute();
$pay_result = $stmt->get_result();
if ($pay = $pay_result->fetch_assoc()) {
    $total_price = $pay['payAmount'];
}

// Status list for progress bar calculation
$statuses = ['Pending', 'Confirmed', 'Processing', 'Delivery', 'Completed'];
$currentStatusIndex = array_search($order['orderStatus'], $statuses);
$progressPercent = ($currentStatusIndex !== false) ? (($currentStatusIndex + 1) / count($statuses)) * 100 : 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Track Order</title>
 <head>
    <title>Track Order</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/track.css">
    <script>
        setTimeout(function() {
            location.reload();
        }, 10000); // refresh every 10 seconds
    </script>
</head>
</head>
<body>

<div class="track-box">
    <div class="track-logo" style="text-align: center;">
        <img src="images/CSC264 LOGO 1.png" alt="CSC264 Logo" class="track-logo-img">
    </div>
    <h2 style="text-align: center;">Tracking Order #<?= htmlspecialchars($order['orderId']) ?></h2>

    <div class="info">
        <p><strong>Order Items:</strong></p>
        <?php if (count($items) > 0): ?>
            <ul>
            <?php foreach ($items as $item): ?>
                <li><?= htmlspecialchars($item['menuName']) ?> <span class="track-qty">x<?= $item['quantity'] ?></span></li>
            <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No items found for this order.</p>
        <?php endif; ?>
        <p><strong>Order Type:</strong> <span class="track-type"><?= htmlspecialchars($order['orderType']) ?></span></p>
        <p><strong>Total:</strong> <span class="track-total">RM<?= number_format($total_price, 2) ?></span></p>
        <p><strong>Current Status:</strong> <span class="track-status"><?= htmlspecialchars($order['orderStatus']) ?></span></p>
    </div>

    <div class="progress-container">
        <div class="progress-bar" style="width: <?= $progressPercent ?>%;">
            <?= htmlspecialchars($order['orderStatus']) ?>
        </div>
    </div>
    <div class="progress-steps">
        <?php foreach ($statuses as $i => $status): ?>
            <div class="progress-step<?= $i == $currentStatusIndex ? ' active' : '' ?>">
                <?= htmlspecialchars($status) ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="status-text">
        <?= ($order['orderStatus'] == "Completed") ? "Your order has been completed. Thank you!" : "Your order is in progress..." ?>
    </div>

    <div class="reload-msg">Auto-refresh every 10 seconds...</div>
    <div class="track-back-btn">
        <a href="index.php" class="back-btn">Back to Homepage</a>
    </div>
</div>

</body>
</html>
