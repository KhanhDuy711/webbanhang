<?php
// get_voucher_percent.php
include "connect.php";

if(isset($_GET['vid'])) {
    $vid = $_GET['vid'];
    
    $query = "SELECT vpercent FROM voucher WHERE vid = $vid";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $vpercent = $row['vpercent'];
        echo $vpercent;
    } else {
        echo "0";
    }
}
?>
