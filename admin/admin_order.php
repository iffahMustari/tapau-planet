<?php
include '../connection.php'; // sambung ke database

// Fetch orders
$sql = "SELECT * FROM orders ORDER BY orderId DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Orders</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', Arial, sans-serif;
      background: #f4f6f9;
      display: flex;
      min-height: 100vh;
      color: #333;
    }

    /* Sidebar */
    .sidebar {
      width: 220px;
      background: #ffffff;
      border-right: 1px solid #ddd;
      min-height: 100vh;
      padding-top: 20px;
      display: flex;
      flex-direction: column;
      position: fixed;
      left: 0;
      top: 0;
    }

    .logo-container {
      padding: 20px;
      text-align: center;
    }

    .logo-img {
      width: 100px;
    }

    ul {
      list-style: none;
      padding: 0;
    }

    ul li a {
      display: block;
      padding: 12px 20px;
      color: #333;
      text-decoration: none;
      border-left: 4px solid transparent;
      transition: all 0.3s ease;
    }

    ul li a:hover,
    ul li a.active {
      background-color: #f0f0f0;
      border-left: 4px solid #ff914d;
      color: #ff914d;
    }

    /* Main */
    .main {
      margin-left: 220px;
      padding: 2rem;
      flex: 1;
    }

    h2 {
      margin-bottom: 20px;
      text-align: center;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
    }

    .table th, .table td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: center;
    }

    .table th {
      background: #f0f0f0;
    }

    .btn {
      padding: 4px 8px;
      font-size: 0.85rem;
      border: none;
      border-radius: 4px;
      text-decoration: none;
      cursor: pointer;
      color: #fff;
    }

    .btn-primary {
      background: #007bff;
    }

    select.form-select-sm {
      width: 140px;
      padding: 4px;
      font-size: 0.85rem;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <nav class="sidebar">
    <div class="logo-container">
      <img src="../images/CSC264 LOGO 1.png" class="logo-img" alt="Logo" />
    </div>
    <ul>
      <li><a href="indexadmin.php"><i class="fas fa-chart-bar"></i> Dashboard</a></li>
      <li><a href="admin_menu.php"><i class="fas fa-utensils"></i> Menu</a></li>
      <li><a href="admin_order.php" class="active"><i class="fas fa-receipt"></i> Orders</a></li>
      <li><a href="admin_staff.php"><i class="fas fa-user-tie"></i> Staff</a></li>
      <li><a href="admin_user.php"><i class="fas fa-comments"></i> Feedback</a></li>
      <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </nav>

  <!-- Main Content -->
  <main class="main">
    <h2>Order Management</h2>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Order No.</th>
          <th>Customer ID</th>
          <th>Order Type</th>
          <th>Payment Method</th>
          <th>Order Date</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>#ORD-" . str_pad($row['orderId'], 3, '0', STR_PAD_LEFT) . "</td>";
            echo "<td>" . htmlspecialchars($row['userId']) . "</td>";
            echo "<td>" . htmlspecialchars($row['orderType']) . "</td>";
            echo "<td>" . htmlspecialchars($row['payMethod']) . "</td>";
            echo "<td>" . htmlspecialchars($row['orderDate']) . "</td>";

            echo "<td>";
            echo "<form method='POST' action='update_status.php'>";
            echo "<input type='hidden' name='orderId' value='" . $row['orderId'] . "'>";
            echo "<select name='orderStatus' class='form-select form-select-sm' onchange='this.form.submit()'>";
            echo "<option value='Pending'" . ($row['orderStatus'] == 'Pending' ? ' selected' : '') . ">Pending</option>";
            echo "<option value='Approved'" . ($row['orderStatus'] == 'Approved' ? ' selected' : '') . ">Approved</option>";
            echo "<option value='Completed'" . ($row['orderStatus'] == 'Completed' ? ' selected' : '') . ">Completed</option>";
            echo "</select>";
            echo "</form>";
            echo "</td>";

            echo "<td><a href='../receipt.php?orderId=" . $row['orderId'] . "' class='btn btn-primary'>View Receipt</a></td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='7'>No orders found.</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </main>

</body>
</html>
<?php mysqli_close($conn); ?>
