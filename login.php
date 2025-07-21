<?php
include 'connection.php';
session_start();

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($conn === false) {
        die("ERROR: Could not connect to the database. " . mysqli_connect_error());
    }

    $stmt = $conn->prepare("SELECT userId, userName, userEmail, userPassword, userRole FROM users WHERE userName = ?");

    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if (password_verify($password, $row['userPassword'])) {

                $_SESSION['user'] = [
                'userId' => $row['userId'],
                'userName' => $row['userName'],
                'userEmail' => $row['userEmail'], // Assuming email is stored in the users table
                'userPhone' => $row['userPhone'] ?? '',
                'userAddress'   => $row['userAddress'] ?? ''
                ];
                $_SESSION['userRole'] = $row['userRole'];

                if ($row['userRole'] === 'admin') {
                    header("Location: admin/indexadmin.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $error = "Invalid username or password";
            }
        } else {
            $error = "Invalid username or password";
        }
        $stmt->close();
    } else {
        die("ERROR: Could not prepare statement. {$conn->error}");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Login page for Takeaway Planet" />
    <meta name="author" content="Your Name" />
    <title>Login - Takeaway Planet</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="css/login.css" />
    <style>
        .password-wrapper {
            position: relative;
            width: 100%;
        }
        .password-wrapper input[type="password"],
        .password-wrapper input[type="text"] {
            width: 95%;
            padding-right: 10px;
            padding-left: 8px;
        }
        .toggle-password {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            user-select: none;
            font-size: 18px;
            color: #666;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 12px;
        }
        .success-message {
            color: green;
            text-align: center;
            margin-bottom: 12px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <a href="index.php" title="Logo">
                <!-- Logo image -->
            <img src="images/CSC264 LOGO 1.png" alt="Restaurant Logo" class="img-responsive" style="width:150px; height:150px;" />
            </a>
        </div>

        <?php if (!empty($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['logout']) && $_GET['logout'] == 1): ?>
            <div class="success-message">You have successfully logged out.</div>
        <?php endif; ?>

        <form action="login.php" method="POST" autocomplete="off">
            <h2>Login</h2>
            <input type="text" name="username" placeholder="Username" required value="<?= htmlspecialchars($username) ?>" />

            <div class="password-wrapper">
                <input type="password" id="password" name="password" placeholder="Password" required />
                <span id="togglePassword" class="toggle-password" title="Show Password">Show</span>
            </div>

            <button type="submit">Log In</button>
        </form>

        <p style="text-align:center; margin-top:16px;">
            Don't have an account?
            <a href="register.php" style="color:#e1bb47; font-weight:600;">Register here</a>
        </p>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function () {
            const isPassword = password.getAttribute('type') === 'password';
            password.setAttribute('type', isPassword ? 'text' : 'password');
            this.textContent = isPassword ? 'Hide' : 'Show';
            this.title = isPassword ? 'Hide Password' : 'Show Password';
        });
    </script>
</body>
</html>
