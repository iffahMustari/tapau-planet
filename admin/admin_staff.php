<?php
session_start();
include('../connection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Staff Management</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: system-ui, Arial, sans-serif;
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

    h1 {
      font-weight: 700;
      color: #333;
      margin-bottom: 1.5rem;
      text-align: center;
    }

    .card {
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,.05);
      padding: 1rem;
      margin-top: 1rem;
    }

    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
    }

    .card-header h5 {
      margin: 0;
      font-size: 1.1rem;
      color: #333;
    }

    .btn {
      padding: 6px 12px;
      border: none;
      border-radius: 4px;
      font-size: .9rem;
      text-decoration: none;
      color: #fff;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: .4rem;
    }

    .btn-primary {
      background: #007bff;
    }

    .btn-warning {
      background: #ffc107;
      color: #000;
    }

    .btn-danger {
      background: #dc3545;
      color: #fff;
    }

    .btn:hover {
      opacity: .85;
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
      color: #333;
    }

    .staff-img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 6px;
    }

    .text-center {
      text-align: center;
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
      <li><a href="admin_order.php"><i class="fas fa-receipt"></i> Orders</a></li>
      <li><a href="admin_staff.php" class="active"><i class="fas fa-user-tie"></i> Staff</a></li>
      <li><a href="admin_user.php"><i class="fas fa-user-friends"></i> Customers</a></li>
      <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </nav>

  <!-- Main content -->
  <main class="main" id="mainContent">
    <h1>Staff Management <span style="color:#007bff">Panel</span></h1>
    <div class="card">
      <div class="card-header">
        <h5>Staff Members</h5>
        <a href="add_staff.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Staff</a>
      </div>
      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Photo</th>
              <th>Name</th>
              <th>Age</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Role</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $sql = "SELECT * FROM staff";
              $result = $conn->query($sql);
              if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $staffPic = !empty($row['staffPic']) && file_exists("../images/" . $row['staffPic']) 
                    ? htmlspecialchars($row['staffPic']) 
                    : "default_staff.jpg";
                  
                  echo "<tr>";
                  echo "<td>" . htmlspecialchars($row['staffId']) . "</td>";
                  echo "<td><img src='../images/" . $staffPic . "' class='staff-img' alt='Staff Photo'></td>";
                  echo "<td>" . htmlspecialchars($row['staffName']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['staffAge']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['staffEmail']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['staffPhone']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['staffRole']) . "</td>";
                  echo "<td>
                          <a href='edit_staff.php?id=" . $row['staffId'] . "' class='btn btn-warning'><i class='fas fa-edit'></i></a>
                          <a href='delete_staff.php?staffId=" . $row['staffId'] . "' class='btn btn-danger' onclick='return confirm(\"Are you sure?\")'><i class='fas fa-trash'></i></a>
                        </td>";
                  echo "</tr>";
                }
              } else {
                echo '<tr><td colspan="8" class="text-center">No staff members found</td></tr>';
              }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

</body>
</html>
<?php $conn->close(); ?>
