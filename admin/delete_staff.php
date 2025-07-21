<?php
include('../connection.php');

if (isset($_GET['staffId'])) {
    $staffId = intval($_GET['staffId']); // ← BETULKAN DI SINI

    $stmt = $conn->prepare("DELETE FROM staff WHERE staffId = ?");
    $stmt->bind_param("i", $staffId); // ← GUNA $staffId YANG BETUL

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: admin_staff.php?msg=delete_success");
        exit();
    } else {
        $stmt->close();
        $conn->close();
        header("Location: admin_staff.php?msg=delete_fail");
        exit();
    }
} else {
    header("Location: admin_staff.php?msg=invalid_request");
    exit();
}
?>
