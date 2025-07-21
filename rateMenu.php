<?php
// rate_menu.php
if (!isset($_GET['menuId']) || !isset($_GET['orderId'])) {
  echo "Invalid access.";
  exit;
}

$menuId = $_GET['menuId'];
$orderId = $_GET['orderId'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Rate Menu</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f2f2f2;
      margin: 0;
      padding: 30px;
    }

    .container {
      background: #fff;
      max-width: 500px;
      margin: 0 auto;
      padding: 25px 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
    }

    select, textarea, input[type="submit"], a.button {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }

    input[type="submit"] {
      background-color: #007bff;
      color: white;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover {
      background-color: #0056b3;
    }

    a.button {
      display: inline-block;
      text-align: center;
      text-decoration: none;
      background-color: #6c757d;
      color: white;
      border: none;
    }

    a.button:hover {
      background-color: #5a6268;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Rate This Menu</h2>
    <form method="POST" action="submit_rating.php">
      <input type="hidden" name="menuId" value="<?= htmlspecialchars($menuId) ?>">
      <input type="hidden" name="orderId" value="<?= htmlspecialchars($orderId) ?>">

      <label for="rating">Rating (1 to 5):</label>
      <select name="rating" id="rating" required>
        <option value="1">⭐ 1</option>
        <option value="2">⭐ 2</option>
        <option value="3">⭐ 3</option>
        <option value="4">⭐ 4</option>
        <option value="5">⭐ 5</option>
      </select>

      <label for="comment">Comment (optional):</label>
      <textarea name="comment" id="comment" rows="3" placeholder="Your feedback..."></textarea>

      <input type="submit" value="Submit Rating">
      <a href="index.php" class="button">Cancel</a>
    </form>
  </div>
</body>
</html>
