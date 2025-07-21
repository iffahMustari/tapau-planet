<?php
// Include the database connection file
include 'connection.php';
session_start();

$error = '';
$email = '';
$username = '';
$password = '';
$confirm_password = '';
$first_name = '';
$last_name = '';
$phone = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $username = htmlspecialchars(trim($_POST['username'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $first_name = htmlspecialchars(trim($_POST['first_name'] ?? ''));
    $last_name = htmlspecialchars(trim($_POST['last_name'] ?? ''));
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));

    // Validate input
    if (
        empty($email) || empty($username) || empty($password) || empty($confirm_password) ||
        empty($first_name) || empty($last_name) || empty($phone)
    ) {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
        $email = '';
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (!preg_match("/^01[0-9]-\d{7,8}$/", $phone)) {
        $error = "Format nombor telefon tak sah. Contoh: 017-99556644";
        $phone = '';
    } else {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT userId FROM users WHERE userName = ? OR userEmail = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username or email already exists.";
            $username = '';
            $email = '';
        } else {
            // Insert new user
            $stmt->close();
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
           $stmt = $conn->prepare("INSERT INTO users (userEmail, userName, userPassword, userRole, userFName, userLName, userPhone) VALUES (?, ?, ?, 'user', ?, ?, ?)");
            $stmt->bind_param("ssssss", $email, $username, $hashed_password, $first_name, $last_name, $phone);

            if ($stmt->execute()) {
                $user_id = $conn->insert_id;
                $_SESSION['user'] = [
                    'userId' => $user_id,
                    'userName' => $username
                ];
                header("Location: index.php");
                exit();
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Register page for Takeaway Planet" />
    <meta name="author" content="Your Name" />
    <title>Register - Takeaway Planet</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/register.css" />
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <a href="index.php" class="logo-link" style="display: flex; justify-content: center; align-items: center;">
                <img src="images/CSC264 LOGO 1.png" alt="Restaurant Logo" class="img-responsive" style="width:150px; height:150px; display:block; margin:auto;" />
            </a>
        </div>
        <h2 style="text-align: center; margin-top: 150px;">Register</h2>
        <?php if (!empty($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form action="register.php" method="POST" autocomplete="off">
            <div class="input-row">
                <input type="text" name="first_name" placeholder="First Name" required value="<?= htmlspecialchars($first_name) ?>" />
                <input type="text" name="last_name" placeholder="Last Name" required value="<?= htmlspecialchars($last_name) ?>" />
            </div>
            <div class="input-row">
                <input type="text" name="username" placeholder="Username" required value="<?= htmlspecialchars($username) ?>" />
                <input type="text" name="phone" placeholder="Phone" required value="<?= htmlspecialchars($phone) ?>" />
            </div>
            <input type="email" name="email" placeholder="E-mel" required value="<?= htmlspecialchars($email) ?>" />
            <input type="password" name="password" placeholder="Password" required />
            <input type="password" name="confirm_password" placeholder="Confirm Password" required />

            <p style="text-align: center; font-size: 14px;">
                Already have an account?
                <a href="login.php" style="color:#e1bb47; font-weight:600; font-size: 14px;" class="register-btn">Login</a>
            </p>
            <p style="text-align: center; font-size: 14px;">
                By registering, you agree to our
                <a href="#" style="color:#e1bb47; font-weight:600; font-size: 14px;">Terms of Service</a>.
            </p>
            <p style="text-align: center; font-size: 14px;">
                and
                <a href="#" style="color:#e1bb47; font-weight:600; font-size: 14px;">Privacy Policy</a>.
            </p>
            <p style="text-align: center; font-size: 14px;">We will never share your information with third parties.</p>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
