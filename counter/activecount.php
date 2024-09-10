<?php

    $servername="localhost";
    $uname="root";
    $pass="";
    $database="feesys";

    $conn=mysqli_connect($servername,$uname,$pass,$database);

if(!$conn){
    die("Connection Failed");
}

$sql = "SELECT COUNT(DISTINCT roll_no) AS distinct_count FROM student WHERE delete_status = '0'";
$query = $conn->query($sql);

if ($query) {
    $row = $query->fetch_assoc();
    echo $row['distinct_count']; // Output the count of distinct roll numbers
} else {
    echo "Error: " . $conn->error;
}
?>