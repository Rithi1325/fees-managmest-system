<?php
//error_reporting(0);
ob_start();
session_start();
// $siteName = "ssb.com";

//DEFINE("BASE_URL","http://cipetbhopal.com/");
// DEFINE("BASE_URL","http://localhost/SSBFeesSystem/");

DEFINE('DB_USER', 'if0_36719879');
DEFINE('DB_PSWD', 'LhzNgwmB3BoXd0Z');
DEFINE('DB_HOST', 'sql110.infinityfree.com');
DEFINE('DB_NAME', 'if0_36719879_feesys'); 

date_default_timezone_set('Asia/Calcutta'); 
$conn =  new mysqli(DB_HOST,DB_USER,DB_PSWD,DB_NAME);
if($conn->connect_error)
die("Failed to connect database ".$conn->connect_error );
