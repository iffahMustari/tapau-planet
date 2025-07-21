<?php
session_start();
include('../connection.php');

$query = "SELECT f.feedbackId, f.feedbackText, f.serviceRating, f.feedbackDate, u.userName, u.userEmail
          FROM feedback f
          JOIN users u ON f.userId = u.userId
          ORDER BY f.feedbackDate DESC";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Feedback | Tapau Planet</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      background-color: #f4f4f4;
      color: #333;
    }

    .custom-flex {
      display: flex;
    }

    /* Sidebar (same as admin_dashboard.php) */
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

    /* Main Content */
    .main-content {
      flex-grow: 1;
      padding: 30px;
    }

    h2 {
      font-size: 28px;
      margin-bottom: 25px;
      font-weight: 600;
      border-bottom: 2px solid #ff914d;
      padding-bottom: 10px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      overflow: hidden;
    }

    th, td {
      padding: 14px 16px;
      text-align: left;
      font-size: 14px;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #f0f0f0;
      color: #333;
      font-weight: 600;
    }

    tr:nth-child(even) {
      background-color: #fff;
    }

    tr:hover {
      background-color: #f0f0f0;
    }

    @media (max-width: 768px) {
      .main-content {
        padding: 20px;
      }

      table, th, td {
        font-size: 13px;
      }

      h2 {
        font-size: 22px;
      }

      .sidebar {
        display: none;
      }
    }
  </style>
</head>
<body>
  <div class="custom-flex">
    <!-- Sidebar -->
    <nav class="sidebar">
      <div class="logo-container">
        <img src="../images/CSC264 LOGO 1.png" alt="Restaurant Logo" class="logo-img">
      </div>
      <ul class="list-unstyled">
        <li><a href="indexadmin.php"><i class="fas fa-chart-bar"></i> Dashboard</a></li>
        <li><a href="admin_menu.php"><i class="fas fa-utensils"></i> Menu</a></li>
        <li><a href="admin_order.php"><i class="fas fa-receipt"></i> Orders</a></li>
        <li><a href="admin_staff.php"><i class="fas fa-user-tie"></i> Staff</a></li>
        <li><a href="admin_user.php" class="active"><i class="fas fa-comments"></i> Feedback</a></li>
        <li><a href="../logout.php"><i class="fas fa-user-alt"></i> Logout</a></li>
      </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
      <h2>Customer Review</h2>
      <table>
        <thead>
          <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Feedback</th>
            <th>Rating</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = mysqli_fetch_assoc($result)) { ?>
          <tr>
            <td><?= htmlspecialchars($row['userName']); ?></td>
            <td><?= htmlspecialchars($row['userEmail']); ?></td>
            <td><?= htmlspecialchars($row['feedbackText']); ?></td>
            <td><?= htmlspecialchars($row['serviceRating']); ?>/5</td>
            <td><?= date('d M Y, h:i A', strtotime($row['feedbackDate'])); ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
