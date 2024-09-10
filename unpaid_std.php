<?php
$page = 'unpaid students';
include("php/dbconnect.php");
include("php/checklogin.php");
$errormsg = '';

?>




<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fees Management System For SSB college - Kommadikottai</title>

    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.css" rel="stylesheet" />
    <!-- Font Awesome CSS -->
    <link href="css/font-awesome.css" rel="stylesheet" />
    <!-- Custom CSS -->
    <link href="css/basic.css" rel="stylesheet" />
    <link href="css/custom.css" rel="stylesheet" />

    <!-- Google Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

    <!-- jQuery -->
    <script src="js/jquery-1.10.2.js"></script>

    <!-- jQuery UI CSS -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- jQuery UI JS -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

</head>
<?php
include("php/header.php");
?>
<div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-head-line">Unpaid Student
						<?php
						echo (isset($_GET['action']))?
						' <a href="unpaid_std.php" class="btn btn-success btn-sm pull-right" style="border-radius:0%">Go Back </a>':'<a href="paid_std.php" class="btn btn-success btn-sm pull-right" style="border-radius:0%">Fully Paid Students</a>';
						?>
						</h1>
                     
						<?php

						echo $errormsg;
						?>
                    </div>
                </div>
                
		 <link href="css/datatable/datatable.css" rel="stylesheet" />
		 
		
		 
		 
         <div class="panel panel-default">
                         <div class="panel-heading">
                             <b>Unpaid Student</b>
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
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
									$sql = "select * from student where delete_status='0' and fees=balance";
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
                                        </tr>';
										$i++;
									}
									
									?>
									
                                    </tbody>
                                 </table>
                             </div>
                         </div>
                     </div>

                     <div class="panel panel-default">
                         <div class="panel-heading">
                             Semester Fees - Unpaid Student
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
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
									$sql = "select * from student where delete_status='0' and classfee!='' and classpaid='0'";
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
                                        </tr>';
										$i++;
									}
									
									?>
									
                                    </tbody>
                                 </table>
                             </div>
                         </div>
                     </div>

                     <div class="panel panel-default">
                         <div class="panel-heading">
                             Bus Fees - Unpaid Student
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
                                            <th>Total Fees</th>
                                            <th>Paid Amount</th>
											<th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
									$sql = "select * from student where delete_status='0' and busfee!='' and buspaid='0'";
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
                                        </tr>';
										$i++;
									}
									
									?>
									
                                    </tbody>
                                 </table>
                             </div>
                         </div>
                     </div>

                     <div class="panel panel-default">
                         <div class="panel-heading">
                             Van Fees - Unpaid Student
                         </div>
                         <div class="panel-body">
                              <div class="table-sorting table-responsive">
 
                                 <table class="table table-striped table-bordered table-hover" id="tSortable23">
                                 <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name | Reg No</th>
											<th>Course</th>
                                            <th>Semester</th>
                                            <th>Total Fees</th>
                                            <th>Paid Amount</th>
											<th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
									$sql = "select * from student where delete_status='0' and vanfee!='' and vanpaid='0'";
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
                                        </tr>';
										$i++;
									}
									
									?>
									
                                    </tbody>
                                 </table>
                             </div>
                         </div>
                     </div>

                     <div class="panel panel-default">
                         <div class="panel-heading">
                             Skill Fees - Unpaid Student
                         </div>
                         <div class="panel-body">
                              <div class="table-sorting table-responsive">
 
                                 <table class="table table-striped table-bordered table-hover" id="tSortable24">
                                 <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name | Reg No</th>
											<th>Course</th>
                                            <th>Semester</th>
                                            <th>Total Fees</th>
                                            <th>Paid Amount</th>
											<th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
									$sql = "select * from student where delete_status='0' and skillfee!='' and skillpaid='0'";
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
                                        </tr>';
										$i++;
									}
									
									?>
									
                                    </tbody>
                                 </table>
                             </div>
                         </div>
                     </div>

                     <div class="panel panel-default">
                         <div class="panel-heading">
                             Other Fees - Unpaid Student
                         </div>
                         <div class="panel-body">
                              <div class="table-sorting table-responsive">
 
                                 <table class="table table-striped table-bordered table-hover" id="tSortable25">
                                 <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name | Reg No</th>
											<th>Course</th>
                                            <th>Semester</th>
                                            <th>Total Fees</th>
                                            <th>Paid Amount</th>
											<th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
									$sql = "select * from student where delete_status='0' and otherfee!='' and otherpaid='0'";
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
         $(document).ready(function () {
             $('#tSortable20').dataTable({
    "bPaginate": true,
    "bLengthChange": true,
    "bFilter": true,
    "bInfo": true,
    "bAutoWidth": true });
	
         });
		 
	
    </script> 
     <script>
         $(document).ready(function () {
             $('#tSortable21').dataTable({
    "bPaginate": true,
    "bLengthChange": true,
    "bFilter": true,
    "bInfo": true,
    "bAutoWidth": true });
	
         });
		 
	
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
    <script>
         $(document).ready(function () {
             $('#tSortable23').dataTable({
    "bPaginate": true,
    "bLengthChange": true,
    "bFilter": true,
    "bInfo": true,
    "bAutoWidth": true });
	
         });
		 
	
    </script> 
    <script>
         $(document).ready(function () {
             $('#tSortable24').dataTable({
    "bPaginate": true,
    "bLengthChange": true,
    "bFilter": true,
    "bInfo": true,
    "bAutoWidth": true });
	
         });
		 
	
    </script> 
    <script>
         $(document).ready(function () {
             $('#tSortable25').dataTable({
    "bPaginate": true,
    "bLengthChange": true,
    "bFilter": true,
    "bInfo": true,
    "bAutoWidth": true });
	
         });
		 
	
    </script>       
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