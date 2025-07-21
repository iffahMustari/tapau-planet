<?php // footer.php ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Creative Footer</title>
<link rel="stylesheet" href="css/footer.css" />
<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
</head>
<body>

<footer id="footer">
  <div class="footer-container">
    <!-- Logo & Description -->
    <div class="footer-section">
      <div class="footer-logo">
        <img src="images/CSC264 LOGO 1.png" alt="CSC264 Logo" style="height:190px;">
      </div>
      <p class="footer-description">
        Enjoy the convenience of ordering your favorite food online. Easy, fast, and secure with Tapau Planet!
      </p>
    </div>

    <!-- Navigation Links -->
    <div class="footer-section">
      <h3 class="footer-heading">Explore</h3>
      <ul class="footer-links">
        <li><a href="#">Home</a></li>
        <li><a href="#">Portfolio</a></li>
        <li><a href="#">Blog</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
    </div>

    <!-- Social Media -->
    <div class="footer-section">
      <h3 class="footer-heading">Follow Us</h3>
      <ul class="social-icons">
        <li>
          <a href="#" aria-label="Instagram" title="Instagram">
            <i class="fab fa-instagram"></i>
          </a>
        </li>
        <li>
          <a href="#" aria-label="Twitter" title="Twitter">
            <i class="fab fa-twitter"></i>
          </a>
        </li>
        <li>
          <a href="tel:+60123456789" aria-label="Phone" title="Phone">
            <i class="fas fa-phone"></i>
          </a>
        </li>
      </ul>
    </div>

    <!-- Newsletter -->
    <div class="footer-section newsletter">
      <h3 class="footer-heading">Newsletter</h3>
      <form>
        <input type="email" placeholder="Your email address" required />
        <button type="submit">Subscribe</button>
      </form>
    </div>

    <!-- Feedback Actions -->
    <!-- <div class="footer-section feedback-actions">
      <a href="submit_feedback.php" class="btn">Submit Feedback</a>
      <a href="view_feedback.php" class="btn">View Feedback</a>
    </div> -->
  </div>

  <div class="footer-bottom">
    &copy; 2025 Tapau Planet. All rights reserved.
  </div>

</footer>

</body>
</html>
