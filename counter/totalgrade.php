<?php

    $servername="localhost";
    $uname="root";
    $pass="";
    $database="feesys";

    $conn=mysqli_connect($servername,$uname,$pass,$database);

if(!$conn){
    die("Connection Failed");
}

    $sql = "SELECT * FROM grade where delete_status='0'";
    $query = $conn->query($sql);
    echo "$query->num_rows";
?>