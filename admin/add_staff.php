<?php
include('../connection.php');

// Initialize variables
$error = '';
$staffName = $staffEmail = $staffPhone = $staffRole = '';
$staffAge = 0;
$staffPic = 'default_staff.jpg';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staffName = trim($_POST['staffName'] ?? '');
    $staffAge = intval($_POST['staffAge'] ?? 0);
    $staffEmail = trim($_POST['staffEmail'] ?? '');
    $staffPhone = trim($_POST['staffPhone'] ?? '');
    $staffRole = trim($_POST['staffRole'] ?? '');

    // Validate file upload
    if (isset($_FILES['staffPic']) && $_FILES['staffPic']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../images/";
        $original_name = basename($_FILES['staffPic']['name']);
        $extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($extension, $allowed)) {
            $clean_name = preg_replace('/[^a-zA-Z0-9\-_\.]/', '', $original_name);
            $new_filename = 'staff_' . time() . '_' . $clean_name;
            $target_file = $target_dir . $new_filename;

            if (move_uploaded_file($_FILES['staffPic']['tmp_name'], $target_file)) {
                $staffPic = $new_filename;
            }
        }
    }

    // Validation
    if (empty($staffName) || empty($staffEmail) || empty($staffPhone) || empty($staffRole)) {
        $error = "All fields are required!";
    } elseif ($staffAge < 18 || $staffAge > 100) {
        $error = "Please enter a valid age (18-100).";
    } else {
        $sql = "INSERT INTO staff (staffName, staffAge, staffEmail, staffPhone, staffRole, staffPic)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sissss", $staffName, $staffAge, $staffEmail, $staffPhone, $staffRole, $staffPic);
            if ($stmt->execute()) {
                header("Location: admin_staff.php?success=1");
                exit();
            } else {
                $error = "Error adding staff: " . $conn->error;
            }
        } else {
            $error = "Database error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Staff</title>
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
      margin-bottom: .4rem;
      font-weight: 600;
    }

    .required::after {
      content: ' *';
      color: red;
    }

    input, select {
      width: 100%;
      padding: .6rem;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
    }

    input[type="file"] {
      padding: .3rem;
    }

    small {
      font-size: .8rem;
      color: #777;
    }

    .btn {
      padding: .6rem 1.2rem;
      border: none;
      border-radius: 6px;
      font-size: 1rem;
      color: #fff;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
    }

    .btn-primary { background: #007bff; }
    .btn-secondary { background: #6c757d; }

    .alert {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c2c7;
      padding: 1rem;
      border-radius: 6px;
      margin-bottom: 1rem;
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
    <h2>Add New Staff</h2>

    <?php if (!empty($error)): ?>
      <div class="alert"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label class="required">Full Name</label>
        <input type="text" name="staffName" required value="<?php echo htmlspecialchars($staffName); ?>">
      </div>

      <div class="form-group">
        <label class="required">Age</label>
        <input type="number" name="staffAge" min="18" max="100" required value="<?php echo htmlspecialchars($staffAge); ?>">
      </div>

      <div class="form-group">
        <label class="required">Email</label>
        <input type="email" name="staffEmail" required value="<?php echo htmlspecialchars($staffEmail); ?>">
      </div>

      <div class="form-group">
        <label class="required">Phone</label>
        <input type="tel" name="staffPhone" required value="<?php echo htmlspecialchars($staffPhone); ?>">
      </div>

      <div class="form-group">
        <label class="required">Position</label>
        <select name="staffRole" required>
          <option value="">Select</option>
          <option value="Chef" <?php if($staffRole == 'Chef') echo 'selected'; ?>>Chef</option>
          <option value="Waiter" <?php if($staffRole == 'Waiter') echo 'selected'; ?>>Waiter</option>
          <option value="Manager" <?php if($staffRole == 'Manager') echo 'selected'; ?>>Manager</option>
          <option value="Rider" <?php if($staffRole == 'Rider') echo 'selected'; ?>>Rider</option>
        </select>
      </div>

      <div class="form-group">
        <label>Photo</label>
        <input type="file" name="staffPic" accept="image/*">
        <small>Allowed types: JPG, PNG, GIF (Max 2MB)</small>
      </div>

      <button type="submit" class="btn btn-primary">Add Staff</button>
      <a href="admin_staff.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</main>

</body>
</html>
