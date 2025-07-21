<?php
include 'connection.php';
session_start();


// Check if user is logged in
if (!isset($_SESSION['user']['userId'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user']['userId'] ?? 0;



// Fetch all orders by this user
$orders = $conn->query("SELECT * FROM orders WHERE userId = $userId ORDER BY orderDate DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Orders & Ratings</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #F3ECE5; /* Soft Cream */
      padding: 30px;
      margin: 0;
    }

    .order-box {
      background: #FAF8F5; /* Off-white */
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 30px;
      box-shadow: 0 2px 10px rgba(91, 93, 109, 0.1); /* Slate blue accent */
    }

    .order-box h4 {
      margin: 0 0 15px;
      color: #5B5D6D; /* Grid Accent Blue */
    }

    .menu-header {
      text-align: center;
      margin-bottom: 30px;
    }

    .menu-header img {
      height: 130px;
      width: auto;
      max-width: 220px;
    }

    .menu-header .back-btn {
      display: inline-block;
      margin-top: 15px;
      padding: 10px 22px;
      background: #5B5D6D; /* Slate Blue */
      border: none;
      border-radius: 30px;
      color: #FAF8F5; /* Off-white */
      font-weight: 600;
      font-size: 1rem;
      text-decoration: none;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
      transition: background 0.3s;
    }

    .menu-header .back-btn:hover {
      background: #444653;
    }

    .rated {
      color: #B7C2AF; /* Sage Green */
      font-weight: bold;
    }

    select {
      padding: 6px 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
      background: #EBDDD6; /* Soft Mauve */
    }

    input[type="submit"] {
      background: #C7D0D5; /* Dusty Blue */
      color: #333;
      border: none;
      padding: 6px 12px;
      cursor: pointer;
      border-radius: 5px;
      margin-left: 5px;
    }

    input[type="submit"]:hover {
      background: #B7C2AF; /* Sage Green */
    }

    .item-card {
      width: 200px;
      background: #EFE9C7; /* Buttercream */
      padding: 10px;
      border-radius: 6px;
      box-shadow: 0 0 5px rgba(0,0,0,0.05);
    }

    .item-img {
      width: 100%;
      height: 130px;
      object-fit: cover;
      border-radius: 4px;
    }

    .item-name {
      margin: 8px 0;
      font-weight: bold;
      color: #5B5D6D;
    }
  </style>
</head>
<body>

<div class="menu-header">
  <img src="images/CSC264 LOGO 1.png" alt="CSC264 Logo">
  <br>
  <a href="index.php" class="back-btn">⬅ Back to Homepage</a>
</div>

<h2 style="text-align:center; color:#5B5D6D; margin-bottom: 30px;">Your Orders & Ratings</h2>

<?php while ($order = $orders->fetch_assoc()): ?>
  <div class="order-box">
    <h4>Order #<?= $order['orderId'] ?> | <?= $order['orderDate'] ?> | <?= ucfirst($order['orderStatus']) ?></h4>

    <?php
    $orderId = $order['orderId'];
    $items = $conn->query("SELECT om.menuId, m.menuName, m.menuPic 
                           FROM ordermenu om 
                           JOIN menu m ON om.menuId = m.menuId 
                           WHERE om.orderId = $orderId");

    if (!$items) {
        echo "<p style='color:red;'>SQL Error: " . $conn->error . "</p>";
        continue;
    }
    ?>

    <div style="display: flex; flex-wrap: wrap; gap: 20px;">
    <?php while ($item = $items->fetch_assoc()):
      $menuId = $item['menuId'];
      $menuName = htmlspecialchars($item['menuName']);
      $menuPic = htmlspecialchars($item['menuPic']);

      $rate = $conn->query("SELECT menuRateRating FROM menu_rating WHERE userId=$userId AND menuId=$menuId AND orderId=$orderId");
      $hasRated = $rate->fetch_assoc();
    ?>
      <div class="item-card">
        <img src="images/<?= $menuPic ?>" alt="<?= $menuName ?>" class="item-img">
        <p class="item-name"><?= $menuName ?></p>

        <?php if ($hasRated): ?>
          <span class="rated">Rated: <?= str_repeat("⭐", $hasRated['menuRateRating']) ?></span>
        <?php else: ?>
          <form method="POST" action="submit_rating.php" style="margin-top: 5px;">
            <input type="hidden" name="menuId" value="<?= $menuId ?>">
            <input type="hidden" name="orderId" value="<?= $orderId ?>">
            <select name="rating" required>
              <option value="">Rate</option>
              <option value="1">⭐ 1</option>
              <option value="2">⭐ 2</option>
              <option value="3">⭐ 3</option>
              <option value="4">⭐ 4</option>
              <option value="5">⭐ 5</option>
            </select>
            <input type="submit" value="Submit">
          </form>
        <?php endif; ?>
      </div>
    <?php endwhile; ?>
    </div>
  </div>
<?php endwhile; ?>

</body>
</html>
