<?php
include '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $orderId = intval($_POST['orderId']);
    $newStatus = mysqli_real_escape_string($conn, $_POST['orderStatus']);

    $sql = "UPDATE orders SET orderStatus='$newStatus' WHERE orderId=$orderId";

    if (mysqli_query($conn, $sql)) {
        header("Location: admin_order.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>
