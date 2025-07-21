<?php
include('../connection.php');// connect to database

if ($conn->connect_error) {
    die("Connection failed: {$conn->connect_error}");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $menuName = $_POST['menuName'];
    $menuPrice = $_POST['menuPrice'];
    $categoryId = $_POST['categoryId'];
    $menuDesc = $_POST['menuDesc'];

    $img_menuPic = $_FILES['menuPic']['name'];
    $img_tmp = $_FILES['menuPic']['tmp_name'];

    // Image folder location (one level up from admin)
    $img_folder = "../images/" . basename($img_menuPic);

    if (move_uploaded_file($img_tmp, $img_folder)) {
        // menuId DIBUANG, sebab AUTO_INCREMENT
        $sql = "INSERT INTO menu (menuName, menuPrice, categoryId, menuDesc, menuPic) 
                VALUES ('$menuName', '$menuPrice', '$categoryId', '$menuDesc', '$img_menuPic')";

        if ($conn->query($sql) === TRUE) {
           header("Location: admin_menu.php?msg=add_success");
           exit();
        } else {
            header("Location: admin_menu.php?msg=add_fail");
            exit();
        }
    } else {
       header("Location: admin_menu.php?msg=invalid_request");
       exit();
    }
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Update Product</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    /* RESET */
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

    /* SIDEBAR */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 60px;
      background-color: #fff;
      padding-top: 20px;
      box-shadow: 2px 0 5px rgba(0,0,0,0.1);
      transition: width 0.3s ease;
      overflow-x: hidden;
      z-index: 999;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .sidebar:hover {
      width: 250px;
      align-items: flex-start;
      padding-left: 20px;
    }

    .logo-container {
      width: 100%;
      text-align: center;
      margin-bottom: 30px;
    }

    .logo-img {
      width: 40px;
      transition: width 0.3s ease;
      user-select: none;
    }

    .sidebar:hover .logo-img {
      width: 140px;
      margin-left: 10px;
    }

    .sidebar ul {
      list-style: none;
      width: 100%;
      padding-left: 0;
    }

    .sidebar ul li {
      margin: 15px 0;
      width: 100%;
    }

    .sidebar ul li a {
      display: flex;
      align-items: center;
      padding: 10px 20px;
      text-decoration: none;
      color: #333;
      font-size: 16px;
      border-radius: 6px;
      transition: background-color 0.3s ease, color 0.3s ease;
      white-space: nowrap;
      width: 100%;
    }

    .sidebar ul li a i {
      min-width: 25px;
      text-align: center;
      font-size: 18px;
      margin-right: 0;
      transition: color 0.3s ease;
    }

    .sidebar ul li a span {
      opacity: 0;
      margin-left: 0;
      transition: opacity 0.3s ease, margin-left 0.3s ease;
      width: 0;
      overflow: hidden;
      display: inline-block;
      user-select: none;
    }

    .sidebar:hover ul li a span {
      opacity: 1;
      margin-left: 12px;
      width: auto;
    }

    .sidebar ul li a:hover {
      background-color: #f0f0f0;
      color: #ff914d;
    }

    .sidebar ul li a.active {
      background-color: #ff914d;
      color: white;
      font-weight: 600;
    }
    .sidebar ul li a.active i {
      color: white;
    }

    /* MAIN CONTENT */
    .main {
      margin-left: 60px;
      padding: 30px 40px;
      flex-grow: 1;
      transition: margin-left 0.3s ease;
      min-height: 100vh;
      background-color: #f4f6f9;
    }

    .sidebar:hover ~ .main {
      margin-left: 250px;
    }

    /* HEADER */
    .header {
      background-color: #fff;
      color: #222;
      padding: 20px 30px;
      border-radius: 10px;
      margin-bottom: 40px;
      box-shadow: 0 3px 8px rgba(0,0,0,0.1);
      user-select: none;
    }

    .header h1 {
      font-size: 28px;
      font-weight: 700;
      letter-spacing: 0.03em;
    }

    /* UPDATE PRODUCT FORM */
    .update-product {
      max-width: 700px;
      margin: 0 auto;
      background: white;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .update-product h1.heading {
      text-align: center;
      margin-bottom: 25px;
      color: #444;
      font-weight: 700;
      font-size: 26px;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 18px;
    }

    .update-product img {
      max-width: 220px;
      max-height: 220px;
      object-fit: cover;
      display: block;
      margin: 0 auto 20px;
      border-radius: 8px;
      box-shadow: 0 0 8px rgba(0,0,0,0.12);
      transition: box-shadow 0.3s ease;
    }
    .update-product img:hover {
      box-shadow: 0 0 15px #ff914d;
    }

    label, span {
      font-weight: 600;
      color: #555;
      font-size: 16px;
      user-select: none;
    }

    input.box, select.box {
      width: 100%;
      padding: 12px 15px;
      font-size: 16px;
      border: 1.5px solid #ccc;
      border-radius: 6px;
      outline-offset: 2px;
      transition: border-color 0.3s ease;
      font-family: inherit;
    }
    input.box:focus, select.box:focus {
      border-color: #ff914d;
      box-shadow: 0 0 6px #ff914daa;
    }

    /* Buttons container */
    .flex-btn {
      display: flex;
      gap: 15px;
      margin-top: 25px;
    }

    /* Buttons styling */
    .btn, .option-btn {
      flex: 1;
      padding: 14px 0;
      font-weight: 700;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
      text-align: center;
      text-decoration: none;
      user-select: none;
      border: none;
      transition: background-color 0.3s ease;
      color: white;
    }

    .btn {
      background-color: #2ecc71;
      box-shadow: 0 4px 10px rgba(46, 204, 113, 0.4);
    }
    .btn:hover {
      background-color: #27ae60;
      box-shadow: 0 6px 14px rgba(39, 174, 96, 0.6);
    }

    .option-btn {
      background-color: #3498db;
      box-shadow: 0 4px 10px rgba(52, 152, 219, 0.4);
      display: inline-block;
      line-height: normal;
    }
    .option-btn:hover {
      background-color: #2980b9;
      box-shadow: 0 6px 14px rgba(41, 128, 185, 0.6);
    }

    /* Responsive */
    @media (max-width: 768px) {
      .sidebar:hover {
        width: 60px;
        align-items: center;
        padding-left: 0;
      }

      .sidebar:hover ul li a span {
        display: none;
      }

      .main {
        margin-left: 60px;
        padding: 20px;
      }

      .update-product {
        padding: 20px;
        max-width: 100%;
      }
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <nav class="sidebar" aria-label="Sidebar navigation">
    <div class="logo-container">
      <img src="../images/CSC264 LOGO 1.png" alt="Apricot Logo" class="logo-img" draggable="false" />
    </div>
    <ul>
      <li><a href="indexadmin.php" class="active" aria-current="page"><i class="fas fa-chart-bar" aria-hidden="true"></i><span>Dashboard</span></a></li>
      <li><a href="admin_menu.php"><i class="fas fa-edit" aria-hidden="true"></i><span>Menu</span></a></li>
      <li><a href="admin_sales.php"><i class="fas fa-dollar-sign" aria-hidden="true"></i><span>Sales</span></a></li>
      <li><a href="#"><i class="fas fa-right-from-bracket" aria-hidden="true"></i><span>Logout</span></a></li>
    </ul>
  </nav>

  <!-- Main Content -->
<main class="main" role="main">
  <header class="header">
    <h1>Add New Menu</h1>
  </header>

  <section class="update-product" aria-label="Update Product Form">
    <h1 class="heading">Add New Menu</h1>
<form action="" method="POST" enctype="multipart/form-data">
  <div class="form-group">
    <label for="menuName">Menu Name:</label>
    <input type="text" id="menuName" name="menuName" class="box" required>
  </div>

  <div class="form-group">
    <label for="menuPrice">Menu Price (RM):</label>
    <input type="number" step="0.01" id="menuPrice" name="menuPrice" class="box" required>
  </div>

  <div class="form-group">
    <label for="categoryId">Category</label>
    <select name="categoryId" id="categoryId" class="box" required>
      <option selected value="<?= htmlspecialchars($product['categoryId'] ?? ''); ?>">
        <?= isset($product['categoryId']) ? ucfirst(htmlspecialchars($product['categoryId'])) : '-- Select Category --'; ?>
      </option>
      <option value="1">Malay</option>
      <option value="2">Western</option>
      <option value="3">Korean</option>
      <option value="4">Drinks</option>
    </select>
  </div>

  <div class="form-group">
    <label for="menuDesc">Menu Description:</label>
    <input type="text" id="menuDesc" name="menuDesc" class="box" required>
  </div>

  <div class="form-group">
    <label for="menuPic">Upload Menu Picture:</label>
    <input type="file" id="menuPic" name="menuPic" class="box" accept="image/*" required>
  </div>

  <div class="flex-btn">
    <input type="submit" value="Add" class="btn" name="add" />
    <a href="admin_menu.php" class="option-btn" role="button">Go back</a>
  </div>
</form>

  </section>
</main>

  <script>
    const imgInput = document.getElementById('image');
    const productImg = document.getElementById('productImg');

    imgInput.addEventListener('change', function (e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (event) {
          productImg.src = event.target.result;
        };
        reader.readAsDataURL(file);
      }
    });
  </script>

</body>
</html>
