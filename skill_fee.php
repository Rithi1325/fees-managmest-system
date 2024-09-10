<?php
$page = 'fees_structure';
include("php/dbconnect.php");
include("php/checklogin.php");
$error = '';
$action = "add";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save'])) {
        $skillYear = mysqli_real_escape_string($conn, $_POST['skillYear']);
        $skillSemester = mysqli_real_escape_string($conn, $_POST['skillSemester']);

        $skillNames = array_map(function($value) use ($conn) {
            return mysqli_real_escape_string($conn, $value);
        }, $_POST['skillName']);
        
        $skillFees = array_map(function($value) use ($conn) {
            return mysqli_real_escape_string($conn, $value);
        }, $_POST['skillFees']);

        // Perform SQL INSERT query to store data in the database
        if ($_POST['action'] == "add") {
            foreach ($skillNames as $key => $skillName) {
                $fee = $skillFees[$key]; 
                $insertSkillFeesQuery = "INSERT INTO skill_fees (year, semester, skill_name, fees) 
                                         VALUES ('$skillYear', '$skillSemester', '$skillName', '$fee')";
                mysqli_query($conn, $insertSkillFeesQuery);
            }
            
        echo '<script type="text/javascript">window.location="fees_structure.php?act=1";</script>';
        } elseif ($_POST['action'] == "edit") {
            $id = mysqli_real_escape_string($conn, $_POST['id']);
            foreach ($skillNames as $key => $skillName) {
                $fee = $skillFees[$key]; 
                $updateSkillFeesQuery = "UPDATE skill_fees 
                                         SET year = '$skillYear', 
                                             semester = '$skillSemester', 
                                             skill_name = '$skillName', 
                                             fees = '$fee' 
                                         WHERE id = '$id'";
                mysqli_query($conn, $updateSkillFeesQuery);
            }
            
        echo '<script type="text/javascript">window.location="fees_structure.php?act=2";</script>';
        }
        
    }
}


$action="add";
if (isset($_GET['action']) && @$_GET['action'] == "edit") {
    $id =mysqli_real_escape_string($conn, $_GET['id']);
    $fetch_query = "SELECT * FROM skill_fees WHERE id='$id'";
    $fetch_result = mysqli_query($conn, $fetch_query);
    $data = mysqli_fetch_assoc($fetch_result);

    $action = "edit";
}


if (isset($_GET['action']) && $_GET['action'] == "delete") {
    $conn->query("UPDATE  skill_fees set delete_status = '1'  WHERE id='" . $_GET['id'] . "'");
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
                <h1 class="page-head-line">Skill Fees
                <?php
						echo (isset($_GET['action']) && @$_GET['action']=="add" || @$_GET['action']=="edit" )?
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
                        <?php echo (isset($_GET['action']) && $_GET['action'] == "edit") ? "Edit Skill Fees" : "Add Skill Fees"; ?>
                        </div>
                        <form id="skillFeesForm" method="post" action="skill_fee.php" class="form-horizontal">
                        <div class="panel-body">
                            <!-- Common Fields -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Year:</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="skillYear" name="skillYear">
									<option value="">Select the Year</option>
									<?php
                                    $currentYear = date("Y");
                                    for ($year = $currentYear; $year <= $currentYear + 5; $year++) {
                                        // Check if $year matches the fetched year data and mark it as selected if true
                                        $selected = isset($data['year']) && $data['year'] == $year ? 'selected' : '';
                                        echo "<option value='$year' $selected>$year</option>";
                                    }
                                    ?>
									</select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Semester:</label>
                                <div class="col-sm-8">
                                <select class="form-control" id="skillSemester" name="skillSemester" required>
                                    <option value="">Select Semester</option>
                                    <option value="Odd Semester"<?php echo isset($data['semester']) && $data['semester'] == 'Odd Semester' ? 'selected' : ''; ?>>Odd Semester</option>
                                    <option value="Even Semester"<?php echo isset($data['semester']) && $data['semester'] == 'Even Semester' ? 'selected' : ''; ?>>Even Semester</option>
                                </select>
                            </div>
                            </div>
                            <div class="skill-section">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Skill Name:</label>
                                    <div class="col-sm-8">
                                    <input type="text" class="form-control" id="skillName" name="skillName[]" value="<?php echo isset($data['skill_name']) ? $data['skill_name'] : ''; ?>" required />
                                </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Fees:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="skillFees" name="skillFees[]" value="<?php echo isset($data['fees']) ? $data['fees'] : ''; ?>" required />
                                        </div>
                                
                                            <!-- <span class="remove-skill" onclick="removeSkill(this)">Remove</span> -->
                                        
                                        <!-- <div class="col-sm-2 pull-right"> -->
                                        <!-- <span class="add-skill" onclick="addSkill()">Add More</span> -->
                                        <?php
                                        if(isset($_GET['action']) && @$_GET['action']=="add"){
                                        ?>
                                        <a  class="btn btn-success btn-xs" style="border-radius:60px;"><span class="add-destination" onclick="addSkill()"><span class="glyphicon glyphicon-plus"></span><span></a>
                                                        
                                        <a  class="btn btn-danger btn-xs" style="border-radius:60px;" onclick="removeSkill(this)"><span class="glyphicon glyphicon-trash"></span></a>
                                        <?php
                                        }
                                        ?>
                                    </div>
                            </div>
                            </div>
                            <!-- </div> -->
                            <div class="panel-body">
                            <div class="form-group col-sm-4 pull-right">
                                <input type="hidden" name="id" value="<?php echo $id;?>">
                                <input type="hidden" name="action" value="<?php echo $action;?>">
                                <button type="submit" class="btn btn-success" name="save">Save</button>
                            </div>
                            </div>
                        </form>
                    </div>
                </div>
        <?php
         }
            ?>
                <script>
                    function addSkill() {
                        var clone = $('.skill-section:first').clone();
                        clone.find('input[type="text"]').val('');
                        $('.skill-section:last').after(clone);
                    }


                    function removeSkill(element) {
                        var container = element.parentNode.parentNode;
                        container.parentNode.removeChild(container);
                    }

                </script>

                <script>
                    function openAddEntryModal1() {
                        $('#addEntryModal1').modal('show');
                    }
                    function openAddEntryModal2() {
                        $('#addEntryModal1').modal('show');
                    }
                    function openAddEntryModal3() {
                        $('#addEntryModal1').modal('show');
                    }
                </script>
                    
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#skillFeesForm").validate({
            rules: {
                skillYear: "required",
                skillSemester: "required",
                "skillName[]": "required",
                "skillFees[]": "required",
            },
            messages: {
                skillYear: "Please select a current year",
                skillSemester: "Please select a semester",
                "skillName[]": "Please enter skill name",
                "skillFees[]": "Please enter skill fees",
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

