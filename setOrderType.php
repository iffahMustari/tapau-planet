<?php
session_start();

if (isset($_POST['orderType'])) {
    $_SESSION['order_type'] = $_POST['orderType'];  // e.g., "Pickup" atau "Delivery"
    error_log("Order type set to: " . $_SESSION['order_type']);
}
?>
