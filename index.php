<?php $page='dashboard';
include("php/dbconnect.php");
include("php/checklogin.php");


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fees Management System For SSB college - Kommadikottai</title>

    <!-- BOOTSTRAP STYLES-->
    <link href="css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="css/font-awesome.css" rel="stylesheet" />
       <!--CUSTOM BASIC STYLES-->
    <link href="css/basic.css" rel="stylesheet" />
    <!--CUSTOM MAIN STYLES-->
    <link href="css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

    


</head>
<?php
include("php/header.php");
?>
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-head-line">Dashboard</h1>
                        </div>
                </div>
<div class="container">
<div class="dashboard-box child-box">
        <a href="download.php">
            <i class="fa fa-database fa-5x"></i>
            <h4>Backup Data</h4>
            <p>Download the Backup File</p>
        </a>
    </div>
    <div class="dashboard-box ">
        <a href="student.php">
            <i class="fa fa-users fa-5x"></i>
            <h4>Total Students: <?php include 'counter/stucount.php'?></h4>
            <p>Manage Students</p>
        </a>
    </div>

    <div class="dashboard-box ">
        <a href="fees.php">
            <i class="fa fa-rupee fa-5x"></i>
            <h4>Today's Collection:<?php include 'counter/todayearncount.php'?></h4>
            <p>Collect Fees</p>
        </a>
    </div>

    <?php if ($_SESSION['typeUser'] == "admin") { ?>
        <div class="dashboard-box ">
            <a href="class.php">
                <i class="fa fa-th-large fa-5x"></i>
                <h4>Available Courses: <?php include 'counter/totalgrade.php'?></h4>
                <p>Course Fees Management</p>
            </a>
        </div>
    <?php } ?>

    <div class="dashboard-box">
        <a>
            <i class="fa fa-toggle-on fa-5x"></i>
            <h4>Total Students Studying: <?php include 'counter/activecount.php'?></h4>
            <p>Active Students</p>
        </a>
    </div>

    <div class="dashboard-box ">
        <a href="report.php">
            <i class="fa fa-file-text-o fa-5x"></i>
            <h4>Total Collection: <?php include 'counter/totalearncount.php'?></h4>
            <p>View Reports</p>
        </a>
    </div>

    <?php if ($_SESSION['typeUser'] == "admin") { ?>
        <div class="dashboard-box">
            <a href="inactivestd.php">
                <i class="fa fa-graduation-cap fa-5x"></i>
                <h4>Passed-out Students: <?php include 'counter/inactivecount.php'?></h4>
                <p>Inactive Students</p>
            </a>
        </div>
    
    <div class="dashboard-box">
        <a href="discont_list.php">
            <i class="fa fa-toggle-off fa-5x"></i>
            <h4>Total Discontinued Students: <?php include 'counter/discontcount.php'?></h4>
            <p>Discontinue Students</p>
        </a>
    </div>
    <?php } ?>
    <div class="dashboard-box">
        <a href="discont_list.php">
            <i class="fa fa-check-circle fa-5x"></i>
            <h4>Full Fees Paid Students: <?php include 'counter/paidstdcount.php'?></h4>
            <p>Fees Paid Students</p>
        </a>
    </div>
    <?php
    include("dchart.php");
    ?>
</div>
            
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->

    
   
   <script src="js/jquery-1.10.2.js"></script>	
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="js/bootstrap.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="js/jquery.metisMenu.js"></script>
       <!-- CUSTOM SCRIPTS -->
    <script src="js/custom1.js"></script>
    

    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f7f6;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 100%;
        margin: 20px auto;
        padding: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center; /* Align items horizontally to the center */
        align-items: center; /* Align items vertically to the center */
    }

    .dashboard-box {
        width: 280px;
        height: 220px;
        background-color: #eafafe;
        border: 1px solid #2fd5fe; /* Set border color and style */
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
        /* margin-right: 20px;
        margin-right: -1px; */
    }

    .child-box {
        /* margin: 20px; */
        /* margin-top: -1px; */
        margin-left: -20px;
        /* gap: 20px; */
        
    }

    .dashboard-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .dashboard-box a {
        text-decoration: none;
        color: inherit;
    }

    .dashboard-box h4 {
        margin-top: 10px;
        font-size: 24px;
        color: #333;
    }

    .dashboard-box p {
        margin-top: 5px;
        font-size: 16px;
        color: #777;
    }

    @media (max-width: 768px) {
        .dashboard-box {
            width: calc(50% - 20px);
            height: auto;
        }
    }

    @media (max-width: 480px) {
        .dashboard-box {
            width: 100%;
            margin-right: 0;
        }
    }
</style>
</body>
</html>
