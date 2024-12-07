<?php
// getSearchSuggestions.php

// Connect to your database
include "Connect.php";

// Get the keyword from the request
$keyword = $_REQUEST["keyword"];

// Fetch suggestions from the database or any other source
$sql = "SELECT pname FROM product WHERE pname LIKE '%$keyword%'";
$result = $conn->query($sql);

// Output suggestions as an unordered list
echo '<ul>';
while ($row = $result->fetch_assoc()) {
    echo '<li onclick="selectSuggestion(\'' . $row['pname'] . '\')">' . $row['pname'] . '</li>';
}
echo '</ul>';

// Close the database connection
$conn->close();
?>
