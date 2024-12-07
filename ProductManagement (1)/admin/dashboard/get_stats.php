<?php
include "Connect.php";

$startDate = $_GET['start_date'] ?? date('Y-m-d');
$endDate = $_GET['end_date'] ?? date('Y-m-d');

$filteredQuery = "SELECT COUNT(*) as orderCount, SUM(rmoney) as totalRevenue FROM receipt WHERE DATE(rdate) BETWEEN '$startDate' AND '$endDate' and rstatus = 'Hoàn thành'";

$filteredResult = $connect->query($filteredQuery)->fetch_assoc();

header('Content-Type: application/json');
echo json_encode($filteredResult);

$connect->close();
?>