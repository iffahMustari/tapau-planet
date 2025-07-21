<?php
session_start();
include("connection.php");

if (!isset($_SESSION['user']['userName'])) {
    header("Location: login.php");
    exit();
}

$currentUsername = $_SESSION['user']['userName'];

$query = "SELECT * FROM users WHERE userName = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $currentUsername);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

if ($userData && $_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = $_POST['username'];
    $newEmail = $_POST['email'];

    $update = "UPDATE users SET userName = ?, userEmail = ? WHERE userName = ?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("sss", $newUsername, $newEmail, $currentUsername);

    if ($stmt->execute()) {
        $_SESSION['user']['userName'] = $newUsername;
        $_SESSION['success_message'] = "Profile updated successfully!";
        header("Location: profile.php");
        exit();
    } else {
        $error_message = "Failed to update profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Profile</title>
    <link rel="stylesheet" href="css/profile.css" />
</head>
<body>
<div class="login-container">
    <div class="logo">
        <img src="images/CSC264 LOGO 1.png" alt="Restaurant Logo" style="width:150px; height:150px;" />
    </div>
    <h2>Your Profile</h2>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="success-message"><?= $_SESSION['success_message']; ?></div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        <div class="error-message"><?= $error_message; ?></div>
    <?php endif; ?>

    <form method="post">
        <?php if ($userData): ?>
            <input type="text" name="username" value="<?= htmlspecialchars($userData['userName']) ?>" required>
            <input type="email" name="email" value="<?= htmlspecialchars($userData['userEmail'] ?? '') ?>" required>
            <button type="submit">Update Profile</button>
        <?php else: ?>
            <div class="error-message">⚠️ User data not found. Please <a href="login.php">log in again</a>.</div>
        <?php endif; ?>
    </form>

    <button type="button" onclick="window.location.href='index.php'" style="margin-top: 10px;">Back to Home</button>

    <form action="logout.php" method="post" style="margin-top: 10px;">
        <button type="submit" class="logout-btn">Logout</button>
    </form>
</div>
</body>
</html>
