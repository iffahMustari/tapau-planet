<?php
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = intval($_POST['orderId']);
    $orderStatus = mysqli_real_escape_string($conn, $_POST['orderStatus']);

    // Update query
    $sql = "UPDATE orders SET orderStatus='$orderStatus' WHERE orderId=$orderId";
    
    if (mysqli_query($conn, $sql)) {
        // Redirect back to admin_order.php after update
        header("Location: admin_order.php?status=updated");
        exit();
    } else {
        echo "Error updating order: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}
