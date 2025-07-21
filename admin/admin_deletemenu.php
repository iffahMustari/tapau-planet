<?php
include('../connection.php');

if (isset($_GET['menuId'])) {
    $menuId = intval($_GET['menuId']);

    $stmt = $conn->prepare("DELETE FROM menu WHERE menuId = ?");
    $stmt->bind_param("i", $menuId);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: admin_menu.php?msg=delete_success");
        exit();
    } else {
        $stmt->close();
        $conn->close();
        header("Location: admin_menu.php?msg=delete_fail");
        exit();
    }
} else {
    header("Location: admin_menu.php?msg=invalid_request");
    exit();
}
?>
