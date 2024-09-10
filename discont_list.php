<?php $page='discontinue';
include("php/dbconnect.php");
include("php/checklogin.php");
$errormsg = '';
$action = "add";


if(isset($_GET['action']) && $_GET['action']=="delete"){

$conn->query("DELETE FROM student WHERE id='".$_GET['id']."'");	
header("location: discont_list.php?act=3");

}

if(isset($_GET['action']) && $_GET['action']=="approve"){

    $conn->query("UPDATE student set delete_status = '0', discont='0'  WHERE id='".$_GET['id']."'");	
    header("location: discont_list.php?act=2");
    
    }



if(isset($_REQUEST['act']) && @$_REQUEST['act']=="1")
{
$errormsg = "<div class='alert alert-success'> <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Student record has been added!</div>";
}else if(isset($_REQUEST['act']) && @$_REQUEST['act']=="2")
{
$errormsg = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Student record has been updated!</div>";
}
else if(isset($_REQUEST['act']) && @$_REQUEST['act']=="3")
{
$errormsg = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Student has been deleted!</div>";
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
                <h1 class="page-head-line">Discontinued Student List
                <?php
						echo '<a href="discont.php" class="btn btn-success btn-sm pull-right" style="border-radius:0%">Go Back </a>';
						?>
                </h1>
                <div class="row">
                    <?php
                    echo $errormsg;
                    ?>
                    <link href="css/datatable/datatable.css" rel="stylesheet" />

                    <div class="panel panel-default">
                        <div class="panel-heading">
                        Discontinued Student List
                            </div>
                        <div class="panel-body">
                             <div class="table-sorting table-responsive">

                                <table class="table table-striped table-bordered table-hover" id="tSortable21">
                                <thead>
                                <tr>
                                            <th># </th>
                                            <th>Name | Reg No</th>
                                            <th>Course</th>
                                            <th>Semester</th>
                                            <th>Email Id/Contact</th>
                                            <th>Fees</th>
											<th>Balance</th>
                                            <th>Remark</th>
                                            <th>Refund</th>
											<th>Actions</th>
                                        </tr>
                                </thead>
                                <tbody>
									<?php
									$sql = "select * from student where delete_status='1'  and discont='1'";
									$q = $conn->query($sql);
									$i=1;
									while($r = $q->fetch_assoc())
									{
                                        $sql1 = $conn->query("select * from grade where id = ".$r['grade']."");
										$row = $sql1->fetch_assoc();
									
									echo '<tr '.(($r['balance']>0)?'class="primary"':'').'>
                                            <td>'.$i.'</td>
                                            <td>'.$r['sname'].'<br/>'.$r['roll_no'].'</td>
                                            <td>'.$row['grade'].' '.$row['yearofstart'].'-'.$row['yearofend'].'</td>
                                            <td>'.$r['sem'].'</td>
                                            <td>'.$r['emailid'].'<br/>'.$r['contact'].'</td>
                                            <td>'.$r['fees'].'</td>
											<td>'.$r['balance'].'</td>
                                            <td>'.$r['discontinue_reason'].'</td>
                                            <td>'.$r['refund_amount'].'</td>
											<td>
											
											<a href="discont_list.php?action=approve&id='.$r['id'].'" class="btn btn-success btn-xs" style="border-radius:60px;"><span class="glyphicon glyphicon-ok"></span></a>
											
											<a onclick="return confirm(\'Are you sure you want to delete this record permanently?\');" href="discont_list.php?action=delete&id='.$r['id'].'" class="btn btn-danger btn-xs" style="border-radius:60px;"><span class="glyphicon glyphicon-trash"></span></a> </td>
											
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
