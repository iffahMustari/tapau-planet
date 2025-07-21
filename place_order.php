<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user'])) {
    die("Sila login dahulu.");
}

$cust_email = $_SESSION['user']['userEmail'] ?? '';
$user_id = $_SESSION['user']['userId'] ?? 0;
$order_type = $_SESSION['order_type'] ?? 'Pickup'; // GUNA pickup/delivery
$order_status = 'Pending';
$order_date = date('Y-m-d');
$admin_id = 1;
$payMethod = $_POST['payMethod'] ?? '';
$total_amount = 0.00;

// Step 1: Ambil cart
$cart = $conn->prepare("SELECT menuId, cartQuantity FROM cart WHERE userId = ?");
$cart->bind_param("i", $user_id);
$cart->execute();
$result = $cart->get_result();

while ($row = $result->fetch_assoc()) {
    $menu_id = $row['menuId'];
    $quantity = $row['cartQuantity'];

    $getPrice = $conn->prepare("SELECT menuPrice FROM menu WHERE menuId = ?");
    $getPrice->bind_param("i", $menu_id);
    $getPrice->execute();
    $priceResult = $getPrice->get_result();
    $priceRow = $priceResult->fetch_assoc();
    $menu_price = $priceRow['menuPrice'];

    $total_amount += ($menu_price * $quantity);
}

// Tambah delivery fee jika order_type = delivery
if (strtolower($order_type) === 'delivery') {
    $total_amount += 5.00;
}

// Step 2: Simpan order
$stmt = $conn->prepare("INSERT INTO orders (orderDate, orderStatus, orderType, payMethod, adminId, userId, payAmount) 
VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssiid", $order_date, $order_status, $order_type, $payMethod, $admin_id, $user_id, $total_amount);
$stmt->execute();
$orderId = $conn->insert_id;
// selepas INSERT INTO orders...
$_SESSION['last_order_id'] = $orderId;


// Step 3: Simpan ordermenu
$cart->execute();
$result = $cart->get_result();

while ($row = $result->fetch_assoc()) {
    $menu_id = $row['menuId'];
    $quantity = $row['cartQuantity'];

    // Insert ke ordermenu
    $insert = $conn->prepare("INSERT INTO ordermenu (orderId, menuId, quantity) VALUES (?, ?, ?)");
    $insert->bind_param("iii", $orderId, $menu_id, $quantity);
    $insert->execute();
}

// Step 4: Simpan ke payment (WAJIB buat walaupun COD)
$payStatus = 'Paid';
$payDate = date('Y-m-d');
$payTime = date('H:i:s');

$payment = $conn->prepare("INSERT INTO payment (payMethod, payAmount, payDate, payTime, payStatus, orderId)
    VALUES (?, ?, ?, ?, ?, ?)");
$payment->bind_param("sdsssi", $payMethod, $total_amount, $payDate, $payTime, $payStatus, $orderId);

// Step 5: Kosongkan cart
$clear = $conn->prepare("DELETE FROM cart WHERE userId = ?");
$clear->bind_param("i", $user_id);
$payment->execute();
$clear->execute();

// Step 6: Redirect ikut Pickup/Delivery
unset($_SESSION['order_type']); // clear pilihan
$_SESSION['last_order_id'] = $orderId; // Mesti SET
header("Location: receipt.php?orderId=" . $orderId); // Mesti ADA


if (strtolower($order_type) === 'pickup') {
    header("Location: receipt.php?orderId=" . $orderId);
} else {
    header("Location: payment.php?orderId=" . $orderId);
}
exit();
?>
