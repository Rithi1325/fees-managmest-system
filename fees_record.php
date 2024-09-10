<?php
$page = 'fees_structure';
include("php/dbconnect.php");
include("php/checklogin.php");
$error = '';
$action = "add";

// Fetch data from the database to display in tables
$busFeesQuery = "SELECT * FROM bus_fees WHERE delete_status='1'";
$busFeesResult = mysqli_query($conn, $busFeesQuery);

$skillFeesQuery = "SELECT * FROM skill_fees  WHERE delete_status='1'";
$skillFeesResult = mysqli_query($conn, $skillFeesQuery);

// Fetch data from the database to display in tables
$vanFeesQuery = "SELECT * FROM van_fees  WHERE delete_status='1'";
$vanFeesResult = mysqli_query($conn, $vanFeesQuery);

if (isset($_GET['action']) && @$_GET['action'] == "edit1" && isset($_GET['id'])) {
    $editBusEntryId = mysqli_real_escape_string($conn, $_GET['id']);
    $editBusFeesQuery = "SELECT * FROM bus_fees WHERE id = '$editBusEntryId'";
    $editBusFeesResult = mysqli_query($conn, $editBusFeesQuery);
    $editBusEntryData = mysqli_fetch_assoc($editBusFeesResult);

    // Now, you can use $editBusEntryData to pre-fill the form fields in the edit mode
    // Modify the HTML accordingly
}
elseif (isset($_GET['action']) && @$_GET['action'] == "edit2" && isset($_GET['id'])) {
    $editVanEntryId = mysqli_real_escape_string($conn, $_GET['id']);
    $editVanFeesQuery = "SELECT * FROM van_fees WHERE id = '$editVanEntryId'";
    $editVanFeesResult = mysqli_query($conn, $editVanFeesQuery);
    $editVanEntryData = mysqli_fetch_assoc($editVanFeesResult);

    // Now, you can use $editVanEntryData to pre-fill the form fields in the edit mode
    // Modify the HTML accordingly
}


if (isset($_GET['action']) && $_GET['action'] == "delete1") {
    $conn->query("DELETE FROM  bus_fees WHERE id=" . $_GET['id']);
    header("location: fees_record.php?act=3");
}

if (isset($_GET['action']) && $_GET['action'] == "delete2") {
    $conn->query("DELETE FROM  van_fees WHERE id=" . $_GET['id']);
    header("location: fees_record.php?act=3");
}
if (isset($_GET['action']) && $_GET['action'] == "delete3") {
    $conn->query("DELETE FROM  skill_fees WHERE id=" . $_GET['id']);
    header("location: fees_record.php?act=3");
}


if(isset($_GET['action']) && $_GET['action']=="approve1"){
    $conn->query("UPDATE bus_fees SET delete_status = '0' WHERE id=" . $_GET['id']);	
    header("location: fees_record.php?act=2");
}

if(isset($_GET['action']) && $_GET['action']=="approve2"){
    $conn->query("UPDATE van_fees SET delete_status = '0' WHERE id=" . $_GET['id']);	
    header("location: fees_record.php?act=2");
}
if(isset($_GET['action']) && $_GET['action']=="approve3"){
    $conn->query("UPDATE skill_fees SET delete_status = '0' WHERE id=" . $_GET['id']);	
    header("location: fees_record.php?act=2");
}


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
                <h1 class="page-head-line">Previous Fees Records
                <?php
						echo '<a href="fees_structure.php" class="btn btn-success btn-sm pull-right" style="border-radius:0%">Go Back </a>';
						?>
                </h1>
                <div class="row">
                    <?php
                    echo $error;
                    ?>
                    <link href="css/datatable/datatable.css" rel="stylesheet" />

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Previous Bus Fees Records
                            </div>
                        <div class="panel-body">
                             <div class="table-sorting table-responsive">

                                <table class="table table-striped table-bordered table-hover" id="tSortable21">
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
                                while ($row = mysqli_fetch_assoc($busFeesResult)) {
                                    echo "<tr>
                                            <td>{$row['id']}</td>
                                            <td>{$row['year']}</td>
                                            <td>{$row['semester']}</td>
                                            <td>{$row['from_location']}</td>
                                            <td>{$row['to_location']}</td>
                                            <td>{$row['fees']}</td>
                                            <td>
											<a href=\"fees_record.php?action=approve1&id='{$row['id']}'\" class='btn btn-success btn-xs' style='border-radius:60px;'><span class='glyphicon glyphicon-ok'></span></a>
											
											<a onclick=\"return confirm('Are you sure you want to delete this record permanently?');\" href=\"fees_record.php?action=delete1&id='{$row['id']}'\" class='btn btn-danger btn-xs' style='border-radius:60px;'><span class='glyphicon glyphicon-trash'></span></a> </td>
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
                            Previous van Fees Records
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
											<a href=\"fees_record.php?action=approve2&id='{$row['id']}'\" class='btn btn-success btn-xs' style='border-radius:60px;'><span class='glyphicon glyphicon-ok'></span></a>
											
											<a onclick=\"return confirm('Are you sure you want to delete this record permanently?');\" href=\"fees_record.php?action=delete2&id='{$row['id']}'\" class='btn btn-danger btn-xs' style='border-radius:60px;'><span class='glyphicon glyphicon-trash'></span></a> </td>
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
                            Previous Skill Fees Records
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
											<a href=\"fees_record.php?action=approve3&id='{$row['id']}'\" class='btn btn-success btn-xs' style='border-radius:60px;'><span class='glyphicon glyphicon-ok'></span></a>
											
											<a onclick=\"return confirm('Are you sure you want to delete this record permanently?');\" href=\"fees_record.php?action=delete3&id='{$row['id']}'\" class='btn btn-danger btn-xs' style='border-radius:60px;'><span class='glyphicon glyphicon-trash'></span></a> </td>
											</tr>";
                                        $serialNum++;
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <script src="js/dataTable/jquery.dataTables.min.js"></script> 
                
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#tSortable21').dataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "bAutoWidth": true
        });
    });
</script>

<script>
    $(document).ready(function () {
        $('#tSortable22').dataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "bAutoWidth": true
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('#tSortable23').dataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "bAutoWidth": true
        });
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
