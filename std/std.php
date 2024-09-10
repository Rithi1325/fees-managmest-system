<?php
include("../php/dbconnect.php");
include("../php/checklogin.php");

// Check if the session variable exists and if not, redirect to the login page
if (!isset($_SESSION['rainbow_regno']) || !isset($_SESSION['rainbow_stdids'])) {
    header("Location: stdlogin.php");
    exit();
}

// Access session variables
$regno = $_SESSION['rainbow_regno'];
$stdids = $_SESSION['rainbow_stdids'];

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fees Management System For SSB college - Kommadikottai</title>

    <!-- BOOTSTRAP STYLES-->
    <link href="../css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="../css/font-awesome.css" rel="stylesheet" />
       <!--CUSTOM BASIC STYLES-->
    <link href="../css/basic.css" rel="stylesheet" />
    <!--CUSTOM MAIN STYLES-->
    <link href="../css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    
</head>
<body>
    <div id="page-inner">
    <!-- <div class="modal fade" id="myModal" role="dialog"> -->
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Student's Fees Report</h4>
        </div>
      <div class="modal-body" id="formcontent" id="exampl">
        <?php
            // Loop through each student ID and fetch their information
            foreach ($stdids as $stdid) {
                // Fetch student details based on the stdid
                $sql_student = "SELECT * FROM student WHERE id='$stdid'";
                $result_student = $conn->query($sql_student);
                
                if ($result_student->num_rows > 0) {
                    // Display student information
                    $student_info = $result_student->fetch_assoc();
                    
                $sql1 = $conn->query("select * from grade where id = ".$student_info['grade']."");
				$row = $sql1->fetch_assoc();
                    echo '<div id="exampl">
                    <h4>Student Info</h4>
                          <div class="table-responsive">
                              <table class="table table-bordered">
                                  <tr>
                                      <th>Name</th>
                                      <td>'.$student_info['sname'].'</td>
                                      <th>Course</th>
                                      <td>'.$row['grade'].' '.$row['yearofstart'].'-'.$row['yearofend'].'</td>
                                  </tr>
                                  <tr>
                                      <th>Contact</th>
                                      <td>'.$student_info['contact'].'</td>
                                      <th>Email Id</th>
                                      <td>'.$student_info['emailid'].'</td>
                                  </tr>
                              </table>';
                    
                    // Fetch and display fee transactions for the student
                    $sql_fee_transactions = "SELECT * FROM fees_transaction WHERE stdid='$stdid'";
                    $result_fee_transactions = $conn->query($sql_fee_transactions);
                    
                    if ($result_fee_transactions->num_rows > 0) {
                        echo '<h4>Fee Info</h4>
                              <div class="table-responsive">
                                  <table class="table table-bordered">
                                      <thead>
                                          <tr>
                                              <th>Receipt No</th>
                                              <th>Date</th>
                                              <th>Semester</th>
                                              <th>Payment Mode</th>
                                              <th>Amount</th>
                                              <th>Fee Type</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                      <div>';
                        
                        $totalPaid = 0;
                        while ($fee_transaction = $result_fee_transactions->fetch_assoc()) {
                            $totalPaid += $fee_transaction['paid'];
                            echo '<tr>
                                    <td>'.$fee_transaction['recno'].'</td>
                                    <td>'.date("d-m-Y", strtotime($fee_transaction['submitdate'])).'</td>
                                    <td>'.$fee_transaction['sem'].'</td>
                                    <td>'.$fee_transaction['paymentmode'].'<br>'.$fee_transaction['transid'].' '.$fee_transaction['checkno'].'</td>
                                    <td>'.$fee_transaction['paid'].'</td>
                                    <td>'.$fee_transaction['transcation_remark'].'</td>
                                  </tr>';
                        }
                        
                        echo '</tbody>
                              </table>
                            </div>';

                        // Display total fees, total paid, and balance
                        $balance = $student_info['fees'] - $totalPaid;
                        echo '<table style="width:200px;">
                                  <tr>
                                      <th>Total Fees:</th>
                                      <td>Rs. '.$student_info['fees'].'</td>
                                  </tr>
                                  <tr>
                                      <th>Total Paid:</th>
                                      <td>Rs. '.$totalPaid.'</td>
                                  </tr>
                                  <tr>
                                      <th>Balance:</th>
                                      <td>Rs. '.$balance.'</td>
                                  </tr>
                              </table>';
                    } else {
                        echo 'No fee transactions found for this student.';
                    }
                } else {
                    echo 'Student information not found.';
                }
            }
        ?>
        </div>
        </div>
    </div>
        </div>
        
       
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="js/bootstrap.js"></script>
    <script>
function CallPrint(strid) {
    var prtContent = document.getElementById("exampl");
    var collegeLogoUrl = "img/logo.png";
    var WinPrint = window.open("", "", "height=1000, width=1000");
    WinPrint.document.write("<html>");
    WinPrint.document.write("<style>.exampl{border: 1px solid #ccc;padding: 20px;margin: 20px;} table {width: 100%;border-collapse: collapse;margin-top: 20px;} th, td {border: 1px solid #ddd; padding: 8px; text-align: left;} th { background-color: #f2f2f2;} h3{margin-top: 20px;} body {background-image: url('" + collegeLogoUrl + "'); background-repeat: no-repeat; background-size: contain; background-position: center; height: 100%; margin: 0; padding: 0;}</style>");
    WinPrint.document.write("<body>");
    WinPrint.document.write("<h3 align='center'>Sri Sankara Bagavathi Arts And Science College,Kommadikottai </h3>");
    WinPrint.document.write(prtContent.innerHTML);
    WinPrint.document.write("</body></html>");
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
    WinPrint.close();
}
</script>

<div class="modal-footer">
    <button type="button" class="btn btn-success" style="border-radius:0%" data-dismiss="modal" OnClick="CallPrint(this.value)" >Print</button>
        </div>
</body>
</html>
