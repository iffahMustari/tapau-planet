<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'tapauplanet_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT 
          DATE_FORMAT(payDate, '%b') AS month, 
          SUM(payAmount) AS total 
        FROM payment 
        WHERE payStatus = 'Paid'
        GROUP BY MONTH(payDate)
        ORDER BY MONTH(payDate)";

$result = $conn->query($sql);

$data = ['labels' => [], 'values' => []];

while ($row = $result->fetch_assoc()) {
  $data['labels'][] = $row['month'];
  $data['values'][] = (float)$row['total'];
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($data);
?>
