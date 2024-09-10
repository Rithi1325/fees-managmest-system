<?php
$page = 'fees_structure';
include("php/dbconnect.php");
include("php/checklogin.php");
$error = "";
$action = "add";
$id = "";
$vanYear = "";
$vanSemester = "";
$vanFrom = "";
$vanTo = "";
$vanFees = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submitVanFees'])) {

    $vanYear = mysqli_real_escape_string($conn, $_POST['vanYear']);
    $vanSemester = mysqli_real_escape_string($conn, $_POST['vanSemester']);
    $vanFrom = mysqli_real_escape_string($conn, $_POST['vanFrom']);
    
    // Escape each 'To' address and fee in the arrays
    $vanTo = array_map(function($value) use ($conn) {
        return mysqli_real_escape_string($conn, $value);
    }, $_POST['vanTo']);
    
    $vanFees = array_map(function($value) use ($conn) {
        return mysqli_real_escape_string($conn, $value);
    }, $_POST['vanFees']);

    // Perform SQL INSERT query to store data in the database
    if ($_POST['action'] == "add") {
        // Perform SQL INSERT query to store data in the database
        foreach ($vanTo as $key => $to) {
            $insertVanFeesQuery = "INSERT INTO van_fees (year, semester, from_location, to_location, fees) 
                                   VALUES ('$vanYear', '$vanSemester', '$vanFrom', '$to', '{$vanFees[$key]}')";
            mysqli_query($conn, $insertVanFeesQuery);
        }
        
    echo '<script type="text/javascript">window.location="fees_structure.php?act=1";</script>';
    } elseif ($_POST['action'] == "edit") {
        // Perform SQL UPDATE query to update existing data in the database
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        foreach ($vanTo as $key => $to) {
            $updateVanFeesQuery = "UPDATE van_fees 
                       SET year = '$vanYear', 
                           semester = '$vanSemester', 
                           from_location = '$vanFrom', 
                           to_location = '$to', 
                           fees = '{$vanFees[$key]}' 
                       WHERE id = '$id'";
        
        mysqli_query($conn, $updateVanFeesQuery);
        }

    echo '<script type="text/javascript">window.location="fees_structure.php?act=2";</script>';
    }
    
} 
}

$action="add";
if (isset($_GET['action']) && @$_GET['action'] == "edit") {
    // Fetch data from the database based on the provided ID
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $editVanFeesQuery = "SELECT * FROM van_fees WHERE id = '$id'";
    $editVanFeesResult = mysqli_query($conn, $editVanFeesQuery);

    // Check if data exists for the provided ID
    if (mysqli_num_rows($editVanFeesResult) > 0) {
        // Fetch the data
        $editVanEntryData = mysqli_fetch_assoc($editVanFeesResult);

        // Populate variables with fetched data for pre-filling the form fields
        $vanYear = $editVanEntryData['year'];
        $vanSemester = $editVanEntryData['semester'];
        $vanFrom = $editVanEntryData['from_location'];
        $vanTo = $editVanEntryData['to_location'];
        $vanFees = $editVanEntryData['fees'];
        $action = "edit";
    } else {
        // Handle if no data found for the provided ID
        echo "No record found for the provided ID.";
    }
    
}

if (isset($_GET['action']) && $_GET['action'] == "delete") {
    $conn->query("UPDATE  van_fees set delete_status = '1'  WHERE id='" . $_GET['id'] . "'");
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
                <h1 class="page-head-line">Van Fees
                <?php
						echo (isset($_GET['action']) && @$_GET['action']=="add" || @$_GET['action']=="edit")?
						' <a href="fees_structure.php" class="btn btn-success btn-sm pull-right" style="border-radius:0%">Go Back </a>':'<a href="fees_record.php" class="btn btn-danger btn-sm pull-right" style="border-radius:0%">Previous Records</a>';
						?>
                </h1>
                <div class="row">
                    <?php
                    echo $error;
                    ?>

        <?php 
		 if(isset($_GET['action']) && @$_GET['action']=="add" || @$_GET['action']=="edit")
		 {
		?>

            <script type="text/javascript" src="js/validation/jquery.validate.min.js"></script>
            <div class="row">
				
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                        <?php echo (isset($_GET['action']) && $_GET['action'] == "edit") ? "Edit Van Fees" : "Add Van Fees"; ?>
                        </div>
                        <form id="vanFeesForm" method="post" action="van_fee.php" class="form-horizontal">
                        <div class="panel-body">
                            <!-- Common Fields -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Year:</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="vanYear" name="vanYear">
									<option value="">Select the Year</option>
									<?php
                                        $currentYear = date("Y");
                                        for ($year = $currentYear; $year <= $currentYear + 5; $year++) {
                                            $selected = ($year == $vanYear) ? 'selected' : '';
                                            echo "<option value='$year' $selected>$year</option>";
                                        }
                                        ?>
									</select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Semester:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="vanSemester" name="vanSemester" required>
                                            <option value="">Select Semester</option>
                                            <option value="Odd Semester" <?php echo ($vanSemester == "Odd Semester") ? "selected" : ""; ?>>Odd Semester</option>
                                            <option value="Even Semester" <?php echo ($vanSemester == "Even Semester") ? "selected" : ""; ?>>Even Semester</option>
                                        </select>
                                    </div>
                            </div>
                            
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="vanFrom">From:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="vanFrom" name="vanFrom" value="<?php echo $vanFrom; ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">To:</label>
                            
                                    <label class="col-sm-5 control-label" >Fees:</label>
                                </div>
                            <div class="van-destination">
                                <div class="form-group">
                                <label class="col-sm-1 control-label"></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="vanTo" name="vanTo[]" value="<?php echo $vanTo; ?>" required />
                                </div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="vanFees" name="vanFees[]" value="<?php echo $vanFees; ?>" required />
                                    </div>
                                    <?php
                                    if(isset($_GET['action']) && @$_GET['action']=="add"){
                                    ?>
                                    <a  class="btn btn-success btn-xs" style="border-radius:60px;"><span class="add-destination" onclick="addVan('van')"><span class="glyphicon glyphicon-plus"></span><span></a>
											
									<a  class="btn btn-danger btn-xs" style="border-radius:60px;" onclick="removeVan(this)"><span class="glyphicon glyphicon-trash"></span></a>
								    <?php
                                    }
                                    ?>
                                </div>
                                
                            </div>

                        
                        </div>
                        <div class="panel-body">
                            <div class="form-group col-sm-4 pull-right">
                                <input type="hidden" name="id" value="<?php echo $id;?>">
                                <input type="hidden" name="action" value="<?php echo $action;?>">
                                <button type="submit" class="btn btn-success" style="border-radius:0%" name="submitVanFees">Save</button>
                        </div></div>
                        </div>
                        </form>
                    </div>
         </div>
         </div>
         </div>
         </div>


            <?php
         }
		?>
                <script>
                    function addVan(type) {
                        var clone = $('.van-destination:first').clone();
                        clone.find('input[type="text"]').val('');
                        $('.van-destination:last').after(clone);
                    }


                    function removeVan(element) {
                        var container = element.parentNode.parentNode;
                        container.parentNode.removeChild(container);
                    }

                </script>
                    
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
    $("#vanFeesForm").validate({
        rules: {
            vanYear: "required",
            vanSemester: "required",
            vanFrom: "required",
            "vanTo[]": "required",
            "vanFees[]": "required",
        },
        messages: {
            vanYear: "Please select a current year",
            vanSemester: "Please select a semester",
            vanFrom: "Please enter a from destination",
            "vanTo[]": "Please enter a to destination",
            "vanFees[]": "Please enter van fees",
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

