<?php
$page = 'course';
include("php/dbconnect.php");
include("php/checklogin.php");
$errormsg = '';
$action = "add";

$grade = '';
$dept = '';
$duration = '';
$semester = '';
$yos = '';
$yoe = '';
$amount = '';
$id = '';

if (isset($_POST['save'])) {
    $grade = mysqli_real_escape_string($conn, $_POST['grade']);
    $dept = mysqli_real_escape_string($conn, $_POST['dept']);
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);
    $semester = mysqli_real_escape_string($conn, $_POST['sem']);
    $yos = mysqli_real_escape_string($conn, $_POST['yos']);
    $yoe = mysqli_real_escape_string($conn, $_POST['yoe']);
	$amount = mysqli_real_escape_string($conn, $_POST['amount']);

    if ($_POST['action'] == "add") {
		$sql = $conn->query("INSERT INTO grade (grade, dept, duration, sem, yearofstart, yearofend, amount) VALUES ('$grade', '$dept', '$duration', '$semester', '$yos', '$yoe', '$amount')");
        echo '<script type="text/javascript">window.location="class.php?act=1";</script>';
    } else if ($_POST['action'] == "update") {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $sql = $conn->query("UPDATE grade SET grade = '$grade', dept = '$dept', duration = '$duration', sem = '$semester', yearofstart = '$yos', yearofend = '$yoe', amount = '$amount' WHERE id = '$id'");
        echo '<script type="text/javascript">window.location="class.php?act=2";</script>';
    }
}

if (isset($_GET['action']) && $_GET['action'] == "delete") {
    $conn->query("UPDATE  grade set delete_status = '1'  WHERE id='" . $_GET['id'] . "'");
    header("location: class.php?act=3");
}

$action = "add";
if (isset($_GET['action']) && $_GET['action'] == "edit") {
    $id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

    $sqlEdit = $conn->query("SELECT * FROM grade WHERE id = '$id'");
    if ($sqlEdit->num_rows) {
        $rowsEdit = $sqlEdit->fetch_assoc();
        extract($rowsEdit);
        $action = "update";
		$amount = $rowsEdit['amount'];
    } else {
        $_GET['action'] = "";
    }
}

if (isset($_REQUEST['act']) && @$_REQUEST['act'] == "1") {
    $errormsg = "<div class='alert alert-success'> Course has been added successfully</div>";
} else if (isset($_REQUEST['act']) && @$_REQUEST['act'] == "2") {
    $errormsg = "<div class='alert alert-success'> Course has been updated successfully</div>";
} else if (isset($_REQUEST['act']) && @$_REQUEST['act'] == "3") {
    $errormsg = "<div class='alert alert-success'> Course has been deleted successfully</div>";
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
	
	 <script src="js/jquery-1.10.2.js"></script>


	
</head>
<?php
include("php/header.php");
?>
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-head-line">Manage Course
						<?php
						echo (isset($_GET['action']) && @$_GET['action']=="add" || @$_GET['action']=="edit")?
						' <a href="class.php" class="btn btn-success btn-sm pull-right" style="border-radius:0%">Go Back </a>':'<a href="class.php?action=add" class="btn btn-danger btn-sm pull-right" style="border-radius:0%"><i class="glyphicon glyphicon-plus"></i> Add New Course </a>';
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
				
                    <div class="col-sm-12 col-sm-offset-0">
               <div class="panel panel-success">
                        <div class="panel-heading">
                           <?php echo ($action=="add")? "Add Course": "Edit Course"; ?>
                        </div>
						<form action="class.php" method="post" id="signupForm1" class="form-horizontal">
                        <div class="panel-body">
						
						
						
						
						<div class="form-group">
								<label class="col-sm-2 control-label" for="Old">Course Name</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="grade" name="grade" value="<?php echo $grade;?>"  />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label" for="Old">Department</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="dept" name="dept" value="<?php echo $dept;?>"  />
								</div>
							</div>
								
							<div class="form-group">
								<label class="col-sm-2 control-label" for="Old">Duration of Course</label>
								<div class="col-sm-10">
									<select class="form-control" name="duration" id="duration" onchange="updateSemester(); updateYearOfEnd();">
									<option value="">Select duration of course</option>
									<option value="2"<?php echo ($duration == 2) ? 'selected' : ''; ?>>2 Years</option>
									<option value="3"<?php echo ($duration == 3) ? 'selected' : ''; ?>>3 Years</option>
									<option value="4"<?php echo ($duration == 4) ? 'selected' : ''; ?>>4 Years</option>
									</select>
								</div>
								</div>

							
							<div class="form-group">
								<label class="col-sm-2 control-label" for="Old">Semester</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="sem" name="sem" value="<?php echo $semester;?>" readonly />
								</div>
								</div>

							<div class="form-group">
								<label class="col-sm-2 control-label" for="Old">Year of Start</label>
								<div class="col-sm-10">
									<select class="form-control" id="yos" name="yos" value="<?php echo $yos;?>" onchange="updateYearOfEnd();">
									<option value="">Select the Year</option>
									<?php
									$currentYear = date("Y");
									for ($year = $currentYear; $year <= $currentYear + 5; $year++) {
										echo "<option value='$year' " . (($yearofstart == $year) ? 'selected' : '') . ">$year</option>";
									}
									?>
									</select>
								</div>
								</div>


							<div class="form-group">
								<label class="col-sm-2 control-label" for="Old">Year of End</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="yoe" name="yoe" value="<?php echo $yearofend;?>" readonly />
								<!--
								<select class="form-control" id="yoe" name="yoe" disabled>
									<option value="">Select the Year</option>
									<?php
									$currentYear = date("Y");
									for ($year = $currentYear; $year <= $currentYear + 5; $year++) {
										echo "<option value='$year' " . (($yearofend == $year) ? 'selected' : '') . ">$year</option>";
									}
									?>
								</select>
								-->
								</div>
								</div>

								<div class="form-group">
									<label class="col-sm-2 control-label" for="Old">Course Amount</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="amount" name="amount" value="<?php echo $amount; ?>" />
									</div>
								</div>

							
						
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
		

		$( document ).ready( function () {			
			
			 if($("#signupForm1").length > 0)
         {
			 // Function to update Semester based on Duration
			 function updateSemester() {
				var duration = $("#duration").val();
				var semester = duration * 2;
				$("#sem").val(semester);
			}

			function updateYearOfEnd() {
				var startYear = parseInt($("#yos").val());
				var duration = parseInt($("#duration").val());
				var endYear = startYear + duration;
				$("#yoe").val(endYear);
			}

			$("#duration, #yos").on("change", function () {
				updateSemester();
				updateYearOfEnd();
			});

			// Call update functions initially
			updateSemester();
			updateYearOfEnd();

			$( "#signupForm1" ).validate( {
				rules: {
					grade: "required",
					dept: "required",
					duration: "required",
					sem: "required",
					yos: "required",
					yoe: "required",
					amount: "required",
								
				
					
				},
				messages: {
					grade: "Please enter Course name",
					dept: "Please enter department name",
					duration: "Please select the duration of this course",
					sem: "Please enter the total semester",
					yos: "Please enter year of start",
					yoe: "Please enter year of end",
					amount: "Please enter the amount for this course"
					
					
					
				},
				errorElement: "em",
				errorPlacement: function ( error, element ) {
					// Add the `help-block` class to the error element
					error.addClass( "help-block" );

					// Add `has-feedback` class to the parent div.form-group
					// in order to add icons to inputs
					element.parents( ".col-sm-10" ).addClass( "has-feedback" );

					if ( element.prop( "type" ) === "checkbox" ) {
						error.insertAfter( element.parent( "label" ) );
					} else {
						error.insertAfter( element );
					}

					// Add the span element, if doesn't exists, and apply the icon classes to it.
					if ( !element.next( "span" )[ 0 ] ) {
						$( "<span class='glyphicon glyphicon-remove form-control-feedback'></span>" ).insertAfter( element );
					}
				},
				success: function ( label, element ) {
					// Add the span element, if doesn't exists, and apply the icon classes to it.
					if ( !$( element ).next( "span" )[ 0 ] ) {
						$( "<span class='glyphicon glyphicon-ok form-control-feedback'></span>" ).insertAfter( $( element ) );
					}
				},
				highlight: function ( element, errorClass, validClass ) {
					$( element ).parents( ".col-sm-10" ).addClass( "has-error" ).removeClass( "has-success" );
					$( element ).next( "span" ).addClass( "glyphicon-remove" ).removeClass( "glyphicon-ok" );
				},
				unhighlight: function ( element, errorClass, validClass ) {
					$( element ).parents( ".col-sm-10" ).addClass( "has-success" ).removeClass( "has-error" );
					$( element ).next( "span" ).addClass( "glyphicon-ok" ).removeClass( "glyphicon-remove" );
				}
			} );
			
			}
			
		} );
	</script>


			   
		<?php
		}else{
		?>
		
		 <link href="css/datatable/datatable.css" rel="stylesheet" />
		 
		
		 
		 
		<div class="panel panel-default">
                        <div class="panel-heading">
                            Manage Course
                        </div>
                        <div class="panel-body">
                             <div class="table-sorting table-responsive">

                                <table class="table table-striped table-bordered table-hover" id="tSortable22">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Course Name | ID</th>
                                            <th>Department</th>
											<th>Duration</th>
											<th>Semester</th>
											<th>Year of Start</th>
											<th>Year of End</th>
											<th>Amount</th>
											<th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
									$sql = "select * from grade where delete_status='0'";
									$q = $conn->query($sql);
									$i=1;
									while($r = $q->fetch_assoc())
									{
									echo '<tr>
                                            <td>'.$i.'</td>
                                            <td>'.$r['grade'].'<br>'.$r['id']." ".'</td>
											<td>'.$r['dept'].'</td>
											<td>'.$r['duration'].' Years</td>
											<td>'.$r['sem'].'</td>
											<td>'.$r['yearofstart'].'</td>
											<td>'.$r['yearofend'].'</td>
											<td>'.$r['amount'].'</td>
											<td>
											<a href="class.php?action=edit&id='.$r['id'].'" class="btn btn-success btn-xs" style="border-radius:60px;"><span class="glyphicon glyphicon-edit"></span></a>
											
											<a onclick="return confirm(\'Are you sure you want to delete this record\');" href="class.php?action=delete&id='.$r['id'].'" class="btn btn-danger btn-xs" style="border-radius:60px;"><span class="glyphicon glyphicon-remove"></span></a> </td>
                                        </tr>';
										$i++;
									}
									?>
									
                                        
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                     
	<script src="js/dataTable/jquery.dataTables.min.js"></script>
	<script>
  // Function to update Semester based on Duration
  function updateSemester() {
    var duration = document.getElementById('duration').value;
    var semester = duration * 2;
    document.getElementById('sem').value = semester;
  }

  // Function to update Year of End based on Year of Start and Duration
  function updateYearOfEnd() {
    var startYear = parseInt(document.getElementById('yos').value);
    var duration = parseInt(document.getElementById('duration').value);
    var endYear = startYear + duration;
    document.getElementById('yoe').value = endYear;
  }
</script>
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
