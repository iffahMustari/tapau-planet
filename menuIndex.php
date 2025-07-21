<?php
include 'connection.php';
?>

<!-- FOOD MENU Section Starts Here -->
<section class="food-menu">
  <div class="container">
    <h2 class="text-center">Food Menu</h2>

    <!-- Tab Links -->
    <div class="tab" style="display: flex; justify-content: center; gap: 10px;">
      <button class="tablinks" data-tab="Malay" onclick="openMenuTab(event, 'Malay')" id="defaultOpen">Malay</button>
      <button class="tablinks" data-tab="Western" onclick="openMenuTab(event, 'Western')">Western</button>
      <button class="tablinks" data-tab="Korean" onclick="openMenuTab(event, 'Korean')">Korean</button>
      <button class="tablinks" data-tab="Drinks" onclick="openMenuTab(event, 'Drinks')">Drinks</button>
    </div>

    <!-- MALAY -->
    <div id="Malay" class="tabcontent" style="display:block">
      <div class="menu-container">
        <?php
        $categoryId = 1;
        $sql = "SELECT * FROM menu WHERE categoryId = ? LIMIT 6";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $categoryId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if ($res && mysqli_num_rows($res) > 0) {
          echo '<div style="display: flex; flex-wrap: wrap; justify-content: center;">';
          while ($row = mysqli_fetch_assoc($res)) {
            $menuId = $row['menuId'];
            $menuName = $row['menuName'];
            $menuPrice = $row['menuPrice'];
            $menuDesc = $row['menuDesc'];
            $menuPic = $row['menuPic'];
            echo '
              <div class="food-menu-box" style="width: 32%; margin: 1%; box-sizing: border-box;">
                <div class="food-menu-img">
                  <img src="images/' . $menuPic . '" alt="' . $menuName . '" class="img-responsive img-curve" style="width: 100%; height: 200px; object-fit: cover;">
                </div>
                <div class="food-menu-desc">
                  <h4>' . $menuName . '</h4>
                  <p class="food-price">RM ' . number_format($menuPrice, 2) . '</p>
                  <p class="food-detail">' . $menuDesc . '</p>
                  <br>
                  <a href="menu.php?menuId=' . $menuId . '&menuName=' . urlencode($menuName) . '&menuPrice=' . $menuPrice . '&menuPic=' . $menuPic . '" 
                     class="btn btn-primary">Order Now</a>
                </div>
              </div>';
          }
          echo '</div>';
        } else {
          echo "<div class='error'>No Malay food available.</div>";
        }
        ?>
      </div>
    </div>

    <!-- WESTERN -->
    <div id="Western" class="tabcontent" style="display:none">
      <div class="menu-container">
        <?php
        $categoryId = 2;
        $sql = "SELECT * FROM menu WHERE categoryId = ? LIMIT 6";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $categoryId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if ($res && mysqli_num_rows($res) > 0) {
          echo '<div style="display: flex; flex-wrap: wrap; justify-content: center;">';
          while ($row = mysqli_fetch_assoc($res)) {
            $menuId = $row['menuId'];
            $menuName = $row['menuName'];
            $menuPrice = $row['menuPrice'];
            $menuDesc = $row['menuDesc'];
            $menuPic = $row['menuPic'];
            echo '
              <div class="food-menu-box" style="width: 32%; margin: 1%; box-sizing: border-box;">
                <div class="food-menu-img">
                  <img src="images/' . $menuPic . '" alt="' . $menuName . '" class="img-responsive img-curve" style="width: 100%; height: 200px; object-fit: cover;">
                </div>
                <div class="food-menu-desc">
                  <h4>' . $menuName . '</h4>
                  <p class="food-price">RM ' . number_format($menuPrice, 2) . '</p>
                  <p class="food-detail">' . $menuDesc . '</p>
                  <br>
                  <a href="menu.php?menuId=' . $menuId . '&menuName=' . urlencode($menuName) . '&menuPrice=' . $menuPrice . '&menuPic=' . $menuPic . '" 
                     class="btn btn-primary">Order Now</a>
                </div>
              </div>';
          }
          echo '</div>';
        } else {
          echo "<div class='error'>No Western food available.</div>";
        }
        ?>
      </div>
    </div>

    <!-- KOREAN -->
    <div id="Korean" class="tabcontent" style="display:none">
      <div class="menu-container">
        <?php
        $categoryId = 3;
        $sql = "SELECT * FROM menu WHERE categoryId = ? LIMIT 6";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $categoryId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if ($res && mysqli_num_rows($res) > 0) {
          echo '<div style="display: flex; flex-wrap: wrap; justify-content: center;">';
          while ($row = mysqli_fetch_assoc($res)) {
            $menuId = $row['menuId'];
            $menuName = $row['menuName'];
            $menuPrice = $row['menuPrice'];
            $menuDesc = $row['menuDesc'];
            $menuPic = $row['menuPic'];
            echo '
              <div class="food-menu-box" style="width: 32%; margin: 1%; box-sizing: border-box;">
                <div class="food-menu-img">
                  <img src="images/' . $menuPic . '" alt="' . $menuName . '" class="img-responsive img-curve" style="width: 100%; height: 200px; object-fit: cover;">
                </div>
                <div class="food-menu-desc">
                  <h4>' . $menuName . '</h4>
                  <p class="food-price">RM ' . number_format($menuPrice, 2) . '</p>
                  <p class="food-detail">' . $menuDesc . '</p>
                  <br>
                  <a href="menu.php?menuId=' . $menuId . '&menuName=' . urlencode($menuName) . '&menuPrice=' . $menuPrice . '&menuPic=' . $menuPic . '" 
                     class="btn btn-primary">Order Now</a>
                </div>
              </div>';
          }
          echo '</div>';
        } else {
          echo "<div class='error'>No Korean food available.</div>";
        }
        ?>
      </div>
    </div>

    <!-- DRINKS -->
    <div id="Drinks" class="tabcontent" style="display:none">
      <div class="menu-container">
        <?php
        $categoryId = 4;
        $sql = "SELECT * FROM menu WHERE categoryId = ? LIMIT 6";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $categoryId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if ($res && mysqli_num_rows($res) > 0) {
          echo '<div style="display: flex; flex-wrap: wrap; justify-content: center;">';
          while ($row = mysqli_fetch_assoc($res)) {
            $menuId = $row['menuId'];
            $menuName = $row['menuName'];
            $menuPrice = $row['menuPrice'];
            $menuDesc = $row['menuDesc'];
            $menuPic = $row['menuPic'];
            echo '
              <div class="food-menu-box" style="width: 32%; margin: 1%; box-sizing: border-box;">
                <div class="food-menu-img">
                  <img src="images/' . $menuPic . '" alt="' . $menuName . '" class="img-responsive img-curve" style="width: 100%; height: 200px; object-fit: cover;">
                </div>
                <div class="food-menu-desc">
                  <h4>' . $menuName . '</h4>
                  <p class="food-price">RM ' . number_format($menuPrice, 2) . '</p>
                  <p class="food-detail">' . $menuDesc . '</p>
                  <br>
                  <a href="menu.php?menuId=' . $menuId . '&menuName=' . urlencode($menuName) . '&menuPrice=' . $menuPrice . '&menuPic=' . $menuPic . '" 
                     class="btn btn-primary">Order Now</a>
                </div>
              </div>';
          }
          echo '</div>';
        } else {
          echo "<div class='error'>No Drinks available.</div>";
        }
        ?>
      </div>
    </div>

    <div class="clearfix"></div>
    <p class="text-center">
      <a href="menu.php" class="btn btn-primary">See All Foods</a>
    </p>
  </div>
</section>

<script>
  function openMenuTab(evt, tabName) {
    const tabcontent = document.getElementsByClassName("tabcontent");
    const tablinks = document.getElementsByClassName("tablinks");

    for (let i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }

    for (let i = 0; i < tablinks.length; i++) {
      tablinks[i].classList.remove("active");
    }

    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.classList.add("active");
  }

  document.getElementById("defaultOpen").click();
</script>

<link rel="stylesheet" href="css/menuIndex.css">
