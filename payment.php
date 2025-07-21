<?php
session_start();
include 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['user']['userId'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user']['userId'];
$orderType = $_SESSION['order_type'] ?? 'Pickup'; // Default is Pickup

// Get cart items and calculate total
$cartItems = [];
$totalItems = 0;
$totalPrice = 0.00;

$sql = "SELECT c.cartQuantity, m.menuName, m.menuPrice 
        FROM cart c 
        JOIN menu m ON c.menuId = m.menuId 
        WHERE c.userId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $itemTotal = $row['menuPrice'] * $row['cartQuantity'];
    $cartItems[] = [
        'name' => $row['menuName'],
        'qty' => $row['cartQuantity'],
        'total' => $itemTotal
    ];
    $totalItems += $row['cartQuantity'];
    $totalPrice += $itemTotal;
}

// Get user info from DB
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

$deliveryFee = ($orderType === 'Delivery') ? 5.00 : 0.00;
$finalTotal = $totalPrice + $deliveryFee;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment | Complete Your Order</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #e1bb47;
            --light: #f8f9fa;
            --dark: #343a40;
            --success: #28a745;
            --danger: #dc3545;
            --border-radius: 8px;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            color: var(--dark);
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .payment-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .payment-header h1 {
            color: var(--primary);
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .payment-header p {
            color: #6c757d;
        }

        .payment-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        @media (max-width: 992px) {
            .payment-grid {
                grid-template-columns: 1fr;
            }
        }

        .payment-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 30px;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 1.25rem;
            color: var(--primary);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: var(--secondary);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--primary);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: var(--transition);
        }

        .form-control[readonly] {
            background-color: #f8f9fa;
            cursor: not-allowed;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }

        .col {
            flex: 1;
            padding: 0 10px;
        }

        .btn {
            display: inline-block;
            background-color: var(--success);
            color: white;
            border: none;
            padding: 14px 20px;
            width: 100%;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        .payment-method {
            margin-bottom: 25px;
        }

        .card-details {
            background: #f9f9f9;
            padding: 20px;
            border-radius: var(--border-radius);
            margin-top: 15px;
            transition: var(--transition);
        }

        .disabled-section {
            opacity: 0.6;
            pointer-events: none;
            background-color: #f5f5f5;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item-name {
            font-weight: 600;
            color: var(--primary);
        }

        .cart-item-qty {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .price {
            font-weight: 600;
            color: var(--primary);
        }

        .total-price {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary);
        }

        .divider {
            height: 1px;
            background-color: #eee;
            margin: 20px 0;
        }

        .accepted-cards {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .card-icon {
            color: #6c757d;
            font-size: 1.5rem;
        }

        .order-type-badge {
            background-color: var(--secondary);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-left: 10px;
        }

        @media (max-width: 768px) {
            .col {
                flex: 100%;
                margin-bottom: 15px;
            }

            .payment-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="payment-header">
            <h1>Complete Your Payment</h1>
            <p>Review your order and enter payment details</p>
        </header>

        <div class="payment-grid">
            <div class="payment-card">
                <h2 class="section-title"><i class="fas fa-user-circle"></i> Billing Information</h2>
                <form action="payment_process.php" method="POST">
                    <input type="hidden" name="payAmount" value="<?= number_format($finalTotal, 2, '.', '') ?>">
                    <input type="hidden" name="userId" value="<?= $userId ?>">
                    <input type="hidden" name="orderType" value="<?= $orderType ?>">

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="fname"><i class="fas fa-user"></i> Full Name</label>
                                <input type="text" id="fname" name="fullName" class="form-control" 
                                       value="<?= htmlspecialchars($userInfo['fullname']) ?>" readonly>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                                <input type="text" id="email" name="email" class="form-control" 
                                       value="<?= htmlspecialchars($userInfo['email']) ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="phone"><i class="fas fa-phone"></i> Phone Number</label>
                                <input type="text" id="phone" name="phone" class="form-control" 
                                       value="<?= htmlspecialchars($userInfo['phone']) ?>" readonly>
                            </div>
                        </div>
                        <?php if ($orderType === 'Delivery'): ?>
                        <div class="col">
                            <div class="form-group">
                                <label for="address"><i class="fas fa-map-marker-alt"></i> Delivery Address</label>
                                <input type="text" id="address" name="address" class="form-control" 
                                       value="<?= htmlspecialchars($userInfo['address']) ?>" readonly>
                            </div>
                        </div>
                        <?php else: ?>
                            <input type="hidden" name="address" value="">
                        <?php endif; ?>
                    </div>

                    <div class="payment-method">
                        <h2 class="section-title"><i class="fas fa-credit-card"></i> Payment Method</h2>
                        <div class="form-group">
                            <label for="payMethod">Select Payment Method</label>
                            <select id="payMethod" name="payMethod" class="form-control" required>
                                <option value="card">Credit/Debit Card</option>
                                <option value="cod">Cash on Delivery (COD)</option>
                            </select>
                        </div>
                    </div>

                    <div id="card-details" class="card-details">
                        <p><i class="fas fa-check-circle"></i> We accept the following cards:</p>
                        <div class="accepted-cards">
                            <i class="fab fa-cc-visa card-icon"></i>
                            <i class="fab fa-cc-mastercard card-icon"></i>
                            <i class="fab fa-cc-amex card-icon"></i>
                        </div>
                        <div class="form-group">
                            <label for="cname">Name on Card</label>
                            <input type="text" id="cname" name="cardname" class="form-control" placeholder="John More Doe" required>
                        </div>
                        <div class="form-group">
                            <label for="ccnum">Credit Card Number</label>
                            <input type="text" id="ccnum" name="cardnumber" class="form-control" placeholder="1111 2222 3333 4444" required>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="expmonth">Expiration Month</label>
                                    <input type="text" id="expmonth" name="expmonth" class="form-control" placeholder="MM" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="expyear">Expiration Year</label>
                                    <input type="text" id="expyear" name="expyear" class="form-control" placeholder="YYYY" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="cvv">CVV</label>
                                    <input type="text" id="cvv" name="cvv" class="form-control" placeholder="123" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn"><i class="fas fa-lock"></i> Complete Payment</button>
                </form>
            </div>

            <div class="payment-card">
                <h2 class="section-title">
                    <i class="fas fa-shopping-cart"></i> Order Summary
                    <span class="price"><?= $totalItems ?> items</span>
                    <span class="order-type-badge"><?= $orderType ?></span>
                </h2>
                <?php foreach ($cartItems as $item): ?>
                    <div class="cart-item">
                        <div>
                            <span class="cart-item-name"><?= htmlspecialchars($item['name']) ?></span>
                            <span class="cart-item-qty">x<?= $item['qty'] ?></span>
                        </div>
                        <span class="price">RM<?= number_format($item['total'], 2) ?></span>
                    </div>
                <?php endforeach; ?>
                <div class="divider"></div>
                <div class="cart-item">
                    <span>Subtotal</span>
                    <span class="price">RM<?= number_format($totalPrice, 2) ?></span>
                </div>
                <div class="cart-item">
                    <span><?= ($orderType === 'Delivery') ? 'Delivery Fee' : 'Pickup' ?></span>
                    <span class="price">RM<?= number_format($deliveryFee, 2) ?></span>
                </div>
                <div class="divider"></div>
                <div class="cart-item">
                    <span class="total-price">Total</span>
                    <span class="total-price">RM<?= number_format($finalTotal, 2) ?></span>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('payMethod').addEventListener('change', function () {
            const isCOD = this.value === 'cod';
            const cardDetails = document.getElementById('card-details');
            const cardInputs = cardDetails.querySelectorAll('input');

            if (isCOD) {
                cardDetails.classList.add('disabled-section');
                cardInputs.forEach(input => input.required = false);
            } else {
                cardDetails.classList.remove('disabled-section');
                cardInputs.forEach(input => input.required = true);
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const payMethod = document.getElementById('payMethod');
            if (payMethod.value === 'cod') {
                document.getElementById('card-details').classList.add('disabled-section');
                document.querySelectorAll('#card-details input').forEach(input => {
                    input.required = false;
                });
            }
        });
    </script>
</body>
</html>