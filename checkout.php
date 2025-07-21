<?php
session_start();
include 'connection.php';



// Check if user is logged in
if (!isset($_SESSION['user']['userId'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user']['userId'];
$orderType = $_SESSION['order_type'] ?? 'Pickup'; // Default to Pickup

// Get cart items
$stmt = $conn->prepare("SELECT m.menuId, m.menuName, m.menuPrice, c.cartQuantity 
                        FROM menu m 
                        JOIN cart c ON m.menuId = c.menuId 
                        WHERE c.userId = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$cart = [];
$total = 0.00;
$totalItems = 0;

while ($row = $result->fetch_assoc()) {
    $cart[] = [
        'id' => $row['menuId'],
        'name' => $row['menuName'],
        'price' => $row['menuPrice'],
        'qty' => $row['cartQuantity']
    ];
    $total += $row['menuPrice'] * $row['cartQuantity'];
    $totalItems += $row['cartQuantity'];
}
$stmt->close();

// Get user info
$stmt = $conn->prepare("SELECT userFName, userLName, userPhone, userAddress, userEmail FROM users WHERE userId = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$userResult = $stmt->get_result();
$user = $userResult->fetch_assoc();
$stmt->close();

$userInfo = [
    'fullname' => $user['userFName'] . ' ' . $user['userLName'],
    'email' => $user['userEmail'],
    'phone' => $user['userPhone'],
    'address' => $user['userAddress']
];

// Determine next action based on order type
$nextAction = 'payment.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout | Complete Your Order</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            background-color: #F3ECE5;
            padding: 20px;
            margin: 0;
            color: #333;
            line-height: 1.6;
        }

        .checkout-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header img {
            height: 130px;
            width: auto;
            max-width: 220px;
        }

        .back-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 22px;
            background: #5B5D6D;
            border: none;
            border-radius: 30px;
            color: #FAF8F5;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            transition: background 0.3s;
        }

        .back-btn:hover {
            background: #444653;
        }

        h2 {
            text-align: center;
            color: #5B5D6D;
            margin-bottom: 25px;
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
            background: #FAF8F5;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        .cart-table th, .cart-table td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .cart-table th {
            background-color: #EFE9C7;
            color: #5B5D6D;
        }

        .cart-table tr:hover {
            background-color: #EBDDD6;
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 6px 12px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .delete-btn:hover {
            background-color: #b02a37;
        }

        .checkout-form {
            margin-top: 40px;
            background: #FAF8F5;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(91, 93, 109, 0.1);
        }

        .checkout-form h3 {
            color: #5B5D6D;
            margin-bottom: 20px;
            font-size: 1.3rem;
        }

        .checkout-form label {
            display: block;
            margin: 15px 0 8px;
            color: #444;
            font-weight: 600;
        }

        .checkout-form input[type="text"],
        .checkout-form select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            transition: border 0.3s;
        }

        .checkout-form input[type="text"]:focus,
        .checkout-form select:focus {
            border-color: #3498db;
            outline: none;
        }

        .submit-btn {
            margin-top: 25px;
            background-color: #27ae60;
            color: white;
            border: none;
            padding: 14px;
            width: 100%;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: #219653;
        }

        .empty-cart {
            text-align: center;
            padding: 30px;
            background: #FAF8F5;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .empty-cart p {
            font-size: 1.1rem;
            margin-bottom: 20px;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .cart-table {
                font-size: 14px;
            }
            
            .cart-table th, 
            .cart-table td {
                padding: 8px 10px;
            }
            
            .checkout-form {
                padding: 20px;
            }
        }

        @media (max-width: 480px) {
            .cart-table {
                display: block;
                overflow-x: auto;
            }
            
            .header img {
                height: 100px;
            }
        }
    </style>
</head>
<body>

<div class="checkout-container">
    <div class="header">
        <img src="images/CSC264 LOGO 1.png" alt="Restaurant Logo">
        <br>
        <a href="index.php" class="back-btn">
            <i class="fa fa-arrow-left"></i> Back to Menu
        </a>
    </div>

    <h2>Your Order Summary</h2>

    <?php if (empty($cart)) : ?>
        <div class="empty-cart">
            <p><i class="fa fa-shopping-cart" style="font-size: 2rem;"></i><br><br>Your cart is empty</p>
            <p>Redirecting to menu in <span id="countdown">5</span> seconds...</p>
        </div>

        <script>
            let seconds = 5;
            const countdownElement = document.getElementById('countdown');
            const interval = setInterval(function () {
                seconds--;
                countdownElement.textContent = seconds;
                if (seconds <= 0) {
                    clearInterval(interval);
                    window.location.href = "index.php";
                }
            }, 1000);
        </script>

    <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Item</th>
                    <th>Price (RM)</th>
                    <th>Quantity</th>
                    <th>Subtotal (RM)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $index => $item): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= number_format($item['price'], 2) ?></td>
                        <td><?= $item['qty'] ?></td>
                        <td><?= number_format($item['price'] * $item['qty'], 2) ?></td>
                        <td>
                            <form method="post" action="deleteCartItem.php" style="display:inline;">
                                <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                <button type="submit" class="delete-btn">
                                    <i class="fa fa-trash"></i> Remove
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4" style="text-align: right;"><strong>Total</strong></td>
                    <td colspan="2"><strong>RM <?= number_format($total, 2) ?></strong></td>
                </tr>
            </tbody>
        </table>

        <div class="checkout-form">
            <h3><i class="fa fa-user"></i> Customer Information</h3>
            <form action="<?= $nextAction ?>" method="POST">
                <input type="hidden" name="total" value="<?= number_format($total, 2, '.', '') ?>">
                <input type="hidden" name="userId" value="<?= $userId ?>">
                
                <?php foreach ($cart as $item): ?>
                    <input type="hidden" name="menuId[]" value="<?= $item['id'] ?>">
                    <input type="hidden" name="qty[]" value="<?= $item['qty'] ?>">
                    <input type="hidden" name="price[]" value="<?= $item['price'] ?>">
                <?php endforeach; ?>

                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" 
                       value="<?= htmlspecialchars($userInfo['fullname']) ?>" required>

                <label for="email">Email</label>
                <input type="text" id="email" name="email" 
                       value="<?= htmlspecialchars($userInfo['email']) ?>" required>

                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" 
                       value="<?= htmlspecialchars($userInfo['phone']) ?>" required>

                <?php if ($orderType === 'Delivery'): ?>
                    <label for="address">Delivery Address</label>
                    <input type="text" id="address" name="address" 
                           value="<?= htmlspecialchars($userInfo['address']) ?>" required>
                <?php else: ?>
                    <input type="hidden" name="address" value="">
                    <p style="color: #5B5D6D; font-weight: 600;">
                        <i class="fa fa-info-circle"></i> You've selected Pickup order
                    </p>
                <?php endif; ?>

                <button type="submit" class="submit-btn">
                    <?= ($orderType === 'Pickup') ? 'Confirm Order' : 'Proceed to Payment' ?>
                </button>
            </form>
        </div>
    <?php endif; ?>
</div>

</body>
</html>