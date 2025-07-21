<?php include('admin/connection.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Important to make website responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tapau Planet</title>

    <!-- Link our CSS file -->
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: url('images/bg.png') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>

<body>
    <!-- Navbar Section Starts Here -->
    <section class="navbar">
        <div class="container">
            <div class="logo">
                <a href="#" title="Logo">
                    <img src="images/CSC264 LOGO 1.png" alt="Restaurant Logo" class="img-responsive">
                </a>
            </div>

            <div class="menu text-right">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="menu.php">Categories</a></li>
                    <li><a href="food.php">Foods</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>

    <!-- fOOD SEARCH Section -->
    <section class="food-search text-center">
        <div class="container">
            <form action="food.php" method="POST">
                <input type="search" name="search" placeholder="Search for Food.." required>
                <input type="submit" name="submit" value="Search" class="btn btn-primary">
            </form>
        </div>
    </section>

    <!-- CAtegories Section -->
    <section class="categories">
        <div class="container">
            <h2 class="text-center">Explore Foods</h2>

            <a href="category-foods.html">
                <div class="box-3 float-container">
                    <img src="images/nasi-lemak.jpg" alt="Nasi Lemak" class="img-responsive img-curve">
                    <h3 class="float-text text-white" style="margin-left: -40px;">Nasi Lemak</h3>
                </div>
            </a>

            <a href="category-foods.html">
                <div class="box-3 float-container">
                    <img src="images/burger.jpg" alt="Burger" class="img-responsive img-curve">
                    <h3 class="float-text text-white" style="margin-left: -40px;">Burger</h3>
                </div>
            </a>

            <a href="category-foods.html">
                <div class="box-3 float-container">
                    <img src="images/bibimbap.jpg" alt="bibimbap" class="img-responsive img-curve">
                    <h3 class="float-text text-white" style="margin-left: -40px;">Bibimbap</h3>
                </div>
            </a>

            <a href="category-foods.html">
                <div class="box-3 float-container">
                    <img src="images/drink.png" alt="drink" class="img-responsive img-curve">
                    <h3 class="float-text text-white" style="margin-left: -40px;">Drinks</h3>
                </div>
            </a>

            <div class="clearfix"></div>
        </div>
    </section>

    <!-- Food Menu Section -->
    <section class="food-menu">
        <div class="container">
            <h2 class="text-center">Food Menu</h2>

            <?php
            $categories = [
                'Malay' => 1,
                'Western' => 2,
                'Korean' => 3,
                'Drinks' => 4
            ];

            foreach ($categories as $catName => $catId) {
                echo "<h3 class='text-left mt-4'>$catName</h3>";

                $stmt = $conn->prepare("SELECT menuId, menuName, menuPrice, menuPic, menuDesc FROM menu WHERE categoryId = ?");
                $stmt->bind_param("i", $catId);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $menuId = $row['menuId'];
                    $menuName = htmlspecialchars($row['menuName']);
                    $menuPrice = number_format($row['menuPrice'], 2);
                    $menuPic = htmlspecialchars($row['menuPic']);
                    $menuDesc = htmlspecialchars($row['menuDesc']);

                    echo '<div class="food-menu-box">';
                    echo '<div class="food-menu-img">';
                    echo "<img src='images/$menuPic' alt='$menuName' class='img-responsive img-curve'>";
                    echo '</div>';
                    echo '<div class="food-menu-desc">';
                    echo "<h4>$menuName</h4>";
                    echo "<p class='food-price'>RM $menuPrice</p>";
                    echo "<p class='food-detail'>$menuDesc</p>";
                    echo '<br>';
                    echo "<a href='order.php?menuId=$menuId&menuName=$menuName&menuPrice=$menuPrice&menuPic=$menuPic' class='btn btn-primary'>Order Now</a>";
                    echo '</div>';
                    echo '</div>';
                }
            }

            $conn->close();
            ?>
            <div class="clearfix"></div>
        </div>
        <p class="text-center">
            <a href="#">See All Foods</a>
        </p>
    </section>

    <!-- Social Section -->
    <section class="social">
        <div class="container text-center">
            <ul>
                <li><a href="#"><img src="https://img.icons8.com/fluent/50/000000/facebook-new.png"/></a></li>
                <li><a href="#"><img src="https://img.icons8.com/fluent/48/000000/instagram-new.png"/></a></li>
                <li><a href="#"><img src="https://img.icons8.com/fluent/48/000000/twitter.png"/></a></li>
            </ul>
        </div>
    </section>

    <!-- Footer -->
    <section class="footer">
        <div class="container text-center">
            <p>All rights reserved. Designed By <a href="#">Tapau Planet</a></p>
        </div>
    </section>
</body>
</html>
