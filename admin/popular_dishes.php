<?php
$conn = new mysqli("localhost", "root", "", "tapauplanet");

if ($conn->connect_error) {
  echo '<div class="alert alert-danger">Database connection failed.</div>';
} else {
  $sql = "SELECT m.menuName, m.menuPic, m.menuPrice, SUM(om.quantity) AS totalOrdered
          FROM ordermenu om
          JOIN menu m ON om.menuId = m.menuId
          GROUP BY om.menuId
          ORDER BY totalOrdered DESC
          LIMIT 4";

  $result = $conn->query($sql);

  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo '<div class="col-md-6 mb-3">';
      echo '  <div class="d-flex align-items-center">';
      echo '    <img src="images/' . htmlspecialchars($row['menuPic']) . '" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;" alt="' . htmlspecialchars($row['menuName']) . '">';
      echo '    <div class="ms-3">';
      echo '      <h6>' . htmlspecialchars($row['menuName']) . '</h6>';
      echo '      <p class="mb-0">RM ' . number_format($row['menuPrice'], 2) . ' | Ordered: ' . $row['totalOrdered'] . 'x</p>';
      echo '    </div>';
      echo '  </div>';
      echo '</div>';
    }
  } else {
    echo '<div class="col-12"><p>No popular dishes found.</p></div>';
  }
  $conn->close();
}
?>
