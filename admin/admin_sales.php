<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "tapauplanet");
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// 1. Monthly Orders
$orders = [];
for ($i = 1; $i <= 12; $i++) {
    $sql = "SELECT COUNT(*) as count FROM orders WHERE MONTH(orderDate) = $i";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $orders[] = (int)($row['count'] ?? 0);
}

// 2. Monthly Sales
$sales = [];
for ($i = 1; $i <= 12; $i++) {
    $sql = "SELECT SUM(payAmount) as total FROM payment WHERE MONTH(payDate) = $i";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $sales[] = (float)($row['total'] ?? 0);
}

// 3. Order Method – HANYA Delivery dan Pickup
$orderMethods = ['labels' => [], 'values' => []];
$sqlMethod = "
    SELECT 
        CASE 
            WHEN LOWER(orderType) IN ('pickup', 'pick up', 'pick-up') THEN 'Pickup'
            WHEN LOWER(orderType) = 'delivery' THEN 'Delivery'
        END AS method,
        COUNT(*) as count 
    FROM orders 
    WHERE LOWER(orderType) IN ('pickup', 'pick up', 'pick-up', 'delivery')
    GROUP BY method
";
$resultMethod = $conn->query($sqlMethod);
while ($row = $resultMethod->fetch_assoc()) {
    if ($row['method']) {
        $orderMethods['labels'][] = $row['method'];
        $orderMethods['values'][] = (int)$row['count'];
    }
}

// 4. Payment Method – HANYA Cash on Delivery dan Card
$paymentMethods = ['labels' => [], 'values' => []];
$sqlPayment = "SELECT LOWER(payMethod) as method, COUNT(*) as count FROM payment GROUP BY method";
$resultPayment = $conn->query($sqlPayment);
$tempPay = [];

while ($row = $resultPayment->fetch_assoc()) {
    $method = $row['method'];
    $count = (int)$row['count'];

    if (in_array($method, ['cod', 'cash'])) {
        $tempPay['Cash on Delivery'] = ($tempPay['Cash on Delivery'] ?? 0) + $count;
    } elseif (in_array($method, ['card'])) {
        $tempPay['Card'] = ($tempPay['Card'] ?? 0) + $count;
    }
}

foreach ($tempPay as $label => $val) {
    $paymentMethods['labels'][] = $label;
    $paymentMethods['values'][] = $val;
}

$conn->close();

// 5. Output JSON
echo json_encode([
    'orders' => [
        'labels' => ["January", "February", "March", "April", "May", "June",
                     "July", "August", "September", "October", "November", "December"],
        'values' => $orders
    ],
    'sales' => [
        'labels' => ["January", "February", "March", "April", "May", "June",
                     "July", "August", "September", "October", "November", "December"],
        'values' => $sales
    ],
    'orderMethods' => $orderMethods,
    'paymentMethods' => $paymentMethods
]);
?>
