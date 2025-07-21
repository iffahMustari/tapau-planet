<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('connection.php');

$result = mysqli_query($conn, "
    SELECT feedback.*, users.userName 
    FROM feedback 
    JOIN users ON feedback.userId = users.userId 
    ORDER BY feedback.feedbackDate DESC
") or die("Something went wrong."); // Avoid exposing raw SQL errors
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Feedback - Tapau Planet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Styles -->
    <link rel="stylesheet" href="css/vFeedback.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body>

<section class="review" id="review">
    <div class="review">
        <div class="subheading">Feedback</div>
        <div class="heading">View Feedback</div>
    </div>

    <div class="review-slider">
        <div class="wrapper">
            <i class="fa fa-arrow-left prev"></i>

            <div class="slides">
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <div class="user-slide">
                            <div class="user">
                                <div class="userinfo">
                                    <h3><?= htmlspecialchars($row['userName']) ?></h3>
                                    <div class="start">
                                        <?php
                                        $stars = intval($row['serviceRating']);
                                        for ($i = 1; $i <= 5; $i++) {
                                            echo '<i class="fa fa-star' . ($i <= $stars ? '' : '-o') . '"></i>';
                                        }
                                        ?>
                                    </div>
                                    <small><?= date('d M Y, h:i A', strtotime($row['feedbackDate'])) ?></small>
                                </div>
                                <p><?= nl2br(htmlspecialchars($row['feedbackText'])) ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="text-align: center;">No feedback found.</p>
                <?php endif; ?>
            </div>

            <i class="fa fa-arrow-right next"></i>
        </div>
    </div>
</section>

<!-- JavaScript for slider -->
<script>
    const slides = document.querySelectorAll('.user-slide');
    const nextBtn = document.querySelector('.next');
    const prevBtn = document.querySelector('.prev');
    const slidesWrapper = document.querySelector('.slides');

    let currentIndex = 0;
    let autoplayInterval;

    function showSlide(index) {
        const offset = -index * 100;
        slidesWrapper.style.transform = `translateX(${offset}%)`;
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % slides.length;
        showSlide(currentIndex);
    }

    function prevSlide() {
        currentIndex = (currentIndex - 1 + slides.length) % slides.length;
        showSlide(currentIndex);
    }

    function startAutoplay() {
        autoplayInterval = setInterval(nextSlide, 4000);
    }

    function stopAutoplay() {
        clearInterval(autoplayInterval);
    }

    nextBtn.addEventListener('click', () => {
        stopAutoplay();
        nextSlide();
    });

    prevBtn.addEventListener('click', () => {
        stopAutoplay();
        prevSlide();
    });

    showSlide(currentIndex);
    startAutoplay();
</script>

</body>
</html>
