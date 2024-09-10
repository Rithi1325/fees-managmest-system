<?php $page='profile';
include("php/dbconnect.php");
include("php/checklogin.php");
$error='';
// Database Connection
//Validating Session
if(isset($_POST['update'])){
$name=$_POST['name'];
$uname=$_POST['username'];
$pass=$_POST['password'];
$email=$_POST['emailid'];
$adminid=intval($_SESSION['id']);
$query=mysqli_query($conn,"update user set name='$name',username='$uname',password='$pass',emailid='$email' where  ID='$adminid'");
if($query){
echo "<script>alert('Profile details updated successfully.');</script>";
echo "<script type='text/javascript'> document.location = 'profile.php'; </script>";
} else {
echo "<script>alert('Something went wrong. Please try again.');</script>";
}
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
	
	<script type="text/javascript" src="js/validation/jquery.validate.min.js"></script>
	
</head>
<?php
include("php/header.php");
?>
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-head-line">Profile</h1>
<?php 
$adminid=intval($_SESSION['id']);
$query=mysqli_query($conn,"select * from user where  id='$adminid'");
$cnt=1;
while($result=mysqli_fetch_array($query)){
?>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-8">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Update  the Info</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form name="subadmin" method="post">
                <div class="card-body">
<!-- Username-->
    <div class="form-group">
                    <label for="exampleInputusername">Username (used for login)</label>
               <input type="text"   name="sadminusername" id="sadminusername" class="form-control" value="<?php echo $result['username'];?>" readonly>
                  </div>
<!-- admin Full Name--->
   <div class="form-group">
                    <label for="exampleInputFullname">Name</label>
                    <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo $result['name'];?>" placeholder="Enter Sub-Admin Full Name" required>
                  </div>
<!--  admin Email---->
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control" id="emailid" name="emailid" placeholder="Enter email" required value="<?php echo $result['emailid'];?>">
                  </div>
<!--  admin Contact Number---->


<?php } ?>
      
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary" name="update" id="update">Update</button>
                </div>
              </form>
            </div>
            <!-- /.card -->

        
       
          </div>
          <!--/.col (left) -->
  
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php include_once('includes/footer.php');?>

</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- bs-custom-file-input -->
<script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
<!-- Page specific script -->
<script>
$(function () {
  bsCustomFileInput.init();
});
</script>
</body>
</html>
<?php ?>
