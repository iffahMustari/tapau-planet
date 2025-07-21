<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('connection.php');

// Bila form dihantar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['user'])) {
        $userId = $_SESSION['user']['id'];
        $serviceRating = intval($_POST["feedbackText"]);
        $feedbackText = trim($_POST["feedbackText"]);

        $stmt = $conn->prepare("INSERT INTO `feedback` (feedbackId, feedbackText, serviceRating, feedbackDate) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("isi", $userId, $feedbackText, $serviceRating);

        if ($stmt->execute()) {
            echo "<script>
                alert('Thank you for your feedback!');
                window.location.href = 'feedback.php?success=1';
            </script>";
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "<script>
            alert('Feedback is only available for registered users.');
            window.location.href = 'feedback.php';
        </script>";
        exit();
    }
}

$isLoggedIn = isset($_SESSION['user']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Feedback</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap">
    <link rel="stylesheet" href="css/sFeedback.css">
</head>
<body class="feedback">

<?php if (isset($_GET['success'])): ?>
<script>
    alert('Thank you for your feedback!');
    window.scrollTo({ top: 0, behavior: 'smooth' });
</script>
<?php endif; ?>

<div class="feedback-container">
    <h2>Submit Your Feedback</h2>
    <?php if (!$isLoggedIn): ?>
    <div style="color: red; text-align: center; font-weight: bold; margin-bottom: 15px;">
        Please log in to submit feedback.
    </div>
<?php endif; ?>
    <form method="post" <?= !$isLoggedIn ? 'onsubmit="return false;"' : '' ?>>
        <div class="starwidget">
            <?php for ($i = 5; $i >= 1; $i--): ?>
                <input type="radio" name="feedback" id="feedback<?= $i ?>" value="<?= $i ?>" <?= !$isLoggedIn ? 'disabled' : '' ?>>
                <label for="feedback<?= $i ?>" title="<?= $i ?> Star">&#9733;</label>
            <?php endfor; ?>
        </div>
        <textarea 
            name="comment" 
            placeholder="<?= $isLoggedIn ? 'Write your feedback...' : 'Write your feedback...' ?>" 
            <?= !$isLoggedIn ? 'disabled onfocus="alert(\'Please log in to write your feedback....\')"' : '' ?> 
            required
        ></textarea>
        <button type="submit" <?= !$isLoggedIn ? 'disabled' : '' ?>>Submit Feedback</button>
    </form>
</div>

</body>
</html>
