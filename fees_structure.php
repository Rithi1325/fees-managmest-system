<?php
$page = 'fees_structure';
include("php/dbconnect.php");
include("php/checklogin.php");
$error = '';
$action = "add";

// Fetch data from the database to display in tables
$busFeesQuery = "SELECT * FROM bus_fees WHERE delete_status='0'";
$busFeesResult = mysqli_query($conn, $busFeesQuery);

$skillFeesQuery = "SELECT * FROM skill_fees  WHERE delete_status='0'";
$skillFeesResult = mysqli_query($conn, $skillFeesQuery);

// Fetch data from the database to display in tables
$vanFeesQuery = "SELECT * FROM van_fees  WHERE delete_status='0'";
$vanFeesResult = mysqli_query($conn, $vanFeesQuery);


if(isset($_REQUEST['act']) && @$_REQUEST['act']=="1")
{
$error = "<div class='alert alert-success'> <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Fees record has been added!</div>";
}else if(isset($_REQUEST['act']) && @$_REQUEST['act']=="2")
{
$error = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Fees record has been updated!</div>";
}
else if(isset($_REQUEST['act']) && @$_REQUEST['act']=="3")
{
$error = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Fees has been deleted!</div>";
}

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
	
	<link href="css/ui.css" rel="stylesheet" />
	<link href="css/datepicker.css" rel="stylesheet" />	
	
    <script src="js/jquery-1.10.2.js"></script>
	
    <script type='text/javascript' src='js/jquery/jquery-ui-1.10.1.custom.min.js'></script>


</head>
<?php
include("php/header.php");
?>
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Fees Management
                <?php
						echo (isset($_GET['action']) && @$_GET['action']=="add1" || @$_GET['action']=="edit1" || @$_GET['action']=="add2" || @$_GET['action']=="edit2" || @$_GET['action']=="add3" || @$_GET['action']=="edit3")?
						' <a href="fees_structure.php" class="btn btn-success btn-sm pull-right" style="border-radius:0%">Go Back </a>':'<a href="fees_record.php" class="btn btn-danger btn-sm pull-right" style="border-radius:0%">Previous Records</a>';
						?>
                </h1>
                <div class="row">
                    <?php
                    echo $error;
                    ?>

                    <link href="css/datatable/datatable.css" rel="stylesheet" />

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Manage Bus Fees
                            <a class="btn btn-success btn-sm pull-right" href="bus_fee.php?action=add" onclick="openAddEntryModal()">Add New Bus Entry</a>
                        </div>
                        <div class="panel-body">
                             <div class="table-sorting table-responsive">

                                <table class="table table-striped table-bordered table-hover" id="tSortable21">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Year</th>
                                    <th>Semester</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Fees</th>
								    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $serialNumber = 1;
                                while ($row = mysqli_fetch_assoc($busFeesResult)) {
                                    echo "<tr>
                                            
                                            <td>{$row['id']}</td>
                                            <td>{$row['year']}</td>
                                            <td>{$row['semester']}</td>
                                            <td>{$row['from_location']}</td>
                                            <td>{$row['to_location']}</td>
                                            <td>{$row['fees']}</td>
                                            <td>
											<a href=\"bus_fee.php?action=edit&id=".$row['id']."\" class='btn btn-success btn-xs' style='border-radius:60px;'><span class='glyphicon glyphicon-edit'></span></a>
											
											<a onclick=\"return confirm('Are you sure you want to delete this record');\" href='bus_fee.php?action=delete&id=".$row['id']."' class='btn btn-danger btn-xs' style='border-radius:60px;'><span class='glyphicon glyphicon-remove'></span></a></td>
                                        </tr>";
                                        $serialNumber++;
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                            </div>

                            <div class="panel panel-default">
                        <div class="panel-heading">
                            Manage van Fees
                            <a class="btn btn-success btn-sm pull-right" href="van_fee.php?action=add" onclick="openAddEntryModal()">Add New Van Entry</a>
                        </div>
                        <div class="panel-body">
                             <div class="table-sorting table-responsive">

                                <table class="table table-striped table-bordered table-hover" id="tSortable22">
                                <thead>
                                <tr>
                                    <th>ID</ht>
                                    <th>Year</th>
                                    <th>Semester</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Fees</th>
								    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $serialNumber = 1;
                                while ($row = mysqli_fetch_assoc($vanFeesResult)) {
                                    echo "<tr>
                                            <td>{$row['id']}</td>
                                            <td>{$row['year']}</td>
                                            <td>{$row['semester']}</td>
                                            <td>{$row['from_location']}</td>
                                            <td>{$row['to_location']}</td>
                                            <td>{$row['fees']}</td>
                                            <td>
											<a href=\"van_fee.php?action=edit&id=".$row['id']."\" class='btn btn-success btn-xs' style='border-radius:60px;'><span class='glyphicon glyphicon-edit'></span></a>
											
											<a onclick=\"return confirm('Are you sure you want to delete this record');\" href='van_fee.php?action=delete&id=".$row['id']."' class='btn btn-danger btn-xs' style='border-radius:60px;'><span class='glyphicon glyphicon-remove'></span></a> </td>
                                        </tr>";
                                        $serialNumber++;
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                            </div>

         <div class="panel panel-default">
                        <div class="panel-heading">
                            Manage Skill Fees
                            <a class="btn btn-success btn-sm pull-right" href="skill_fee.php?action=add" onclick="openAddEntryModal()">Add New Skill Entry</a>
                        </div>
                        <div class="panel-body">
                             <div class="table-sorting table-responsive">

                                <table class="table table-striped table-bordered table-hover" id="tSortable23">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Year</th>
                                    <th>Semester</th>
                                    <th>Skill Name</th>
                                    <th>Fees</th>
								    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $serialNum = 1;
                                while ($row = mysqli_fetch_assoc($skillFeesResult)) {
                                    echo "<tr>
                                            <td>{$row['id']}</td>
                                            <td>{$row['year']}</td>
                                            <td>{$row['semester']}</td>
                                            <td>{$row['skill_name']}</td>
                                            <td>{$row['fees']}</td>
                                            <td>
											<a href=\"skill_fee.php?action=edit&id=".$row['id']."\" class='btn btn-success btn-xs' style='border-radius:60px;'><span class='glyphicon glyphicon-edit'></span></a>
											
											<a onclick=\"return confirm('Are you sure you want to delete this record');\" href='skill_fee.php?action=delete&id=".$row['id']."' class='btn btn-danger btn-xs' style='border-radius:60px;'><span class='glyphicon glyphicon-remove'></span></a> </td>
                                        </tr>";
                                        $serialNum++;
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <script src="js/dataTable/jquery.dataTables.min.js"></script> 
                
<script>
 $(document).ready(function () {
    $('#tSortable21').dataTable({
    "bPaginate": true,
    "bLengthChange": true,
    "bFilter": true,
    "bInfo": true,
    "bAutoWidth": true });

    $('#tSortable22').dataTable({
    "bPaginate": true,
    "bLengthChange": true,
    "bFilter": true,
    "bInfo": true,
    "bAutoWidth": true });

    $('#tSortable23').dataTable({
    "bPaginate": true,
    "bLengthChange": true,
    "bFilter": true,
    "bInfo": true,
    "bAutoWidth": true });
	
         });
		 
	
    </script>
<!-- BOOTSTRAP SCRIPTS -->
<script src="js/bootstrap.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="js/jquery.metisMenu.js"></script>
       <!-- CUSTOM SCRIPTS -->
    <script src="js/custom1.js"></script>

</body>
</html>

