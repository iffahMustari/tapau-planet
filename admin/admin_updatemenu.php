<?php
include('../connection.php');

$menuId = $name = $price = $categoryId = $menuDesc = $oldPic = "";
$error = $success = "";

// GET: ambil data menu berdasarkan menuId
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['menuId'])) {
    $menuId = intval($_GET['menuId']);
    $stmt = $conn->prepare("SELECT menuName, menuPrice, categoryId, menuDesc, menuPic FROM menu WHERE menuId = ?");
    $stmt->bind_param("i", $menuId);
    $stmt->execute();
    $stmt->bind_result($name, $price, $categoryId, $menuDesc, $oldPic);
    if (!$stmt->fetch()) {
        $error = "Undefined menu.";
    }
    $stmt->close();
}

// POST: proses update menu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $menuId = intval($_POST['menuId']);
    $name = $_POST['name']; 
    $price = $_POST['price']; 
    $menuDesc = $_POST['menuDesc'];
    $categoryId = $_POST['category'];
    $oldPic = $_POST['oldPic'];

    // Handle image
    if (isset($_FILES['menuPic']) && $_FILES['menuPic']['error'] === 0) {
        $ext = pathinfo($_FILES['menuPic']['name'], PATHINFO_EXTENSION);
        $image = uniqid('menu_', true) . '.' . $ext;
        $image_tmp = $_FILES['menuPic']['tmp_name'];
        $target = "../images/" . $image;

        if (!move_uploaded_file($image_tmp, $target)) {
            $error = "Failed to upload new image.";
        } else {
            if ($oldPic && file_exists("../images/" . $oldPic)) {
                unlink("../images/" . $oldPic);
            }
        }
    } else {
        $image = $oldPic;
    }

    if (!$error) {
        $stmt = $conn->prepare("UPDATE menu SET menuName=?, menuPrice=?, menuDesc=?, categoryId=?, menuPic=? WHERE menuId=?");
        $stmt->bind_param("sdsisi", $name, $price, $menuDesc, $categoryId, $image, $menuId);

        if ($stmt->execute()) {
            header("Location: admin_menu.php?msg=update_success");
            exit();
        } else {
            $error = "Update failed. Please try again.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Update Product</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f6f9;
      color: #333;
      min-height: 100vh;
      display: flex;
      flex-direction: row;
    }

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

    .main {
      margin-left: 60px;
      padding: 30px 40px;
      flex-grow: 1;
      background-color: #f4f6f9;
    }

    .header {
      background-color: #fff;
      padding: 20px 30px;
      border-radius: 10px;
      margin-bottom: 30px;
      box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    }

    .header h1 {
      font-size: 28px;
      font-weight: 700;
    }

    .update-product {
      max-width: 700px;
      margin: auto;
      background: white;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .update-product h1.heading {
      text-align: center;
      margin-bottom: 25px;
      color: #444;
    }

    .update-product img {
      max-width: 220px;
      max-height: 220px;
      object-fit: cover;
      display: block;
      margin: 0 auto 10px;
      border-radius: 8px;
      box-shadow: 0 0 8px rgba(0,0,0,0.12);
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 18px;
    }

    label, span {
      font-weight: 600;
      color: #555;
    }

    input.box, select.box {
      width: 100%;
      padding: 12px 15px;
      font-size: 16px;
      border: 1.5px solid #ccc;
      border-radius: 6px;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    input.box:focus, select.box:focus {
      border-color: #ff914d;
      box-shadow: 0 0 5px #ff914daa;
    }

    input[type="file"].box {
      padding: 5px 10px;
    }

    .btn-submit {
      background-color: #2ecc71;
      color: white;
      padding: 14px 0;
      font-weight: 700;
      font-size: 16px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s ease;
      box-shadow: 0 4px 10px rgba(46, 204, 113, 0.4);
    }

    .btn-submit:hover {
      background-color: #27ae60;
      box-shadow: 0 6px 14px rgba(39, 174, 96, 0.6);
    }

    .btn-back {
      text-align: center;
      margin-top: 20px;
    }

    .btn-back a {
      color: #888;
      font-size: 15px;
      text-decoration: none;
    }

    .btn-back a:hover {
      text-decoration: underline;
    }

    .message {
      padding: 10px 15px;
      background-color: #ffe6e6;
      color: #c0392b;
      border-radius: 5px;
      margin-bottom: 15px;
      text-align: center;
    }

    @media (max-width: 768px) {
      .main {
        padding: 20px;
        margin-left: 0;
      }

      .sidebar {
        display: none;
      }

      .update-product {
        padding: 20px;
      }
    }
  </style>
</head>
<body>

  <nav class="sidebar">
    <div class="logo-container">
      <img src="../images/CSC264 LOGO 1.png" alt="Restaurant Logo" class="logo-img">
    </div>
    <ul class="list-unstyled">
      <li><a href="admin_dashboard.php"><i class="fas fa-chart-bar"></i> Dashboard</a></li>
      <li><a href="admin_menu.php" class="active"><i class="fas fa-utensils"></i> Menu</a></li>
      <li><a href="admin_order.php"><i class="fas fa-receipt"></i> Orders</a></li>
      <li><a href="admin_staff.php"><i class="fas fa-user-tie"></i> Staff</a></li>
      <li><a href="admin_user.php"><i class="fas fa-comments"></i> Feedback</a></li>
      <li><a href="../logout.php"><i class="fas fa-user-alt"></i> Logout</a></li>
    </ul>
  </nav>

  <main class="main">
    <section class="header">
      <h1>Update Product</h1>
    </section>

    <section class="update-product">
      <?php if ($error): ?>
        <div class="message"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form action="admin_updatemenu.php" method="POST" enctype="multipart/form-data" autocomplete="off">
        <input type="hidden" name="menuId" value="<?= htmlspecialchars($menuId) ?>" />
        <input type="hidden" name="oldPic" value="<?= htmlspecialchars($oldPic) ?>" />

        <img src="../images/<?= htmlspecialchars($oldPic) ?>" alt="Current Menu Image" />
        <small style="display:block; text-align:center; color:#777;">Current product image</small>

        <label for="name">Name</label>
        <input type="text" name="name" id="name" class="box" placeholder="Enter product name" value="<?= htmlspecialchars($name) ?>" required />

        <label for="price">Price (RM)</label>
        <input type="number" name="price" id="price" class="box" placeholder="Enter price" step="0.01" min="0" value="<?= htmlspecialchars($price) ?>" required />

        <label for="menuDesc">Description</label>
        <input type="text" name="menuDesc" id="menuDesc" class="box" placeholder="Enter description" value="<?= htmlspecialchars($menuDesc) ?>" required />

        <label for="category">Category</label>
        <select name="category" id="category" class="box" required>
          <option value="">--Select Category--</option>
          <option value="1" <?= ($categoryId == 1) ? 'selected' : '' ?>>Malay</option>
          <option value="2" <?= ($categoryId == 2) ? 'selected' : '' ?>>Western</option>
          <option value="3" <?= ($categoryId == 3) ? 'selected' : '' ?>>Korean</option>
          <option value="4" <?= ($categoryId == 4) ? 'selected' : '' ?>>Drinks</option>
        </select>

        <label for="menuPic">Change Image</label>
        <input type="file" name="menuPic" id="menuPic" class="box" accept="image/*" />

        <button type="submit" class="btn-submit" name="submit">Update</button>
      </form>

      <div class="btn-back">
        <a href="admin_menu.php"><i class="fas fa-arrow-left"></i> Back to Menu</a>
      </div>
    </section>
  </main>
</body>
</html>
