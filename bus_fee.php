<?php
$page = 'fees_structure';
include("php/dbconnect.php");
include("php/checklogin.php");
$error = '';
$action = "add";

$busYear = '';
$busSemester = '';
$busFrom = '';
$busTo = '';
$busFees = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submitBusFees'])) {
        $busYear = mysqli_real_escape_string($conn, $_POST['busYear']);
        $busSemester = mysqli_real_escape_string($conn, $_POST['busSemester']);
        $busFrom = mysqli_real_escape_string($conn, $_POST['busFrom']);
        $busTo = array_map(function ($value) use ($conn) {
            return mysqli_real_escape_string($conn, $value);
        }, $_POST['busTo']);
        $busFees = array_map(function ($value) use ($conn) {
            return mysqli_real_escape_string($conn, $value);
        }, $_POST['busFees']);

        // Perform SQL INSERT or UPDATE query based on action
        if ($_POST['action'] == "add") {
            // Insert new data
            foreach ($busTo as $key => $to) {
                $insertBusFeesQuery = "INSERT INTO bus_fees (year, semester, from_location, to_location, fees) 
                                       VALUES ('$busYear', '$busSemester', '$busFrom', '$to', '{$busFees[$key]}')";
                mysqli_query($conn, $insertBusFeesQuery);
            }
            echo '<script type="text/javascript">window.location="fees_structure.php?act=1";</script>';
        } elseif ($_POST['action'] == "edit") {
            // Update existing data
            // $id = mysqli_real_escape_string($conn, $_POST['id']);
            $id = mysqli_real_escape_string($conn, $_POST['id']);
            foreach ($busTo as $key => $to) {
                $updateBusFeesQuery = "UPDATE bus_fees SET 
                                       year = '$busYear', 
                                       semester = '$busSemester', 
                                       from_location = '$busFrom', 
                                       to_location = '$to', 
                                       fees = '{$busFees[$key]}'
                                       WHERE id = '$id'";
                mysqli_query($conn, $updateBusFeesQuery);
            }
            echo '<script type="text/javascript">window.location="fees_structure.php?act=2";</script>';
        }
    }
}
$action="add";
if (isset($_GET['action']) && @$_GET['action'] == "edit") {
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        $editBusFeesQuery = "SELECT * FROM bus_fees WHERE id = '$id'";
        $editBusFeesResult = mysqli_query($conn, $editBusFeesQuery);

        if (mysqli_num_rows($editBusFeesResult) > 0) {
            // Fetch the data
            $editBusEntryData = mysqli_fetch_assoc($editBusFeesResult);

            $busYear = $editBusEntryData['year'];
            $busSemester = $editBusEntryData['semester'];
            $busFrom = $editBusEntryData['from_location'];
            $busTo = $editBusEntryData['to_location'];
            $busFees = $editBusEntryData['fees'];
            $action = "edit";
        } else {
            // Handle if no data found for the provided ID
            echo "No record found for the provided ID.";
        }
}

if (isset($_GET['action']) && $_GET['action'] == "delete" && isset($_GET['id'])) {
    $conn->query("UPDATE  bus_fees SET delete_status = '1' WHERE id='" . $_GET['id'] . "'");
    header("location: fees_structure.php?act=3");
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
    <!-- CUSTOM BASIC STYLES-->
    <link href="css/basic.css" rel="stylesheet" />
    <!-- CUSTOM MAIN STYLES-->
    <link href="css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

    <link href="css/ui.css" rel="stylesheet" />
    <link href="css/datepicker.css" rel="stylesheet" />

    <script src="js/jquery-1.10.2.js"></script>

    <script type='text/javascript' src='js/jquery/jquery-ui-1.10.1.custom.min.js'></script>
</head>
<body>
    <?php include("php/header.php"); ?>
    <div id="page-wrapper">
        <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-head-line">Bus Fees
                        <?php echo (isset($_GET['action']) && @$_GET['action']=="add" || @$_GET['action']=="edit") ? ' <a href="fees_structure.php" class="btn btn-success btn-sm pull-right" style="border-radius:0%">Go Back </a>' : '<a href="fees_record.php" class="btn btn-danger btn-sm pull-right" style="border-radius:0%">Previous Records</a>'; ?>
                    </h1>
                    <div class="row">
                        <?php echo $error; ?>
                        <?php if(isset($_GET['action']) && @$_GET['action']=="add" || @$_GET['action']=="edit") { ?>
                            <script type="text/javascript" src="js/validation/jquery.validate.min.js"></script>
                            <div class="row">
                                <div class="col-sm-8 col-sm-offset-2">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <?php echo (isset($_GET['action']) && $_GET['action'] == "edit") ? "Edit Bus Fees" : "Add Bus Fees"; ?>
                                        </div>
                                        <form id="busFeesForm" method="post" action="bus_fee.php" class="form-horizontal">
                                            <div class="panel-body">
                                                <!-- Common Fields -->
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Year:</label>
                                                    <div class="col-sm-8">
                                                        <select class="form-control" id="busYear" name="busYear">
                                                            <option value="">Select the Year</option>
                                                            <?php
                                                            $currentYear = date("Y");
                                                            for ($year = $currentYear; $year <= $currentYear + 5; $year++) {
                                                                $selected = ($busYear == $year) ? 'selected' : '';
                                                                echo "<option value='$year' $selected>$year</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Semester:</label>
                                                    <div class="col-sm-8">
                                                        <select class="form-control" id="busSemester" name="busSemester">
                                                            <option value="">Select Semester</option>
                                                            <option value="Odd Semester"  <?php echo ($busSemester == "Odd Semester") ? "selected" : ""; ?>>Odd Semester</option>
                                                            <option value="Even Semester" <?php echo ($busSemester == "Even Semester") ? "selected" : ""; ?>>Even Semester</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">From:</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" id="busFrom" name="busFrom" value="<?php echo $busFrom ?? ''; ?>"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">To:</label>
                                                    <label class="col-sm-5 control-label">Fees:</label>
                                                </div>
                                                <div class="bus-destination">
                                                    <div class="form-group">
                                                        <label class="col-sm-1 control-label"></label>
                                                        <div class="col-sm-5">
                                                            <input type="text" class="form-control" id="busTo" name="busTo[]" value="<?php echo $busTo; ?>"/>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="busFees" name="busFees[]" value="<?php echo $busFees; ?>"/>
                                                        </div>
                                                        <?php if(isset($_GET['action']) && @$_GET['action']=="add") { ?>
                                                            <a class="btn btn-success btn-xs" style="border-radius:60px;"><span class="add-destination" onclick="addBus('bus')"><span class="glyphicon glyphicon-plus"></span><span></a>
                                                            <a class="btn btn-danger btn-xs" style="border-radius:60px;" onclick="removeBus(this)"><span class="glyphicon glyphicon-trash"></span></a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel-body">
                                                <div class="form-group col-sm-4 pull-right">
                                                    <input type="hidden" name="id" value="<?php echo $id;?>">
								                    <input type="hidden" name="action" value="<?php echo $action;?>">
                                                    <button type="submit" class="btn btn-success" style="border-radius:0%" name="submitBusFees">Save</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <script>
                            function addBus(type) {
                                var clone = $('.bus-destination:first').clone();
                                clone.find('input[type="text"]').val('');
                                $('.bus-destination:last').after(clone);
                            }

                            function removeBus(element) {
                                var container = element.parentNode.parentNode;
                                container.parentNode.removeChild(container);
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#busFeesForm").validate({
                rules: {
                    busYear: "required",
                    busSemester: "required",
                    busFrom: "required",
                    "busTo[]": "required",
                    "busFees[]": "required",
                },
                messages: {
                    busYear: "Please select a current year",
                    busSemester: "Please select a semester",
                    busFrom: "Please enter a from destination",
                    "busTo[]": "Please enter a to destination",
                    "busFees[]": "Please enter bus fees",
                },
                errorElement: "em",
                errorPlacement: function (error, element) {
                    // Add the `help-block` class to the error element
                    error.addClass("help-block");

                    // Add `has-feedback` class to the parent div.form-group
                    // in order to add icons to inputs
                    element.parents(".col-sm-8").addClass("has-feedback");

                    if (element.prop("type") === "checkbox") {
                        error.insertAfter(element.parent("label"));
                    } else {
                        error.insertAfter(element);
                    }

                    // Add the span element, if it doesn't exist, and apply the icon classes to it.
                    if (!element.next("span")[0]) {
                        $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
                    }
                },
                success: function (label, element) {
                    // Add the span element, if it doesn't exist, and apply the icon classes to it.
                    if (!$(element).next("span")[0]) {
                        $("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
                    }
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).parents(".col-sm-8").addClass("has-error").removeClass("has-success");
                    $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).parents(".col-sm-8").addClass("has-success").removeClass("has-error");
                    $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
                }
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
