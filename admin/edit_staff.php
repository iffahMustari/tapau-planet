<?php
include('../connection.php');

$id = $_GET['id'] ?? 0;
$staff = [];

// Fetch staff data
$sql = "SELECT * FROM staff WHERE staffId = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$staff = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staffId = $_POST['staffId'];
    $staffName = $_POST['staffName'];
    $staffEmail = $_POST['staffEmail'];
    $staffPhone = $_POST['staffPhone'];
    $staffRole = $_POST['staffRole'];
    $staffPic = $staff['staffPic'];

    if (!empty($_FILES['staffPic']['name'])) {
        $target_dir = "../images/";
        $target_file = $target_dir . basename($_FILES['staffPic']['name']);
        if (move_uploaded_file($_FILES['staffPic']['tmp_name'], $target_file)) {
            $staffPic = $_FILES['staffPic']['name'];
        }
    }

    $sql = "UPDATE staff SET staffName=?, staffEmail=?, staffPhone=?, staffRole=?, staffPic=? WHERE staffId=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $staffName, $staffEmail, $staffPhone, $staffRole, $staffPic, $staffId);

    if ($stmt->execute()) {
        header("Location: admin_staff.php?success=1");
        exit();
    } else {
        $error = "Error updating staff: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Staff</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: system-ui, Arial, sans-serif;
      background: #f4f6f9;
      display: flex;
      min-height: 100vh;
      color: #333;
    }

    .sidebar {
      width: 220px;
      background: #fff;
      border-right: 1px solid #ddd;
      min-height: 100vh;
      padding-top: 20px;
      position: fixed;
      left: 0;
      top: 0;
    }

    .logo-img {
      width: 100px;
      display: block;
      margin: 0 auto 20px;
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

    .main {
      margin-left: 220px;
      padding: 2rem;
      flex: 1;
    }

    .container {
      max-width: 600px;
      margin: 0 auto;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 1rem;
    }

    label {
      display: block;
      font-weight: 600;
      margin-bottom: .4rem;
    }

    input, select {
      width: 100%;
      padding: .6rem;
      font-size: 1rem;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    input[type="file"] {
      padding: .4rem;
    }

    .btn {
      padding: .6rem 1.2rem;
      border: none;
      border-radius: 6px;
      font-size: 1rem;
      color: #fff;
      cursor: pointer;
      text-decoration: none;
      margin-top: 1rem;
      display: inline-block;
    }

    .btn-primary {
      background: #007bff;
    }

    .btn-secondary {
      background: #6c757d;
    }

    .alert {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c2c7;
      padding: 1rem;
      border-radius: 6px;
      margin-bottom: 1rem;
    }

    small.text-muted {
      display: block;
      margin-top: .3rem;
      color: #666;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <nav class="sidebar">
    <img src="../images/CSC264 LOGO 1.png" class="logo-img" alt="Logo" />
    <ul>
      <li><a href="indexadmin.php"><i class="fas fa-chart-bar"></i> Dashboard</a></li>
      <li><a href="admin_menu.php"><i class="fas fa-utensils"></i> Menu</a></li>
      <li><a href="admin_order.php"><i class="fas fa-receipt"></i> Orders</a></li>
      <li><a href="admin_staff.php" class="active"><i class="fas fa-user-tie"></i> Staff</a></li>
      <li><a href="admin_user.php"><i class="fas fa-user-friends"></i> Customers</a></li>
      <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </nav>

  <!-- Main Content -->
  <main class="main">
    <div class="container">
      <h2>Edit Staff Member</h2>

      <?php if (isset($error)): ?>
        <div class="alert"><?php echo $error; ?></div>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label for="staffId">Staff ID</label>
          <input type="text" id="staffId" name="staffId" value="<?php echo htmlspecialchars($staff['staffId']); ?>" readonly>
        </div>

        <div class="form-group">
          <label for="staffName">Name</label>
          <input type="text" id="staffName" name="staffName" value="<?php echo htmlspecialchars($staff['staffName']); ?>" required>
        </div>

        <div class="form-group">
          <label for="staffEmail">Email</label>
          <input type="email" id="staffEmail" name="staffEmail" value="<?php echo htmlspecialchars($staff['staffEmail']); ?>" required>
        </div>

        <div class="form-group">
          <label for="staffPhone">Phone</label>
          <input type="text" id="staffPhone" name="staffPhone" value="<?php echo htmlspecialchars($staff['staffPhone']); ?>" required>
        </div>

        <div class="form-group">
          <label for="staffRole">Position</label>
          <select id="staffRole" name="staffRole" required>
            <option value="Chef" <?php echo $staff['staffRole'] == 'Chef' ? 'selected' : ''; ?>>Chef</option>
            <option value="Waiter" <?php echo $staff['staffRole'] == 'Waiter' ? 'selected' : ''; ?>>Waiter</option>
            <option value="Manager" <?php echo $staff['staffRole'] == 'Manager' ? 'selected' : ''; ?>>Manager</option>
            <option value="Rider" <?php echo $staff['staffRole'] == 'Rider' ? 'selected' : ''; ?>>Rider</option>
          </select>
        </div>

        <div class="form-group">
          <label for="staffPic">Photo</label>
          <input type="file" id="staffPic" name="staffPic">
          <small class="text-muted">Current: <?php echo htmlspecialchars($staff['staffPic']); ?></small>
        </div>

        <button type="submit" class="btn btn-primary">Update Staff</button>
        <a href="admin_staff.php" class="btn btn-secondary">Cancel</a>
      </form>
    </div>
  </main>

</body>
</html>
