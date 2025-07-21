<?php
session_start();
include('connection.php');

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

$userId = $_SESSION['user']['userId'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Orders</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #ddd;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }

    .back-btn {
      padding: 5px 10px;
      text-decoration: none;
      border: 1px solid #007bff;
      color: #007bff;
      border-radius: 4px;
    }

    .order-box {
      border: 1px solid #ccc;
      padding: 10px;
      margin-bottom: 10px;
    }

    .order-title {
      background-color: #cce5ff;
      padding: 5px;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .order-card {
      border: 1px solid #ccc;
      padding: 10px;
      display: flex;
      margin-bottom: 10px;
    }

    .order-card img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      margin-right: 10px;
    }

    .rated-card {
      border: 2px solid #28a745;
    }

    .btn-rate {
      padding: 5px 10px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      margin-top: 5px;
    }

    .no-orders {
      text-align: center;
      margin-top: 50px;
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 10;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
      background-color: #fff;
      margin: 10% auto;
      padding: 20px;
      border-radius: 8px;
      width: 300px;
    }

    .close-btn {
      float: right;
      cursor: pointer;
      font-weight: bold;
    }
  </style>
</head>
<body>

<div class="header">
  <a href="index.php">
    <img src="images/CSC264 LOGO 1.png" alt="Tapau Planet Logo" style="height: 100px;">
  </a>
  <a href="index.php" class="back-btn">← Back to Home</a>
</div>

<h2>My Orders</h2>

<?php
$sql = "SELECT o.orderId, o.orderDate, m.menuId, m.menuName, m.menuPic, m.menuPrice, om.quantity
        FROM orders o
        JOIN ordermenu om ON o.orderId = om.orderId
        JOIN menu m ON m.menuId = om.menuId
        WHERE o.userId = ?
        ORDER BY o.orderDate DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$lastOrderId = 0;
$hasOrders = false;

while ($row = $result->fetch_assoc()) {
    $hasOrders = true;
    $orderId = $row['orderId'];
    $menuId = $row['menuId'];
    $menuName = htmlspecialchars($row['menuName']);
    $menuPic = htmlspecialchars($row['menuPic']);
    $menuPrice = number_format($row['menuPrice'], 2);
    $quantity = (int)$row['quantity'];
    $orderDate = $row['orderDate'];

    $stmt2 = $conn->prepare("SELECT menuRateRating, menuRateComment FROM menu_rating WHERE userId = ? AND menuId = ?");
    $stmt2->bind_param("ii", $userId, $menuId);
    $stmt2->execute();
    $ratingResult = $stmt2->get_result();
    $ratingData = $ratingResult->fetch_assoc();

    if ($lastOrderId != $orderId) {
        echo "<div class='order-title'>Order ID: $orderId | <small>$orderDate</small></div>";
        $lastOrderId = $orderId;
    }

    echo '<div class="order-card ' . ($ratingData ? 'rated-card' : '') . '">';
    echo "<img src=\"images/$menuPic\" alt=\"$menuName\">";
    echo '<div>';
    echo "<h4>$menuName</h4>";
    echo "<p>RM $menuPrice | Qty: $quantity</p>";

    if ($ratingData) {
        echo '<span style="color:green;">⭐ ' . $ratingData['menuRateRating'] . ' / 5</span><br>';
        if (!empty($ratingData['menuRateComment'])) {
            echo '<small>"' . htmlspecialchars($ratingData['menuRateComment']) . '"</small>';
        }
    } else {
        $modalId = "rateModal" . $orderId . "_" . $menuId;
        echo "<button class='btn-rate' onclick=\"document.getElementById('$modalId').style.display='block'\">Rate this</button>";
        echo "
        <div id='$modalId' class='modal'>
          <div class='modal-content'>
            <span class='close-btn' onclick=\"document.getElementById('$modalId').style.display='none'\">&times;</span>
            <form method='POST' action='submit_rating.php'>
              <input type='hidden' name='menuId' value='$menuId'>
              <input type='hidden' name='orderId' value='$orderId'>
              <label>Rating (1 to 5)</label><br>
              <input type='number' name='rating' min='1' max='5' required><br><br>
              <label>Comment (optional)</label><br>
              <textarea name='comment' rows='3'></textarea><br><br>
              <button type='submit'>Submit</button>
            </form>
          </div>
        </div>";
    }

    echo '</div></div>';
}

if (!$hasOrders) {
    echo '<div class="no-orders">';
    echo '<h4>🛒 You have no orders yet.</h4>';
    echo '<a href="../menu.php" class="back-btn" style="margin-top: 10px; display:inline-block;">Order Now</a>';
    echo '</div>';
}

$conn->close();
?>

<script>
// Optional: close modals on outside click
window.onclick = function(event) {
  document.querySelectorAll('.modal').forEach(modal => {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });
}
</script>

</body>
</html>
