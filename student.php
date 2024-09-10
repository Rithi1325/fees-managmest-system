<?php
$page = 'student';
include("php/dbconnect.php");
include('php/checklogin.php');
$errormsg = '';
$action = "add";

$id = "";
$roll = '';
$emailid = '';
$sname = '';
$gender = '';
$dob = '';
$sem = '';
$remark = '';
$contact = '';
$balance = 0;
$classfee = '';
$bus = '';
$busfee = '';
$van = '';
$vanfee = '';
$skill = '';
$skillfee = '';
$fees = '';
$about = '';
$grade = '';
$other = '';
$otherfee = '';
$advancefees = 0;

if (isset($_POST['delete_all'])) {
    $conn->query("UPDATE student SET delete_status = '1' Where balance='0'");
    header("location: student.php?act=4");
}
if (isset($_POST['delete'])) {
    $conn->query("UPDATE student SET delete_status = '1'");
    header("location: student.php?act=5");
}


if (isset($_POST['save'])) {
    $roll = mysqli_real_escape_string($conn, $_POST['roll']);
    $sname = mysqli_real_escape_string($conn, $_POST['sname']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $sem = isset($_POST['sem']) ? mysqli_real_escape_string($conn, $_POST['sem']) : '';
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $about = mysqli_real_escape_string($conn, $_POST['about']);
    $emailid = mysqli_real_escape_string($conn, $_POST['emailid']);
    $grade = mysqli_real_escape_string($conn, $_POST['grade']);
    $bus = mysqli_real_escape_string($conn, $_POST['bus']);
    $van = mysqli_real_escape_string($conn, $_POST['van']);
    $skill = mysqli_real_escape_string($conn, $_POST['skill']);
    $other = mysqli_real_escape_string($conn, $_POST['other']);
    $otherfee = mysqli_real_escape_string($conn, $_POST['otherfee']);

    if ($_POST['action'] == "add") {
        $recno = mt_rand(10000, 99999);
        $remark = mysqli_real_escape_string($conn, $_POST['remark']);
        $fees = mysqli_real_escape_string($conn, $_POST['fees']);
        $advancefees = isset($_POST['advancefees']) ? intval($_POST['advancefees']) : 0;

        $balance = $fees - $advancefees;
        $classfee = isset($_POST['classfee']) ? floatval($_POST['classfee']) : 0;
        $busfee = isset($_POST['busfee']) ? floatval($_POST['busfee']) : 0;
        $vanfee = isset($_POST['vanfee']) ? floatval($_POST['vanfee']) : 0;
        $skillfee = isset($_POST['skillfee']) ? floatval($_POST['skillfee']) : 0;
        // $fees = $classfee + $busfee + $vanfee + $skillfee;
        $q1 = $conn->query("INSERT INTO student (roll_no, sname, gender, dob, sem, contact, about, emailid, grade, balance, fees, classfee, bus, busfee, van, vanfee, skill, skillfee, other, otherfee) VALUES ('$roll', '$sname', '$gender', '$dob', '$sem', '$contact', '$about', '$emailid', '$grade', '$balance', '$fees', '$classfee', '$bus', '$busfee', '$van', '$vanfee', '$skill', '$skillfee','$other', '$otherfee')");
        
        $sid = $conn->insert_id;
        if ($advancefees != 0) {
            $conn->query("INSERT INTO fees_transaction (recno, stdid, paid, submitdate, transcation_remark) VALUES ('$recno', '$sid', '$advancefees', '$sem', '$remark')");
        }
        echo '<script type="text/javascript">window.location="student.php?act=1";</script>';
    } elseif ($_POST['action'] == "update") {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $fees = mysqli_real_escape_string($conn, $_POST['fees']);
        $advancefees = isset($_POST['advancefees']) ? mysqli_real_escape_string($conn, $_POST['advancefees']) : 0;
        $classfee = isset($_POST['classfee']) ? mysqli_real_escape_string($conn, $_POST['classfee']) : 0;
        $busfee = isset($_POST['busfee']) ? mysqli_real_escape_string($conn, $_POST['busfee']) : 0;
        $vanfee = isset($_POST['vanfee']) ? mysqli_real_escape_string($conn, $_POST['vanfee']) : 0;
        $skillfee = isset($_POST['skillfee']) ? mysqli_real_escape_string($conn, $_POST['skillfee']) : 0;
        $otherfee = isset($_POST['otherfee']) ? mysqli_real_escape_string($conn, $_POST['otherfee']) : 0;
        $balance = $fees - $advancefees;

        // Update student details in the database
        $sql = $conn->query("UPDATE student SET grade = '$grade', roll_no = '$roll', sname = '$sname', gender = '$gender', dob = '$dob', sem = '$sem', fees = '$fees', balance = '$balance', contact = '$contact', about = '$about', emailid = '$emailid', classfee = '$classfee', bus = '$bus', busfee = '$busfee', van = '$van', vanfee = '$vanfee', skill = '$skill', skillfee = '$skillfee', other = '$other', otherfee = '$otherfee' WHERE id = '$id'");

        if ($sql) {
            // Update the balance based on total fees and total paid amount
            $totalPaidAmountQuery = $conn->query("SELECT SUM(paid) AS total_paid FROM fees_transaction WHERE stdid = '$id'");
            $totalPaidAmountRow = $totalPaidAmountQuery->fetch_assoc();
            $totalPaidAmount = $totalPaidAmountRow['total_paid'];

            $newBalance = $fees - $totalPaidAmount;

            // Update the balance in the student table
            $updateBalanceQuery = $conn->query("UPDATE student SET balance = '$newBalance' WHERE id = '$id'");

            // Check if the balance update was successful
            if ($updateBalanceQuery) {
                echo '<script type="text/javascript">window.location="student.php?act=2";</script>';
            } else {
                $errormsg = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Error updating balance!</div>";
            }
        } else {
            $errormsg = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Error updating student record!</div>";
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] == "delete") {
    $conn->query("UPDATE  student set delete_status = '1'  WHERE id='" . $_GET['id'] . "'");
    header("location: student.php?act=3");
}

$action = "add";
if (isset($_GET['action']) && @$_GET['action'] == "edit") {
    $id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

    $sqlEdit = $conn->query("SELECT * FROM student WHERE id='" . $id . "'");
    if ($sqlEdit->num_rows) {
        $rowsEdit = $sqlEdit->fetch_assoc();
        extract($rowsEdit);
        $action = "update";
        // Retrieve the existing balance and already paid amount
        $existingBalance = $balance;
        $alreadyPaid = $fees - $balance;
        $roll = $roll_no;
    } else {
        $_GET['action'] = "";
    }
}

if (isset($_REQUEST['act']) && @$_REQUEST['act'] == "1") {
    $errormsg = "<div class='alert alert-success'> <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Student record has been added!</div>";
} else if (isset($_REQUEST['act']) && @$_REQUEST['act'] == "2") {
    $errormsg = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Student record has been updated!</div>";
} else if (isset($_REQUEST['act']) && @$_REQUEST['act'] == "3") {
    $errormsg = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Student has been deleted!</div>";
} else if (isset($_REQUEST['act']) && @$_REQUEST['act'] == "4") {
    $errormsg = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>All paid student's records have been deleted!</div>";
} else if (isset($_REQUEST['act']) && @$_REQUEST['act'] == "5") {
    $errormsg = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>All student's records have been deleted!</div>";
}
else if (isset($_REQUEST['act']) && @$_REQUEST['act'] == "6") {
    $errormsg = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Remainder email has been send to all student's successfully</div>";
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
                        <h1 class="page-head-line">Manage Students  
						<?php
						echo (isset($_GET['action']) && @$_GET['action']=="add" || @$_GET['action']=="edit")?
						' <a href="student.php" class="btn btn-success btn-sm pull-right" style="border-radius:0%">Go Back </a>':'<a href="student.php?action=add" class="btn btn-danger btn-sm pull-right" style="border-radius:0%"><i class="glyphicon glyphicon-plus"></i> Add New Student</a>';
						?>
						</h1>
						<?php
						echo $errormsg;
						?>
                    </div>
                </div>
	
        <?php 
		 if(isset($_GET['action']) && @$_GET['action']=="add" || @$_GET['action']=="edit")
		 {
		?>
		
			<script type="text/javascript" src="js/validation/jquery.validate.min.js"></script>
                <div class="row">
				
                    <div class="col-sm-10 col-sm-offset-1">
               <div class="panel panel-success">
                        <div class="panel-heading">
                           <?php echo ($action=="add")? "Add Student Details": "Edit Student Details"; ?>
                        </div>
						<form action="student.php" method="post" id="signupForm1" class="form-horizontal">
                        <div class="panel-body">
						<fieldset class="scheduler-border" >
						 <legend  class="scheduler-border">Personal Information:</legend>
                         <div class="form-group">
								<label class="col-sm-2 control-label" for="Old">Reg No* </label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="roll" name="roll" value="<?php echo $roll;?>"  />
								</div>
							</div>
						<div class="form-group">
								<label class="col-sm-2 control-label" for="Old">Full Name* </label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="sname" name="sname" value="<?php echo $sname;?>"  />
								</div>
							</div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="Old">Gender* </label>
                                <div class="col-sm-10">
                                    <div class="gender-error-container">
                                    <label><input type="radio" name="gender" value="Male" <?php echo ($gender == "Male") ? "checked": ""; ?>> Male</label><br>
                                    <label><input type="radio" name="gender" value="Female" <?php echo ($gender == "Female") ? "checked": ""; ?>> Female</label>
                                </div></div>
                            </div>

                            <div class="form-group">
								<label class="col-sm-2 control-label" for="Old">DOB* </label>
								<div class="col-sm-10">
									<input type="date" class="form-control" id="dob" name="dob" value="<?php echo $dob;?>"  />
								</div>
							</div>

						<div class="form-group">
								<label class="col-sm-2 control-label" for="Old">Contact* </label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="contact" name="contact" value="<?php echo $contact;?>" maxlength="10" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label" for="Old">Email Id* </label>
								<div class="col-sm-10">
									
									<input type="text" class="form-control" id="emailid" name="emailid" value="<?php echo $emailid;?>"  />
								</div>
						    </div>
                            <div class="form-group">
								<label class="col-sm-2 control-label" for="Password">Community </label>
								<div class="col-sm-10">
	                        <input type="text" class="form-control" id="about" name="about" value="<?php echo $about;?>" />
								</div>
							</div>
							
							
						<div class="form-group">
								<label class="col-sm-2 control-label" for="Old">Course* </label>
								<div class="col-sm-7">
									<select  class="form-control" id="grade" name="grade">
									<option value="" >Select Course</option>
                                    <?php
									$sql = "select * from grade where delete_status='0' order by grade.createddate desc";
									$q = $conn->query($sql);
									
									while($r = $q->fetch_assoc())
									{
									echo '<option value="'.$r['id'].'"  '.(($grade==$r['id'])?'selected="selected"':'').'>'.$r['grade'].' '.$r['yearofstart'].'-'.$r['yearofend'].'</option>';
									}
									// $sql = "update table student set grade=";
									// $q = $conn->query($sql);
									// $grade=$['grade'];
									
									?>									
									
									</select>
								</div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="classfee" name="classfee" value="<?php echo $classfee;?>" readonly />
								</div>
						</div>
						
						
						<div class="form-group">
								<label class="col-sm-2 control-label" for="Old">Semester* </label>
								<div class="col-sm-10">
									<!-- <input type="text" class="form-control" id="sem" name="sem" value="<?php echo  $sem; ?>"  /> -->
                                    <select  class="form-control" id="sem" name="sem">
									<option value="" >Select Semester</option>									
									</select>
                                    <input type="hidden" id="selectedSemester" value="<?php echo $sem; ?>">
								</div>
							</div>

                        
						 </fieldset>
						
						 
							<fieldset class="scheduler-border" >
						 <legend  class="scheduler-border">Fee Information:</legend>
						 <?php 
						// if($action=="add")
						// {
						?>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="Old">Bus Fees </label>
                            <div class="col-sm-7">
									<select  class="form-control" id="bus" name="bus">
									<option value="" >Select Bus</option>
                                    <?php
									$sql = "select * from bus_fees where delete_status='0'";
									$q = $conn->query($sql);
									
									while($row = $q->fetch_assoc())
									{
									echo '<option value="'.$row['id'].'"  '.(($bus==$row['id'])?'selected="selected"':'').'>'.$row['from_location'].'-'.$row['to_location'].'</option>';
									}
									?>									
									
									</select>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="busfee" name="busfee" value="<?php echo $busfee;?>" readonly />
								</div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="Old">Van Fees </label>
                            <div class="col-sm-7">
									<select  class="form-control" id="van" name="van">
									<option value="" >Select Van</option>
                                    <?php
									$sql = "select * from van_fees where delete_status='0'";
									$q = $conn->query($sql);
									
									while($row = $q->fetch_assoc())
									{
                                        echo '<option value="'.$row['id'].'"  '.(($van==$row['id'])?'selected="selected"':'').'>'.$row['from_location'].'-'.$row['to_location'].'</option>';
									}
									?>									
									
									</select>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="vanfee" name="vanfee" value="<?php echo $vanfee;?>" readonly />
								</div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="Old">Skill Fees </label>
                            <div class="col-sm-7">
									<select  class="form-control" id="skill" name="skill">
									<option value="" >Select Skill</option>
                                    <?php
									$sql = "select * from skill_fees where delete_status='0'";
									$q = $conn->query($sql);
									
									while($row = $q->fetch_assoc())
									{
                                        echo '<option value="'.$row['id'].'"  '.(($skill==$row['id'])?'selected="selected"':'').'>'.$row['skill_name'].'</option>';
									}
									?>									
									
									</select>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="skillfee" name="skillfee" value="<?php echo $skillfee;?>" readonly />
								</div>
                        </div>

                        <div class="form-group">
								<label class="col-sm-2 control-label" for="Old">Other Fees </label>
								<div class="col-sm-7">
									<select  class="form-control" id="other" name="other">
									<option value="">Select fees type</option>
                                    <option value="Fine Fees" <?php if ($other == "Fine Fees") echo "selected"; ?>>Fine Amount</option>
                                    <option value="Leave Fine Fees" <?php if ($other == "Leave Fine Fees") echo "selected"; ?>>Leave Fine Amount</option>
                                    <option value="Balance Amount" <?php if ($other == "Balance Amount") echo "selected"; ?>>Balance Amount</option>
                                    <option value="Others" <?php if ($other == "Others") echo "selected"; ?>>Others</option>
                                    <!-- <option value="Semester Fees">Semester Fees</option> -->
                                    <!-- <option value="Bus Fees">Bus Fees</option>
                                    <option value="Van Fees">Van Fees</option>
                                    <option value="Skill Fees">Skill Fees</option> -->			
									
									</select>
                                </div>
								<div class="col-sm-3">
									<input type="text" class="form-control" id="otherfee" name="otherfee" value="<?php echo $otherfee; ?>"/>
								</div>
								
						</div>

						<div class="form-group">
								<label class="col-sm-2 control-label" for="Old">Total Fees </label>
								
								<div class="col-sm-10">
									<input type="text" class="form-control" id="fees" name="fees" value="<?php echo $fees; ?>" readonly/>
								</div>
								
						</div>
						
						<!-- <?php
						// }
						if($action=="add")
						{
						?>
						<div class="form-group">
								<label class="col-sm-2 control-label" for="Old">Adv Fee Paid* </label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="advancefees" name="advancefees" oninput="toggleFeeTypeTextbox()" />
								</div>
						</div>
						<?php
						}
						?> -->
						
						<div class="form-group">
								<label class="col-sm-2 control-label" for="Old">Balance </label>
								<div class="col-sm-10">
									<input type="text" class="form-control"  id="balance" name="balance" value="<?php echo $balance;?>" disabled />
								</div>
						</div>
						
							<?php
						if($action=="add")
						{
                            if ($advancefees > 0) {
                                ?>
                                <div class="form-group" id="feesTypeGroup" style="display: block;">
                                    <label class="col-sm-2 control-label" for="Password">Fees Type </label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="remark" name="remark"><?php echo $remark; ?></textarea>
                                    </div>
                                </div>
                            <?php
                            } else {
                                ?>
                                <div class="form-group" id="feesTypeGroup" style="display: none;">
                                    <label class="col-sm-2 control-label" for="Password">Fees Type </label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="remark" name="remark"><?php echo $remark; ?></textarea>
                                    </div>
                                </div>
                            <?php
                            }
						}
						?>
							
							</fieldset>
						
						<div class="form-group">
								<div class="col-sm-8 col-sm-offset-2">
								<input type="hidden" name="id" value="<?php echo $id;?>">
								<input type="hidden" name="action" value="<?php echo $action;?>">
								
									<button type="submit" name="save" class="btn btn-success" style="border-radius:0%">Save </button>

								   
								</div>
							</div>
                         </div>
							</form>
							
                        </div>
                            </div>
                </div>
  
<script type="text/javascript">
    $(document).ready(function() {
        if ($("#signupForm1").length > 0) {
            <?php if($action=='add') { ?>
            $("#signupForm1").validate({
                rules: {
                    roll: {
                        required: true,
                        digits: true,
                    },
                    sname: "required",
                    gender: "required",
                    dob: "required",
                    emailid: "email",
                    grade: "required",
                    sem: {
                        required: true,
                        digits: true,
                        min: 1,
                        max: 6,
                    },
                    contact: {
                        required: true,
                        digits: true,
                        // min: 10,
                        // max: 10,
                    },
                    fees: {
                        required: true,
                        digits: true,
                    },
                    otherfee: {
                        digits: true,
                    },
                    advancefees: {
                        required: true,
                        digits: true
                    },
                    emailid: {
                        required: true,
                        email: true
                    },
                },
                errorElement: "em",
                errorPlacement: function(error, element) {
                    error.addClass("help-block");
                    element.parents(".col-sm-10").addClass("has-feedback");
                    if (element.prop("type") === "checkbox") {
                        error.insertAfter(element.parent("label"));
                    } 
                    else if (element.attr("type") === "radio") {
                        error.appendTo(element.parents(".gender-error-container")); // Append error to the parent div
                    } else {
                        error.insertAfter(element);
                    }
                    if (!element.next("span")[0]) {
                        $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
                    }
                },
                success: function(label, element) {
                    if ($(element).attr("type") === "radio") {
                // Clear any existing error message for radio buttons
                $(element).parents(".gender-error-container").find(".help-block").remove();
            } 
                    if (!$(element).next("span")[0]) {
                        $("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
                    }
                },
                highlight: function(element, errorClass, validClass) {
                    if ($(element).attr("type") === "radio") {
                $(element).parents(".gender-error-container").addClass("has-error").removeClass("has-success");
                $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
            }
                    $(element).parents(".col-sm-10").addClass("has-error").removeClass("has-success");
                    $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
                },
                unhighlight: function(element, errorClass, validClass) {
                    if ($(element).attr("type") === "radio") {
                $(element).parents(".gender-error-container").addClass("has-success").removeClass("has-error");
                $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
            }
                    $(element).parents(".col-sm-10").addClass("has-success").removeClass("has-error");
                    $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
                },
            });
            <?php } else { ?>
            $("#signupForm1").validate({
                rules: {
                    roll: {
                        required: true,
                        digits: true,
                    },
                    sname: "required",
                    gender: "required",
                    dob: "required",
                    emailid: "email",
                    grade: "required",
                    sem: {
                        required: true,
                        digits: true,
                        min: 1,
                        max: 6,
                    },
                    contact: {
                        required: true,
                        digits: true,
                        // min: 10,
                        // max: 10,
                    },
                    emailid: {
                        required: true,
                        email: true
                    },
                },
                errorElement: "em",
                errorPlacement: function(error, element) {
                    error.addClass("help-block");
                    element.parents(".col-sm-10").addClass("has-feedback");
                    if (element.prop("type") === "checkbox") {
                        error.insertAfter(element.parent("label"));
                    } 
                    else if (element.attr("type") === "radio") {
                        error.appendTo(element.parents(".gender-error-container")); // Append error to the parent div
                    } else {
                        error.insertAfter(element);
                    }
                    if (!element.next("span")[0]) {
                        $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
                    }
                },
                success: function(label, element) {
                    if ($(element).attr("type") === "radio") {
                // Clear any existing error message for radio buttons
                $(element).parents(".gender-error-container").find(".help-block").remove();
            } 
                    if (!$(element).next("span")[0]) {
                        $("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
                    }
                },
                highlight: function(element, errorClass, validClass) {
                    if ($(element).attr("type") === "radio") {
                $(element).parents(".gender-error-container").addClass("has-error").removeClass("has-success");
                $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
            }
                    $(element).parents(".col-sm-10").addClass("has-error").removeClass("has-success");
                    $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
                },
                unhighlight: function(element, errorClass, validClass) {
                    if ($(element).attr("type") === "radio") {
                $(element).parents(".gender-error-container").addClass("has-success").removeClass("has-error");
                $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
            }
                    $(element).parents(".col-sm-10").addClass("has-success").removeClass("has-error");
                    $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
                },
            });
            <?php } ?>
        }
    });
</script>
<script>
	$(document).ready(function() {

    // Function to toggle fee type textbox based on advance fees input
    function toggleFeeTypeTextbox() {
        var advanceFees = parseFloat($('#advancefees').val());
        var feesTypeGroup = $('#feesTypeGroup');

        if (advanceFees > 0) {
            feesTypeGroup.show();  // Show the fees type textbox
        } else {
            feesTypeGroup.hide();  // Hide the fees type textbox
        }
    }

    // Event listener for keyup event on advance fees input
    $('#advancefees').keyup(function() {
        toggleFeeTypeTextbox();
    });

    // Call the toggle function initially
    toggleFeeTypeTextbox();

    // Function to update total fees based on individual fee components
    function updateTotalFees() {
        var classFee = parseFloat($('#classfee').val()) || 0;
        var busFee = parseFloat($('#busfee').val()) || 0;
        var vanFee = parseFloat($('#vanfee').val()) || 0;
        var skillFee = parseFloat($('#skillfee').val()) || 0;
        var otherFee = parseFloat($('#otherfee').val()) || 0;

        var totalFees = classFee + busFee + vanFee + skillFee + otherFee;
        $('#fees').val(totalFees);  // Update total fees input

        var alreadyPaid = <?php echo ($action == 'add' ? 'null' : $alreadyPaid); ?>;
            var totalfee = parseInt(totalFees);
            var balance = totalfee - (alreadyPaid !== null ? alreadyPaid : 0);
            $("#balance").val(balance);

    }

    // Call the update function initially
    updateTotalFees();

    // Event listeners for keyup events on fee inputs
    $('#classfee, #busfee, #vanfee, #skillfee, #otherfee').keyup(function() {
        updateTotalFees();
    });

    // Event listener for keyup event on advance fees input
    $('#advancefees').keyup(function() {
        updateTotalFees();
    });

        $("#grade").change(function() {
            var gradeId = $(this).val();
            if (gradeId !== '') {
                $.ajax({
                    url: 'get_details.php', // PHP script to fetch grade details including amount
                    type: 'POST',
                    data: { grade_id: gradeId },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.success) {
                            // Get the existing total fees
                            var existingFees = parseFloat($("#fees").val());
                            // Add the fetched amount to the existing total fees
                            // var newFees = existingFees + parseFloat(data.amount);
                            // Populate the Total Fees input field with the new total fees 
                            var newFees = parseFloat(data.amount);
                            $("#classfee").val(newFees); // Assuming 2 decimal places for the fees
                            updateTotalFees();

                        } else {
                            console.error(data.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
                
            } 
            else {
                $("#classfee").val('');
                updateTotalFees();
            }
        });

$("#bus").change(function() {
    var busId = $(this).val();
    if (busId !== '') {
        $.ajax({
            url: 'get_details.php', // PHP script to fetch bus details including fees
            type: 'POST',
            data: { bus_id: busId },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    // Populate the Bus Fee input field with the fetched fees
                    $("#busfee").val(parseFloat(data.fees)); // Assuming 2 decimal places for the fees
                    updateTotalFees();
                } else {
                    console.error(data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
    else {
        $("#busfee").val('');
        updateTotalFees();
    }
});

$("#van").change(function() {
    var vanId = $(this).val();
    if (vanId !== '') {
        $.ajax({
            url: 'get_details.php', // PHP script to fetch van details including fees
            type: 'POST',
            data: { van_id: vanId },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    // Populate the Van Fee input field with the fetched fees
                    $("#vanfee").val(parseFloat(data.fees)); // Assuming 2 decimal places for the fees
                    updateTotalFees();
                } else {
                    console.error(data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
    else {
        $("#vanfee").val('');
        updateTotalFees();
    }
});

$("#skill").change(function() {
    var skillId = $(this).val();
    if (skillId !== '') {
        $.ajax({
            url: 'get_details.php', // PHP script to fetch skill details including fees
            type: 'POST',
            data: { skill_id: skillId },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    // Populate the Skill Fee input field with the fetched fees
                    $("#skillfee").val(parseFloat(data.fees)); // Assuming 2 decimal places for the fees
                    updateTotalFees();
                } else {
                    console.error(data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
    else {
        $("#skillfee").val('');
        updateTotalFees();
    }
});


    $("#fees").keyup(function() {
        var fee = $.trim($(this).val());
        if (fee !== '' && !isNaN(fee)) {
            var alreadyPaid = <?php echo ($action == 'add' ? 'null' : $alreadyPaid); ?>;
            var totalfee = parseInt(fee);
            var balance = totalfee - (alreadyPaid !== null ? alreadyPaid : 0);
            $("#balance").val(balance);
            $("#advancefees").removeAttr("readonly");
            $('#advancefees').rules("add", {
                max: parseInt(fee)
            });
        } else {
            $("#balance").val(0);
            $("#advancefees").attr("readonly", "readonly");
        }
    });

    // Function to update semester dropdown based on selected grade/course
    function updateSemesterDropdown() {
        var courseId = $('#grade').val();
        
        if (courseId !== '') {
            $.ajax({
                url: 'get_details.php',
                type: 'POST',
                data: { courseId: courseId },
                success: function(response) {
                    $('#sem').html(response);
                    
                    // Retrieve selected semester value from the hidden input field
                    var selectedSemester = $('#selectedSemester').val();
                    
                    // Set the selected semester in the dropdown
                    $('#sem').val(selectedSemester);
                },
                error: function(xhr, status, error) {
                    console.error(status, error);
                }
            });
        } else {
            $('#sem').html('<option value="">Select Semester</option>');
        }
    }
    
    // Event listener for grade/course selection change
    $('#grade').change(function() {
        updateSemesterDropdown();
    });
    
    // Initial call to update semester dropdown on page load
    updateSemesterDropdown();


    $("#otherfee").keyup(function() {
        var otherFee = parseFloat($.trim($(this).val())) || 0;
        updateTotalFees();
    });
    $("#advancefees").keyup(function() {
        var advancefees = parseInt($.trim($(this).val()));
        var totalfee = parseInt($("#fees").val());
        if (!isNaN(advancefees)) {
            var balance = totalfee - advancefees;
            $("#balance").val(balance);
        } else {
            $("#balance").val(totalfee);
        }
    });
});

</script>



			   
		<?php
		}else{
		?>
		
		 <link href="css/datatable/datatable.css" rel="stylesheet" />
		 
		<div class="panel panel-default">
                        <div class="panel-heading">
						<?php
						if(isset($_SESSION['message']))
						{   
							echo $_SESSION['message'];
							unset($_SESSION['message']);
						}
						?>
						<form action="import.php" method="POST" enctype="multipart/form-data">
						<h5><i><strong>Note:</strong> The file must contain data in the given order.</i></h5>
                            <input type="file" name="import_file" class="form-control" /> <br/>
                            <button type="submit" name="save_excel_data" class="btn btn-success btn-sm pull-left">Import File</button></form>
							<form action="download_excel.php" method="post">
							<button type="submit" name="download_excel" class="btn btn-success btn-sm pull-right">Download File</button><br>
							</form>
						</div>
						<div class="panel-heading">
                            Manage Student  
                        </div>
                        <div class="panel-body">
                            <div class="table-sorting table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="tSortable22">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name | Reg No</th>
											<th>Course</th>
                                            <th>Semester</th>
                                            <th>Fees</th>
                                            <th>Paid</th>
											<th>Balance</th>
											<th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
									$sql = "select * from student where delete_status='0'";
									$q = $conn->query($sql);
									$i=1;
									
									while($r = $q->fetch_assoc())
									{
                                        $sql1 = $conn->query("SELECT * FROM grade WHERE id = " . $r['grade']);
                                        $row = $sql1->fetch_assoc();
                                    
                                        $feesText = '';

                                        if ($r['classpaid'] > 0) {
                                            $feesText .= 'Semester Fees: ' . $r['classpaid'] . '<br>';
                                        }
                                        
                                        if ($r['buspaid'] > 0) {
                                            $feesText .= 'Bus Fees: ' . $r['buspaid'] . '<br>';
                                        }
                                        
                                        if ($r['vanpaid'] > 0) {
                                            $feesText .= 'Van Fees: ' . $r['vanpaid'] . '<br>';
                                        }
                                        
                                        if ($r['skillpaid'] > 0) {
                                            $feesText .= 'Skill Fees: ' . $r['skillpaid'] . '<br>';
                                        }
                                        
                                        if ($r['otherpaid'] > 0) {
                                            $feesText .= 'Other Fees: ' . $r['otherpaid'] . '<br>';
                                        }
                                        
                                    
                                        echo '<tr ' . ($r['balance'] > 0 ? 'class="primary"' : '') . '>
                                                <td>' . $i . '</td>
                                                <td>' . $r['sname'] . '<br/>' . $r['roll_no'] . '</td>
                                                <td>' . $row['grade'] . ' ' . $row['yearofstart'] . '-' . $row['yearofend'] . '</td>
                                                <td>' . $r['sem'] . '</td>
                                                <td>' . $r['fees'] . '</td>
                                                <td>' . $feesText . '</td>
                                                <td>' . $r['balance'] . '</td>
                                                <td>
                                                    <a href="student.php?action=edit&id=' . $r['id'] . '" class="btn btn-success btn-xs" style="border-radius:60px;"><span class="glyphicon glyphicon-edit"></span></a> ';
                                                    if($_SESSION['typeUser']=="admin"){
                                                    echo '<a onclick="return confirm(\'Are you sure you want to deactivate this record\');" href="student.php?action=delete&id=' . $r['id'] . '" class="btn btn-danger btn-xs" style="border-radius:60px;"><span class="glyphicon glyphicon-remove"></span></a>';
                                                    }
                                                    echo '</td>
                                            </tr>';
                                        $i++;
									}
									
									?>
									
                                    </tbody>
                                </table>
                                <?php
                                if($_SESSION['typeUser']=="admin"){
                                ?>
								<form action="student.php" method="post" >
    								<button type="submit" name="delete_all" style="align:right;" class="btn btn-danger btn-sm pull-center" onclick="return confirm('Are you sure you want to deactivate paid student records?')">Delete Paid Student's Details</button>
								</form>
                                <?php
                                }
                                ?>
                                <form action="mail.php" method="post">
									</br><button type="submit" name="mail" class="btn btn-success btn-sm pull-center" onclick="return confirm('Are you sure you want to send remainder for all students?')">Send Remainder</button>
								</form>
								<form action="student.php" method="post">
									</br><button type="submit" name="delete" class="btn btn-danger btn-sm pull-center" onclick="return confirm('Are you sure you want to deactivate all records?')">Delete All</button>
								</form>
                            </div>
                        </div>
                    </div>
                     
	<script src="js/dataTable/jquery.dataTables.min.js"></script>
    
     <script>
         $(document).ready(function () {
             $('#tSortable22').dataTable({
                "bPaginate": true,
                "bLengthChange": true,
                "bFilter": true,
                "bInfo": true,
                "bAutoWidth": true });
	
         });
    </script>
		
		<?php
		}
		?>
            </div>
            <!-- /. PAGE INNER  -->
			
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->

    
   
  
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="js/bootstrap.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="js/jquery.metisMenu.js"></script>
       <!-- CUSTOM SCRIPTS -->
    <script src="js/custom1.js"></script>
</body>
</html>
