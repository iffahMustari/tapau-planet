<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tapau Planet</title>
    <link rel="stylesheet" href="css/header.css">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<header class="navbar">
    <div class="container" style="display: flex; align-items: center; justify-content: space-between;">
        <div class="logo">
            <a href="index.php" title="Tapau Planet Home">
                <img src="images/CSC264 LOGO 1.png" alt="Restaurant Logo" class="img-responsive">
            </a>
        </div>
        <nav style="flex:1; display: flex; justify-content: flex-start; align-items: center;">

        <div class="nav-links" style="flex:1; display: flex; justify-content: center;"> 
            <ul style="display: flex; gap: 30px; list-style: none; margin:0; padding: 0; align-items: center;">
                <li><a href="index.php">Home</a></li>
                <li><a href="#menu">Foods</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="#footer">Contact</a></li>
            </ul>
        </div>

        <div class="nav-icon" style="margin-right: 40px;">
            <ul style="display: flex; gap: 1px; list-style: none; margin: 0; padding: 0; align-items: center;">
                <li>
                    <?php if (isset($_SESSION['user'])): ?>
                        <a href="profile.php">
                            <button type="button" style="background:none;border:none;padding:0;cursor:pointer;">
                                <img src="images/icon.png" alt="" style="width:24px;height:24px;vertical-align:middle;">
                                welcome
                               <?= isset($_SESSION['user']['userName']) ? htmlspecialchars($_SESSION['user']['userName']) : '' ?>
                            </button>
                        </a>
                    <?php else: ?>
                        <a href="login.php">
                            <button type="button" style="background:none;border:none;padding:0;cursor:pointer;">
                                <img src="images/icon.png" alt="" style="width:24px;height:24px;vertical-align:middle;">
                                login
                            </button>
                        </a>
                    <?php endif; ?>
                </li>
                <li>
                    <?php
                    include_once 'connection.php';
                    $orderId = null;
                    if (isset($_SESSION['user'])) {
                        $userEmail = $_SESSION['user']['userEmail'] ?? '';
                        $result = $conn->query("SELECT orderId FROM orders WHERE userId = (SELECT userId FROM users WHERE userEmail = '" . $conn->real_escape_string($userEmail) . "') ORDER BY orderId DESC LIMIT 1");
                        if ($result && $row = $result->fetch_assoc()) {
                            $orderId = $row['orderId'];
                        }
                    }
                    ?>
                    <a href="track.php<?= $orderId ? '?orderId=' . $orderId : '' ?>">
                        <button type="button" style="background:none;border:none;padding:0;cursor:pointer;">
                            <i class="fas fa-map-marker-alt" style="font-size:24px;vertical-align:middle;"></i>
                            track
                        </button>
                    </a>
                </li>
                <li>
                    <a href="checkout.php">
                        <button type="button" style="background:none;border:none;padding:0;cursor:pointer;">
                            <img src="images/cart.png" alt="" style="width:24px;height:24px;vertical-align:middle;">
                            cart
                        </button>
                    </a>
                </li>
                <li>
                    <a href="userOrder.php">
                        <button type="button" style="background:none;border:none;padding:0;cursor:pointer;">
                            <i class="fas fa-history" style="font-size:24px;vertical-align:middle;"></i>
                            History
                        </button>
                    </a>
                </li>
            </ul>
        </div>
        </nav>
    </div>
</header>
</body>
</html>
