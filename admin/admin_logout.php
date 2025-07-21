<?php
session_start();
session_destroy();

// Papar mesej dan redirect ke login
echo "<script>
    alert('You have been successfully logged out.');
    window.location.href = '../login.php';
</script>";
exit();
?>
