<?php
$showModal = isset($_GET['showModal']) && $_GET['showModal'] == 1;
?>
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
        body.index {
            background: url('images/bg.jpg') no-repeat center center fixed;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }
    </style>
</head>

<body class="index">

    <?php include('header.php'); ?>

     <div class="clearfix"></div>
<div class="main-header">
    <span class="header-title">
        Follow the Aroma<br>
        <span class="header-subtitle">VISIT US TODAY</span>
    </span>
</div>
<div class="clearfix"></div>

    <!-- fOOD sEARCH Section Starts Here -->

    <div class="color-gap"></div>
<!-- Categories Section Starts Here -->
<section class="categories">
    <div class="container">
        <h2 class="text-center">Explore Foods</h2>

        <div class="box-3 float-container">
            <img src="images/nasi_lemak.jpg" alt="Nasi Lemak" class="img-responsive img-curve">
            <h3 class="category-title">Malay</h3>
        </div>

        <div class="box-3 float-container">
            <img src="images/burger.jpg" alt="Burger" class="img-responsive img-curve">
            <h3 class="category-title">Western</h3>
        </div>

        <div class="box-3 float-container">
            <img src="images/bibimbap.jpg" alt="bibimbap" class="img-responsive img-curve">
            <h3 class="category-title">Korean</h3>
        </div>

        <div class="box-3 float-container">
            <img src="images/drink.png" alt="drink" class="img-responsive img-curve">
            <h3 class="category-title">Drinks</h3>
        </div>

        <div class="clearfix"></div>
        
    </div>
</section>
<div class="color-gap"></div>

    <!-- Categories Section Ends Here -->

    <section id="menu"></section>


        <?php include('menuIndex.php'); ?>

        <?php include('view_feedback.php'); ?>
        <div class="color-gap"></div>

        <?php include('submit_feedback.php'); ?>
        <div class="color-gap"></div>

        <?php include('footer.php'); ?>

        

        <script src="js/script.js"></script>
        <?php include 'chat_widget.php'; ?>



</body>

<script>
  const showModal = <?= $showModal ? 'true' : 'false' ?>;

  if (showModal) {
    // 1. Buka modal
    const modal = document.getElementById("orderModal");
    if (modal) modal.style.display = "block";

    // 2. Scroll ke menuIndex lepas 300ms
    setTimeout(() => {
      const target = document.getElementById("menuIndex");
      if (target) target.scrollIntoView({ behavior: "smooth" });
    }, 300);

    // 3. Remove query from URL
    history.replaceState({}, document.title, window.location.pathname);
  }
</script>

</html>

