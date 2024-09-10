<?php $page='print';
include("php/dbconnect.php");
include("php/checklogin.php");
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
		
		 <style>
			
@media print {
    .nav-bar,.span-left,.no-print  { display: none; }
	.hidden-name{ display:inherit}
}
			</style>
	
</head>
<?php 
include("php/header.php");
?>
<div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-head-line">Print Recipt
						
						</h1>

                    </div>
                </div>
<?php
// $c=$_POST['c'];
//print_r($p);



$sid = (isset($_POST['student']))?mysqli_real_escape_string($conn,$_POST['student']):'';
$sql = "select * from fees_transaction  where stdid='".$sid."'";
$fq = $conn->query($sql);
if($fq)
{


 $sql = "select s.id,s.sname,s.balance,s.fees,s.contact,b.grade,s.joindate,c.recno from student as s,grade as b,fees_transaction as c where b.id=s.grade  and s.id='".$sid."' and c.stdid='".$sid."'";
$sq = $conn->query($sql);
$sr = $sq->fetch_assoc();


echo '<div id="exampl">
<h2 align="center">SRI SANKARA BAGAVATHI ARTS ANS SCIENCE COLLEGE</h2>
<h4>Student Info</h4>
<div class="table-responsive">
<table class="table table-bordered">
<tr>
<th>Full Name</th>
<td>'.$sr['sname'].'</td>
<th>Class</th>
<td>'.$sr['grade'].'</td>
</tr>
<tr>
<th>Contact</th>
<td>'.$sr['contact'].'</td>
<th>Joined On</th>
<td>'.date("d-m-Y", strtotime($sr['joindate'])).'</td>
</tr>


</table>
</div>
';


echo '
<h4>Fee Info</h4>
<div class="table-responsive">
<table class="table table-bordered">
    <thead>
      <tr>
        <th>Reciept No</th>
        <th>Date</th>
        <th>Paid</th>
        <th>Fee Type</th>
      </tr>
    </thead>
    <tbody>';
	$totapaid = 0;
while($res = $fq->fetch_assoc())
{
$totapaid+=$res['paid'];
        echo '<tr>
        <td>'. $res['recno'].'</td>
        <td>'.date("d-m-Y", strtotime($res['submitdate'])).'</td>
        <td>'.$res['paid'].'</td>
        <td>'.$res['transcation_remark'].'</td>
      </tr>' ;
}
      
echo '	  
    </tbody>
  </table>
 </div> 
 
<table style="width:150px;border: 1px;" >
<tr>
<th>Total Fees: 
</th>
<td>'.'Rs. '.$sr['fees'].'
</td>
</tr>

<tr>
<th>Total Paid: 
</th>
<td>'.'Rs. '.$totapaid.'
</td>
</tr>

<tr>
<th>Balance: 
</th>
<td>'.'Rs. '.$sr['balance'].'
</td>
</tr>
</table>
</div>
<p style="margin-top:1%"  align="center">
  <i class="fa fa-print fa-2x" style="cursor: pointer;"  OnClick="CallPrint(this.value)" ></i>
</p>

<script>
function CallPrint(strid) {
  var prtContent = document.getElementById("exampl");
  var WinPrint = window.open("", "", "left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0");
  WinPrint.document.write(prtContent.innerHTML);
  WinPrint.document.close();
  WinPrint.focus();
  WinPrint.print();
  WinPrint.close();
  }
  </script>
 ';


 }
else
{
echo 'No fees submit.';
}
 







$header="Sri Sankara Bhagavathi Art's Ans Science College ";
?>
<div class="main-container">
	<div class="post-header" style="display:block;float:center;">
		
		<span style="display:block;float:center;">
			<?php echo $header ?>
		</span>
	</div>
	<div class="post-content">
		<div class="post-text">
		<?php
// Assuming you have a database connection established

// Retrieve fees form values from the database (replace 'fees_table' with your actual table name)
$query = "SELECT * FROM fees_transaction as a,student as b";
$result = mysqli_query($conn, $query);

// Check if there are results
if ($result) {
    // Loop through the results and print each fees entry
    if ($row = mysqli_fetch_assoc($result)) {
        echo "Student Name: " . $row['sname'] . "<br>";
        echo "Amount Paid: $" . $row['paid'] . "<br>";
        // Add more lines for other fees form fields

        echo "<hr>"; // Optional: Add a horizontal line between entries
    }

    // Free the result set
    mysqli_free_result($result);
} else {
    echo "Error retrieving fees form data from the database.";
}

// Close the database connection
mysqli_close($conn);
?>

			<!-- <div class="box-left">
				Name:
				<span class="form-text">
					<b><?php echo $c['sname'];?></b>
				</span>
			</div>
			<div class="box-right">
				Roll Number:
				<span class="form-text">
					<b><?php echo $c['Gr'];?></b>
				</span>
			</div>
			<div class="box-left">
				Name:
				<span class="form-text">
					<b><?php echo $c['det'][0];?></b>
				</span>
			</div>
			<div class="box-right">
				Batch:
				<span class="form-text">
					<b><?php echo $c['det'][1];?></b>
				</span>
			</div>
			<div class="box-left">
				Course:
				<span class="form-text">
					<b><?php echo $c['det'][3];?></b>
				</span>
			</div>
			<div class="box-right">
				Year:
				<span class="form-text">
					<b><?php echo $c['det'][2];?></b>
				</span>
			</div>
			<div class="box-left">
				Session:
				<span class="form-text">
					<b><?php echo date('Y');?></b>
				</span>
			</div>
			<div class="box-right">
				Fee Type:
				<span class="form-text">
					<b><?php print_r($c['typ']);?></b>
				</span>
			</div>
			
			<div class="box-left">
				Payment Mode:
				<span class="form-text">
					<b><?php echo $c['pay_mode'];?></b>
				</span>
			</div>
			<div class="box-right">
				Cheque Number
				<span class="form-text">
					<b><?php echo $c['chq'];?></b>
				</span>
			</div>
			<ul class="table-view">
				<li class="table-view-header" style="width:120px;">
					Month
				</li>
				<li class="table-view-header" style="width:120px;">
					Amount
				</li>
				<li class="table-view-header" style="width:120px;">
					Late Fee
				</li>
				<li class="table-view-header" style="width:120px;">
					Date
				</li>
			</ul>
		
			<?php 
				$late_fee=0;
				$fee=0;
				if($c['month']!='One time'){
					foreach($c['month'] as $mon){
						?>
						<ul class="table-view">
							<li style="width:120px;" >
								<?php echo $mon ;?>
							</li>
							<li style="width:120px;" >
								<?php echo $c['amount'];
									$fee=$c['amount'];
								?>
							</li>
							<li style="width:120px;">
								<?php $count=0 ;
								if($c['ot']!='No'){
									foreach($c['ot'] as $late){
											if($late==$mon){
												$count++;
											}
									}
									if($count==1){
										echo $c['lfee'];
										$late_fee=$c['lfee']+$late_fee;
									}
									else{
										echo('0');
									}
								}
								else{
									echo('0');
								}
								?>
							</li>
							<li style="width:120px;">
								<?php echo date('d-m-Y');?>
							</li>
						</ul>
						<?php
					}
				}
				else{
				?>
					<ul class="table-view">
							<li style="width:120px;" >
								<?php echo $c['month'] ;?>
							</li>
							<li style="width:120px;" >
								<?php echo $c['amount'];
									$fee=$c['amount']+$fee;
								?>
							</li>
							<li style="width:120px;">
								<?php if($c['ot']=='no'){
										echo $c['lfee'];
										$late_fee=$c['lfee']+$late_fee;
									}
									else{
										echo ('0');	
									}
								?>
							</li>
							<li style="width:120px;">
								<?php echo date('d-m-Y');?>
							</li>
						</ul>
				<?php }
			?>
			<ul class="table-view">
							<li style="width:120px;" >
								
							</li>
							<li style="width:120px;" class="table-view-header" >
								<?php echo "Amount: ".$fee;?>
							</li>
							<li style="width:120px;" class="table-view-header">
								<?php echo "Late fee:".$late_fee;?>
							</li>
							<li style="width:120px;" class="table-view-header">
								<?php echo ("Total Amount: "); echo($late_fee+$fee);?>
							</li>
						</ul> -->
		</div>
	</div>
	<button class="no-print print_add_fee"><span>Print</span></button>