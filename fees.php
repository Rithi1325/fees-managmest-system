<?php $page='fees';
include("php/dbconnect.php");
include("php/checklogin.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
$errormsg= '';
$classPaid = 0;
$busPaid = 0;
$vanPaid = 0;
$skillPaid = 0;
$otherPaid = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = isset($_POST['student']) ? $_POST['student'] : '';
  $grade = isset($_POST['grade']) ? $_POST['grade'] : '';

  if (empty($name) && empty($grade)) {
      $errormsg = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Please enter at least one field to filter!</div>";
      
  }
}

if(isset($_POST['save']))
{
$recno=mt_rand(10000, 99999);
// $recno = mysqli_real_escape_string($conn,$_POST['recno']);
$paid = mysqli_real_escape_string($conn,$_POST['paid']);
$submitdate = mysqli_real_escape_string($conn,$_POST['submitdate']);
$transcation_remark = mysqli_real_escape_string($conn,$_POST['transcation_remark']);
$paymentMode = mysqli_real_escape_string($conn,$_POST['paymentMode']);
$transid = mysqli_real_escape_string($conn,$_POST['transid']);
$checkno = mysqli_real_escape_string($conn,$_POST['check']);
$sid = mysqli_real_escape_string($conn,$_POST['sid']);

$sql = "select emailid,sname,sem,grade,fees,balance  from student where id = '$sid'";
$sq = $conn->query($sql);
$sr = $sq->fetch_assoc();
$email = $sr['emailid'];
$name = $sr['sname'];
$sem = $sr['sem'];
$c = $sr['grade'];
$totalfee = $sr['fees'];

$sql1 = $conn->query("select * from grade where id = '$c'");
$row = $sql1->fetch_assoc();
$class = $row['grade'] . ' ' . $row['yearofstart'] . '-' . $row['yearofend'];


if($sr['balance']>0)
{
$sql = "insert into fees_transaction(recno,stdid,submitdate,transcation_remark,paid,paymentmode,transid,checkno,sem) values('$recno','$sid','$submitdate','$transcation_remark','$paid','$paymentMode','$transid','$checkno','$sem') ";
$conn->query($sql);
$sql = "SELECT sum(paid) as totalpaid FROM fees_transaction WHERE stdid = '$sid'";
$tpq = $conn->query($sql);
$tpr = $tpq->fetch_assoc();
$totalpaid = $tpr['totalpaid'];
$tbalance = $totalfee - $totalpaid;

$sql = "update student set balance='$tbalance' where id = '$sid'";
$conn->query($sql);
$sql1 = "SELECT * from student where id = '$sid'";
$res=$conn->query($sql1);
$r=$res->fetch_assoc();
$classPaid = $r['classpaid'];
$busPaid = $r['buspaid'];
$vanPaid = $r['vanpaid'];
$skillPaid = $r['skillpaid'];
$otherPaid = $r['otherpaid'];
// Update specific columns based on transaction remark
switch ($transcation_remark) {
  case 'Semester Fees':
      $classPaid += $paid; // Update classpaid column
      break;
  case 'Bus Fees':
      $busPaid += $paid; // Update buspaid column
      break;
  case 'Van Fees':
      $vanPaid += $paid; // Update buspaid column
      break;
  case 'Skill Fees':
      $skillPaid += $paid; // Update buspaid column
      break;
  case 'Others':
      $otherPaid += $paid; // Update buspaid column
      break;
  // Add more cases for other transaction remarks if needed
  default:
      // Default action or error handling
      break;
}

// Update classpaid or buspaid columns in student table
$sqlUpdate = "update student set classpaid='$classPaid', buspaid='$busPaid', vanpaid='$vanPaid', skillpaid='$skillPaid', otherpaid='$otherPaid' where id = '$sid'";
$conn->query($sqlUpdate);

 echo '<script type="text/javascript">window.location="fees.php?act=1";</script>';

}

    // email send
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require 'PHPMailer-master\src\Exception.php';
        require 'PHPMailer-master\src\PHPMailer.php';
        require 'PHPMailer-master\src\SMTP.php';
        $mail = new PHPMailer();
        $smtp = new SMTP();
        $exception = new Exception();

        $to = $email;
        $subject = "SSB FEES PAYMENT REPORT";
        $message = 'Dear student,<br><br>
        I trust this email finds you well. I am writing to provide you with a comprehensive report on the fees payment status for <b>'.$name.'</b>. Please find the details below:<br>
        <pre>
        <ul>
          <li><b>Student Name :</b> '.$name.'</li>
          <li><b>Course       :</b> '.$class.'</li>
          <li><b>Semester     :</b> '.$sem.'</li>
          <li><b>Paid Date    :</b> '.$submitdate.'</li>
          <li><b>Total Fees   :</b> Rs.'.$totalfee.'</li>
          <li><b>Fees Type    :</b> '.$transcation_remark.'</li>
          <li><b>Amount Paid  :</b> Rs.'.$paid.'</li>
          <li><b>Balance      :</b> Rs.'.$tbalance.'</li>
        </ul></pre><br>
          If you have any questions or concerns regarding this report, please feel free to reach out to us at <a href="tel:04639-253605">04639-253605</a>. We appreciate your prompt attention to the payment details, and thank you for your continued cooperation.
          <br><br>
          Best regards,
          <br>
          <br>Sri Sankara Bagavathi Arts And Science College
          <br>Tisaiyanvillai - Udangudi Main Road, Kommadikottai - 628 653
          <br>Thoothukudi District
          <br>Tamilnadu
          <br><a href="tel:04639-253605">04639-253605</a>
          <br><a href="tel:9956609113">9956609113</a>';

        $mail = new PHPMailer();

        
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'haritharshinibscit@gmail.com';
        $mail->Password = 'gmpz lmrf upvc bqql'; 
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465; // Or 587 for TLS

       
        $mail->setFrom('haritharshinit@gmail.com', 'Hari Tharshini');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        
        if ($mail->send()) {
            echo "<script>alert('Email has been sent')</script>";
        } else {
            echo "<script>alert('Error: <?php $mail->ErrorInfo?>')</script>";
        }
    }


}



if(isset($_REQUEST['act']) && @$_REQUEST['act']=="1")
{
$errormsg = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Success!</strong> Fees has been submitted</div>";
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
	<link href="css/jquery-ui-1.10.3.custom.min.css" rel="stylesheet" />	
	<link href="css/datepicker.css" rel="stylesheet" />	
	   <link href="css/datatable/datatable.css" rel="stylesheet" />
	   
    <script src="js/jquery-1.10.2.js"></script>	
    <script type='text/javascript' src='js/jquery/jquery-ui-1.10.1.custom.min.js'></script>
   <script type="text/javascript" src="js/validation/jquery.validate.min.js"></script>
 
		 <script src="js/dataTable/jquery.dataTables.min.js"></script>
		
		 
	
</head>
<?php
include("php/header.php");
?>
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-head-line">Fees  
						
						</h1>

                    </div>
                </div>
				
				
				
    	<?php
		echo $errormsg;
		?>
		
		

<div class="row" style="margin-bottom:20px;">
<div class="col-md-12">
<fieldset class="scheduler-border" >
    <legend  class="scheduler-border">Search:</legend>
<form class="form-inline" role="form" id="searchform" method="POST">
  <div class="form-group">
    <label for="email">Name/Reg No:</label>
    <input type="text" class="form-control" id="student" name="student">
  </div>
  
  
  <div class="form-group">
    <label for="email"> Course: </label>
    <select  class="form-control" id="grade" name="grade" >
		<option value="" >Select Course</option>
                                    <?php
									$sql = "select * from grade where delete_status='0' order by grade.createddate desc";
									$q = $conn->query($sql);
									
									while($r = $q->fetch_assoc())
									{
									echo '<option value="'.$r['id'].'"  '.(($grade==$r['id'])?'selected="selected"':'').'>'.$r['grade'].' '.$r['yearofstart'].'-'.$r['yearofend'].'</option>';
									}
									?>
	</select>
  </div>
  
   <button type="button" class="btn btn-success btn-sm" style="border-radius:0%" id="find" > Filter </button>
  <button type="reset" class="btn btn-danger btn-sm" style="border-radius:0%" id="clear" > Reset </button>
</form>
</fieldset>

</div>
</div>

<script type="text/javascript">

  
$(document).ready( function() {

/*
$('#doj').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: false,
        dateFormat: 'mm/yy',
        onClose: function(dateText, inst) { 
            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
        }
    });
	
*/
	
/******************/	
	//  $("#doj").datepicker({
         
  //       changeMonth: true,
  //       changeYear: true,
  //       showButtonPanel: true,
  //       dateFormat: 'mm/yy',
  //       onClose: function(dateText, inst) {
  //           var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
  //           var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
  //           $(this).val($.datepicker.formatDate('MM yy', new Date(year, month, 1)));
  //       }
  //   });

  //   $("#doj").focus(function () {
  //       $(".ui-datepicker-calendar").hide();
  //       $("#ui-datepicker-div").position({
  //           my: "center top",
  //           at: "center bottom",
  //           of: $(this)
  //       });
  //   });

/*****************/
$('#student').autocomplete({
		      	source: function( request, response ) {
		      		$.ajax({
		      			url : 'ajx.php',
		      			dataType: "json",
						data: {
						   name_startsWith: request.term,
						   type: 'studentname'
						},
						 success: function( data ) {
						 
							 response( $.map( data, function( item ) {
							
								return {
									label: item,
									value: item
								}
							}));
						}
						
						
						
		      		});
		      	}
				/*,
		      	autoFocus: true,
		      	minLength: 0,
                 select: function( event, ui ) {
						  var abc = ui.item.label.split("-");
						  //alert(abc[0]);
						   $("#student").val(abc[0]);
						   return false;
               
						  },
                 */
  

						  
		      });
	

$('#find').click(function () {
mydatatable();
        });


$('#clear').click(function () {

$('#searchform')[0].reset();
mydatatable();
        });
		
function mydatatable()
{
        
              $("#subjectresult").html('<table class="table table-striped table-bordered table-hover" id="tSortable22"><thead><tr><th>Name/Reg No</th><th>Course & Batch</th><th>Semester</th><th>Fees</th><th>Paid Amount</th><th>Balance</th><th>Action</th></tr></thead><tbody></tbody></table>');
			  
			    $("#tSortable22").dataTable({
							      'sPaginationType' : 'full_numbers',
							     "bLengthChange": false,
                  "bFilter": false,
                  "bInfo": false,
							       'bProcessing' : true,
							       'bServerSide': true,
							       'sAjaxSource': "datatable.php?"+$('#searchform').serialize()+"&type=feesearch",
							       'aoColumnDefs': [{
                                   'bSortable': false,
                                   'aTargets': [-1] /* 1st one, start by the right */
                                                }]
                                   });


}
		
////////////////////////////
 $("#tSortable22").dataTable({
			     
                  'sPaginationType' : 'full_numbers',
				  "bLengthChange": false,
                  "bFilter": false,
                  "bInfo": true,
                  
                  'bProcessing' : true,
				  'bServerSide': true,
                  'sAjaxSource': "datatable.php?type=feesearch",
				  
			      'aoColumnDefs': [{
                  'bSortable': false,
                  'aTargets': [-1] /* 1st one, start by the right */
              }]
            });

///////////////////////////		


	
});


function GetFeeForm(sid) {
    $.ajax({
        type: 'post',
        url: 'getfeeform.php',
        data: { student: sid, req: '1' },
        success: function(data) {
            $('#formcontent').html(data);
            $("#myModal").modal({ backdrop: "static" });
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
            // Optionally, you can show an error message to the user here
        }
    });
}


</script>


		

<style>
#doj .ui-datepicker-calendar
{
display:none;
}

</style>
		
		<div class="panel panel-default">
                        <div class="panel-heading">
                            Manage Fees  
                        </div>
                        <div class="panel-body">
                            <div class="table-sorting table-responsive" id="subjectresult">
                                <table class="table table-striped table-bordered table-hover" id="tSortable22">
                                    <thead>
                                        <tr>
                                          
                                            <th>Name/Reg No</th> 
                                            <th>Course & Batch</th>
                                            <th>Semester</th>                                           
                                            <th>Fees</th>
                                            <th>Paid Amount</th>
                                            <th>Balance</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
								                  </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                     
	
	<!-------->
	
	<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" style="color:red;">&times;</button>
          <h4 class="modal-title">Collect Fee</h4>
        </div>
        <div class="modal-body" id="formcontent">
        
        </div>
        
      </div>
    </div>
  </div>

	
    <!--------->
    			
            
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
