<?php $page='discontinue';
include("php/dbconnect.php");
include("php/checklogin.php");
$errormsg = '';
$action = "add";


if (isset($_POST['save'])) {
    // Sanitize input data
    $studentId = $_POST['student_id'];
    $discontinueReason = $_POST['discontinue_reason'];
    $refundAmount = $_POST['refund_amount'];
    $submitDate = $_POST['submitdate'];

    // Prepare the SQL statement with placeholders for values
    $updateSql = "UPDATE student SET discontinue_reason = ?, refund_amount = ?, delete_status = '1', discont = '1' WHERE id = ?";

    // Prepare and bind parameters to avoid SQL injection for update statement
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("sss", $discontinueReason, $refundAmount, $studentId);

    // Execute the update query
    if ($updateStmt->execute()) {
        // Insert the transaction record into fees_transaction table
        if($refundAmount!='0'){
        $paidAmt = $refundAmount * -1;
        $insertSql = "INSERT INTO fees_transaction (recno, stdid, submitdate, transcation_remark, paid) VALUES ('220', ?, ?, 'Discontinued', ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("sss", $studentId, $submitDate, $paidAmt);
        if ($insertStmt->execute()) {
            // Redirect back to the discontinue page with a success message
            header("Location: discont.php?act=1");
            exit();
        } else {
            // Redirect back to the discontinue page with an error message
            header("Location: discont.php?act=2&error=" . urlencode($insertStmt->error));
            exit();
        }
    }
    header("Location: discont.php?act=1");
    exit();
    } else {
        // Redirect back to the discontinue page with an error message
        header("Location: discont.php?act=2&error=" . urlencode($updateStmt->error));
        exit();
    }
} 

if (isset($_REQUEST['act']) && @$_REQUEST['act'] == "1") {
    $errormsg = "<div class='alert alert-success'>Student has been discontinued successfully</div>";
}

if (isset($_REQUEST['act']) && @$_REQUEST['act'] == "2") {
    $errormsg = "<div class='alert alert-danger'>Error occurred while discontinuing student</div>";
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
    <?php include("discont_form.php"); ?>

</head>
<?php
include("php/header.php");
?>
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Discontinue Student
                <?php
						echo (isset($_GET['action']))?
						' <a href="discont.php" class="btn btn-success btn-sm pull-right" style="border-radius:0%">Go Back </a>':'<a href="discont_list.php" class="btn btn-danger btn-sm pull-right" style="border-radius:0%">Discontinued Students</a>';
						?>
                </h1>
                
                    <?php
                    echo $errormsg;
                    ?>
                    <link href="css/datatable/datatable.css" rel="stylesheet" />

                    <div class="panel panel-default">
                        <div class="panel-heading">
                        Discontinue Student
                            </div>
                        <div class="panel-body">
                             <div class="table-sorting table-responsive">

                                <table class="table table-striped table-bordered table-hover" id="tSortable21">
                                <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name | Reg No</th>
											<th>Course</th>
                                            <th>Semester</th>
                                            <th>Total Fees</th>
                                            <th>Paid Amount</th>
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
										$sql1 = $conn->query("select * from grade where id = ".$r['grade']."");
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
									    echo '<tr '.(($r['balance']>0)?'class="primary"':'').'>
                                            <td>'.$i.'</td>
											<td>'.$r['sname'].'<br/>'.$r['roll_no'].'</td>
											<td>'.$row['grade'].' '.$row['yearofstart'].'-'.$row['yearofend'].'</td>
                                            <td>'.$r['sem'].'</td>
                                            <td>'.$r['fees'].'</td>
                                            <td>' . $feesText . '</td>
											<td>'.$r['balance'].'</td>
											<td>
											<button class="btn btn-success btn-sm" style="border-radius:0%" onclick="DiscontForm('.$r['id'].')">Discontinue</button>
                                            </td>
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



<!-- BOOTSTRAP SCRIPTS -->
<script src="js/bootstrap.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="js/jquery.metisMenu.js"></script>
       <!-- CUSTOM SCRIPTS -->
    <script src="js/custom1.js"></script>

</body>
</html>
