<!-- Simpan sebagai admin_dashboard.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tapau Planet Admin Dashboard</title>
  <link rel="stylesheet" href="../css/indexadmin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <style>
  /* ======= Base Styling ======= */
  body {
    font-family: Arial, sans-serif;
    margin: 0;
    background-color: #f4f4f4;
  }

  .custom-flex {
    display: flex;
  }

  /* ======= Sidebar Styling ======= */
  .sidebar {
    width: 220px;
    background: #ffffff;
    border-right: 1px solid #ddd;
    min-height: 100vh;
  }

  .logo-container {
    padding: 20px;
  }

  .logo-img {
    max-width: 100%;
    height: auto;
  }

  ul.list-unstyled {
    list-style: none;
    padding: 0;
  }

  ul.list-unstyled li a {
    display: block;
    padding: 12px 20px;
    color: #333;
    text-decoration: none;
    border-left: 4px solid transparent;
    transition: all 0.3s ease;
  }

  ul.list-unstyled li a.active,
  ul.list-unstyled li a:hover {
    background-color: #f0f0f0;
    border-left: 4px solid #ff914d;
    color: #ff914d;
  }

  /* ======= Main Content ======= */
  .main-content {
    flex-grow: 1;
    padding: 30px;
  }

  /* ======= Card Layout ======= */
  .custom-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 30px;
  }

  .custom-col {
    flex: 1;
    min-width: 280px;
  }

  .custom-card {
    background-color: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
  }

  /* ======= Card Colors ======= */
  .bg-primary   { background-color: #007bff; }
  .bg-success   { background-color: #28a745; }
  .bg-warning   { background-color: #ffc107; }
  .bg-danger    { background-color: #dc3545; }
  .bg-info      { background-color: #17a2b8; }
  .bg-secondary { background-color: #6c757d; }

  .text-white {
    color: #fff;
  }

  /* ======= Chart Styling ======= */
canvas {
  display: block;
  margin: auto;
  width: 100% !important;
  max-width: 600px;  /* Tambah size lebih besar */
  height: auto !important;
  max-height: 400px;
}

  /* ======= Image Thumbnail ======= */
  .img-thumbnail {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 8px;
  }
</style>
</head>
<body>
<div class="custom-flex">
  <nav class="sidebar">
    <div class="logo-container">
      <img src="../images/CSC264 LOGO 1.png" alt="Restaurant Logo" class="logo-img">
    </div>
    <ul class="list-unstyled">
      <p class="display">Home</p>
      <li><a href="#" class="active"><i class="fas fa-chart-bar"></i> Dashboard</a></li>
      <li><a href="admin_menu.php"><i class="fas fa-utensils"></i> Menu</a></li>
      <li><a href="admin_order.php"><i class="fas fa-receipt"></i> Orders</a></li>
      <li><a href="admin_staff.php"><i class="fas fa-user-tie"></i> Staff</a></li>
      <li><a href="admin_user.php"><i class="fas fa-comments"></i> Feedback</a></li>
      <li><a href="../logout.php"><i class="fas fa-user-alt"></i> Logout</a></li>
    </ul>
  </nav>

  <div class="main-content">
   <?php
session_start();
$name = $_SESSION['user']['userName'] ?? 'Guest';
echo "<h1>Welcome to Dashboard, <span style='color: #ff914d;'>$name</span></h1>";

      $conn = new mysqli("localhost", "root", "", "tapauplanet");

      $totalOrders = $totalSales = $activeMenu = $activeUsers = $totalStaff = $totalFeedback = 0;

if (!$conn->connect_error) {
    $conn->query("SET SESSION sql_mode = ''");

    $totalOrders = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'] ?? 0;

    $totalSales = $conn->query("SELECT SUM(payAmount) as total FROM payment")->fetch_assoc()['total'] ?? 0;

    $activeMenu = $conn->query("SELECT COUNT(*) as total FROM menu")->fetch_assoc()['total'] ?? 0;

    $activeUsers = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'] ?? 0;

    $totalStaff = $conn->query("SELECT COUNT(*) as total FROM staff")->fetch_assoc()['total'] ?? 0;

    $totalFeedback = $conn->query("SELECT COUNT(*) as total FROM feedback")->fetch_assoc()['total'] ?? 0;
?>


    <div class="custom-row">
      <div class="custom-col"><div class="custom-card bg-primary text-white"><h6>Total Orders</h6><h4><?= $totalOrders ?></h4></div></div>
      <div class="custom-col"><div class="custom-card bg-success text-white"><h6>Total Sales</h6><h4>RM <?= number_format($totalSales, 2) ?></h4></div></div>
      <div class="custom-col"><div class="custom-card bg-warning text-white"><h6>Active Menu</h6><h4><?= $activeMenu ?></h4></div></div>
      <div class="custom-col"><div class="custom-card bg-danger text-white"><h6>Active Users</h6><h4><?= $activeUsers ?></h4></div></div>
      <div class="custom-col"><div class="custom-card bg-info text-white"><h6>Total Staff</h6><h4><?= $totalStaff ?></h4></div></div>
      <div class="custom-col"><div class="custom-card bg-secondary text-white"><h6>Total Feedback</h6><h4><?= $totalFeedback ?></h4></div></div>
    </div>

    <!-- Charts -->
    <div class="custom-row">
      <div class="custom-col"><div class="custom-card"><h3>Monthly Orders</h3><canvas id="totalOrderChart"></canvas></div></div>
      <div class="custom-col"><div class="custom-card"><h3>Monthly Sales</h3><canvas id="salesChart"></canvas></div></div>
    </div>

    <div class="custom-row">
      <div class="custom-col"><div class="custom-card"><h3>Delivery vs Pick Up</h3><canvas id="orderMethodChart"></canvas></div></div>
      <div class="custom-col"><div class="custom-card"><h3>Payment Method</h3><canvas id="paymentMethodChart"></canvas></div></div>
    </div>

    <div class="custom-row">
      <!-- Top Menus -->
      <div class="custom-col">
        <div class="custom-card">
          <h3>Top 5 Menus</h3>
          <?php
            $sqlMenus = "
              SELECT m.menuName, m.menuPic, COUNT(*) AS total 
              FROM ordermenu oi 
              JOIN menu m ON oi.menuId = m.menuId 
              GROUP BY oi.menuId 
              ORDER BY total DESC 
              LIMIT 5
            ";
            $resultMenus = $conn->query($sqlMenus);
            if ($resultMenus->num_rows > 0) {
              while ($row = $resultMenus->fetch_assoc()) {
                echo '
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                  <img src="../images/' . htmlspecialchars($row['menuPic']) . '" alt="menu" class="img-thumbnail">
                  <div style="margin-left: 10px;">
                    <strong>' . htmlspecialchars($row['menuName']) . '</strong><br>
                    ' . $row['total'] . ' orders
                  </div>
                </div>';
              }
            } else {
              echo "<p>No menu data available.</p>";
            }
          ?>
        </div>
      </div>

<!-- Top Categories -->
<div class="custom-col">
  <div class="custom-card">
    <h3>Top Categories</h3>
    <?php
      $sqlCategories = "
        SELECT c.categoryName, SUM(o.quantity * m.menuPrice) AS totalSales
        FROM ordermenu o
        JOIN menu m ON o.menuId = m.menuId
        JOIN category c ON m.categoryId = c.categoryId
        GROUP BY c.categoryId
        ORDER BY totalSales DESC
        LIMIT 5
      ";
      $resultCategories = $conn->query($sqlCategories);
      if ($resultCategories && $resultCategories->num_rows > 0) {
        while ($row = $resultCategories->fetch_assoc()) {
          echo '
            <div style="margin-bottom: 15px;">
              <strong>' . htmlspecialchars($row['categoryName']) . '</strong><br>
              RM ' . number_format($row['totalSales'], 2) . '
            </div>';
        }
      } else {
        echo "<p>No category data available.</p>";
      }

      $conn->close();
    ?>
  </div>
</div>
    </div>
    <?php } ?>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  fetch("admin_sales.php")
    .then(res => res.json())
    .then(data => initCharts(data))
    .catch(err => console.error("Chart error:", err));

  function initCharts(data) {
    createBarChart("totalOrderChart", "Orders", data.orders.labels, data.orders.values, "#4bc0c0");
    createBarChart("salesChart", "Sales (RM)", data.sales.labels, data.sales.values, "#ff914d");
    createPieChart("orderMethodChart", data.orderMethods.labels, data.orderMethods.values, ["#ffcd56", "#4bc0c0"]);
    createPieChart("paymentMethodChart", data.paymentMethods.labels, data.paymentMethods.values, ["#ff6384", "#36a2eb", "#9966ff"]);
  }

  function createBarChart(id, label, labels, values, color) {
    new Chart(document.getElementById(id), {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{ label: label, data: values, backgroundColor: color }]
      },
      options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });
  }

  function createPieChart(id, labels, values, colors) {
    new Chart(document.getElementById(id), {
      type: 'pie',
      data: {
        labels: labels,
        datasets: [{ data: values, backgroundColor: colors }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'right' },
          tooltip: {
            callbacks: {
              label: function(context) {
                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                const value = context.raw;
                const percentage = Math.round((value / total) * 100);
                return `${context.label}: ${value} (${percentage}%)`;
              }
            }
          }
        }
      }
    });
  }
});
</script>
</body>
</html>
