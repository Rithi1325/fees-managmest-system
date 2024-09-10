<?php

    $servername="localhost";
    $uname="root";
    $pass="";
    $database="feesys";

    $conn=mysqli_connect($servername,$uname,$pass,$database);

if(!$conn){
    die("Connection Failed");
}
    $time=new DateTime('now');
    $today = $time->format('Y-m-d');
    $sql = "SELECT SUM( paid) FROM fees_transaction where submitdate='$today'";
        $amountsum = mysqli_query($conn, $sql) or die(mysqli_error($sql));
        $row_amountsum = mysqli_fetch_assoc($amountsum);
        $totalRows_amountsum = mysqli_num_rows($amountsum);
        // echo $today;
        if($row_amountsum['SUM( paid)']==null){
            $row_amountsum['SUM( paid)']='0';   
        }
        echo 'Rs.' .$row_amountsum['SUM( paid)'];
?>