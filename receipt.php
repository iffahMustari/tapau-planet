<?php
session_start();
include 'connection.php';

if (!isset($conn)) {
    die("❌ Connection gagal. Cek connection.php");
}

// Ambil orderId dari GET atau SESSION
$orderId = $_GET['orderId'] ?? ($_SESSION['last_order_id'] ?? null);
if (!$orderId) {
    die("❌ Order ID missing from both GET and SESSION.");
}
$_SESSION['last_order_id'] = $orderId;

// Dapatkan data order, user dan payment
$stmt = $conn->prepare("
    SELECT o.orderId, o.orderType, u.userFName, u.userLName, u.userEmail, u.userPhone, 
           u.userAddress, u.userCity, p.payMethod, p.payAmount, p.payStatus, 
           p.payDate, p.payTime
    FROM orders o
    JOIN users u ON o.userId = u.userId
    LEFT JOIN payment p ON o.orderId = p.orderId
    WHERE o.orderId = ?
");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("❌ Order ID tidak dijumpai dalam orders/users/payment.");
}

$order = $result->fetch_assoc();

// Dapatkan item dalam order
$stmt = $conn->prepare("SELECT m.menuName, m.menuPrice, om.quantity 
                        FROM ordermenu om
                        JOIN menu m ON om.menuId = m.menuId
                        WHERE om.orderId = ?");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Kira jumlah
$total = 0;
foreach ($items as $item) {
    $total += $item['menuPrice'] * $item['quantity'];
}

// Sanitize order type
$orderTypeRaw = $order['orderType'] ?? '';
$orderType = strtolower(trim($orderTypeRaw));
$isDelivery = $orderType === 'delivery';

// Set delivery fee
$deliveryFee = $isDelivery ? 5.00 : 0.00;
$grandTotal = $total + $deliveryFee;

// Kaedah bayaran
$methodDisplay = [
    'cod' => 'Cash on Delivery (COD)',
    'card' => 'Credit/Debit Card',
    'online' => 'Online Banking'
];
$methodText = $methodDisplay[strtolower($order['payMethod'])] ?? ucfirst($order['payMethod'] ?? 'N/A');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Receipt #<?= $orderId ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: #f7f7f7;
            font-family: 'Montserrat', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .receipt-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 90vh;
            padding: 2rem 20px;
        }
        .invoice-box {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
            padding: 2.5rem;
            max-width: 480px;
            width: 100%;
            margin-top: 2rem;
        }
        h2 {
            text-align: center;
            color: #222;
            margin: 1.5rem 0 1rem 0;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #e1bb47;
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
            letter-spacing: 0.5px;
        }
        .info p {
            margin: 0.2rem 0;
            color: #444;
            font-size: 1rem;
        }
        .cart {
            margin-top: 0.5rem;
            border-radius: 8px;
            background: #faf6ee;
            padding: 1rem;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            padding: 0.4rem 0;
            border-bottom: 1px solid #eee;
            font-size: 1rem;
        }
        .cart-item:last-child {
            border-bottom: none;
        }
        .total {
            text-align: right;
            font-weight: 700;
            color: #222;
            font-size: 1.1rem;
            margin-top: 0.8rem;
        }
        .qr-code {
            text-align: center;
            margin: 1.5rem 0;
        }
        .qr-code img {
            width: 150px;
            height: 150px;
        }
        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 1.5rem;
            gap: 1rem;
        }
        button, .back-btn {
            flex: 1;
            padding: 10px 20px;
            background: linear-gradient(90deg, #e1bb47 60%, #f7d774 100%);
            border: none;
            border-radius: 24px;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            text-align: center;
        }
        button:hover, .back-btn:hover {
            background: linear-gradient(90deg, #f7d774 60%, #e1bb47 100%);
            color: #222;
        }
        @media print {
            .action-buttons { display: none; }
        }
    </style>
</head>
<body>

<div class="receipt-container">
    <div class="invoice-box">

        <!-- Header -->
        <table style="width:100%; border:none; margin-bottom: 1rem;">
            <tr>
                <td style="width:50%;">
                    <img src="images/CSC264 LOGO 1.png" alt="Company Logo" style="height: 60px;" />
                </td>
                <td style="text-align:right; font-size: 0.9rem; color:#666;">
                    Lot 15, Jalan Wangsa Delima 1A,<br />
                    Wangsa Walk Mall,<br />
                    53300 Wangsa Maju,<br />
                    Kuala Lumpur, Malaysia.<br />
                </td>
            </tr>
        </table>

        <h2>Order Receipt</h2>

        <!-- Order Info -->
        <div class="section-title">Order Information</div>
        <div class="info">
            <p><strong>Order ID:</strong> <?= $order['orderId'] ?></p>
            <p><strong>Date:</strong> <?= $order['payDate'] ? date('d/m/Y', strtotime($order['payDate'])) : '-' ?></p>
            <p><strong>Time:</strong> <?= $order['payTime'] ? date('h:i A', strtotime($order['payTime'])) : '-' ?></p>
            <p><strong>Type:</strong> <?= ucfirst($orderType) ?></p>
        </div>

        <!-- Customer Info -->
        <div class="section-title">Customer Information</div>
        <div class="info">
            <p><strong>Name:</strong> <?= htmlspecialchars($order['userFName'] . ' ' . $order['userLName']) ?></p>
            <?php if ($isDelivery): ?>
                <p><strong>Address:</strong> <?= htmlspecialchars($order['userAddress'] ?: 'N/A') ?>, <?= htmlspecialchars($order['userCity'] ?: '-') ?></p>
            <?php endif; ?>
            <p><strong>Email:</strong> <?= htmlspecialchars($order['userEmail']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($order['userPhone']) ?></p>
        </div>

        <!-- Payment Info -->
        <div class="section-title">Payment Details</div>
        <div class="info">
            <p><strong>Method:</strong> <?= htmlspecialchars($methodText) ?></p>
            <p><strong>Amount Paid:</strong> RM <?= number_format($order['payAmount'] ?? $grandTotal, 2) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($order['payStatus'] ?? '-') ?></p>
        </div>

        <!-- Order Items -->
        <div class="section-title">Order Items</div>
        <div class="cart">
            <?php foreach ($items as $item): ?>
                <div class="cart-item">
                    <span><?= htmlspecialchars($item['menuName']) ?> x<?= $item['quantity'] ?></span>
                    <span>RM <?= number_format($item['menuPrice'] * $item['quantity'], 2) ?></span>
                </div>
            <?php endforeach; ?>

            <?php if ($isDelivery): ?>
                <div class="cart-item">
                    <span>Delivery Fee</span>
                    <span>RM <?= number_format($deliveryFee, 2) ?></span>
                </div>
            <?php endif; ?>

            <div class="total">Total: RM <?= number_format($grandTotal, 2) ?></div>
        </div>

        <!-- QR Code -->
        <div class="qr-code">
            <p><strong>Scan to view receipt:</strong></p>
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= urlencode("http://{$_SERVER['HTTP_HOST']}/receipt.php?orderId=$orderId") ?>" alt="QR Code">
        </div>

        <!-- Buttons -->
        <div class="action-buttons">
            <button onclick="downloadPDF()">Download Receipt</button>
            <a href="index.php" class="back-btn">Back to Home</a>
        </div>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function downloadPDF() {
    const element = document.querySelector(".invoice-box");
    html2pdf().set({
        margin: 0.3,
        filename: 'receipt_<?= $orderId ?>.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 4 },
        jsPDF: { unit: 'mm', format: 'letter', orientation: 'portrait' }
    }).from(element).save();
}
</script>

</body>
</html>
