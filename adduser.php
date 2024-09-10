<?php
$page = 'adduser';
include("php/dbconnect.php");
include("php/checklogin.php");
$error = '';

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['emailid']);
    $password = md5($_POST['inputpwd']); // Note: MD5 is not recommended for password hashing, consider using stronger encryption like bcrypt.
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    
    // Check if the username already exists
    $q = "SELECT * FROM user WHERE username = '$username'";
    $res = mysqli_query($conn, $q);

    // Check if any rows are returned
    if (mysqli_num_rows($res) > 0) {
        // Username already exists
        echo "<script>alert('Username already taken');</script>";
        header("Location: adduser.php?act=3");
        exit(); // Exit the script to prevent further execution
    }

    // Proceed to insert the user if the username doesn't exist
    $query = "INSERT INTO user(username, password, name, emailid, type) VALUES ('$username', '$password', '$name', '$email', '$type')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header("Location: adduser.php?act=1");
        exit();
    } else {
        header("Location: adduser.php?act=2");
        exit();
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
<body>
<?php include("php/header.php"); ?>
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Add User</h1>

                <?php
                if (isset($_REQUEST['act']) && @$_REQUEST['act'] == '1') {
                    echo '<div class="alert alert-success">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Success!</strong> User details added successfully.
                          </div>';
                }
                if (isset($_REQUEST['act']) && @$_REQUEST['act'] == '2') {
                    echo '<div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            Something went wrong. Please try again.
                          </div>';
                }
                if (isset($_REQUEST['act']) && @$_REQUEST['act'] == '3') {
                    echo '<div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            Username already taken. Please try again.
                          </div>';
                }
                echo $error;
                ?>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-10 col-sm-offset-1">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        Fill the Info
                                    </div>
                                    <form action="adduser.php" method="post" id="adduser" class="form-horizontal">
                                        <div class="panel-body">
                                        <div class="form-group">
                                        <label class="col-sm-4 control-label">Type Of User</label>
                                            <div class="col-sm-7">
                                                <label class="control-label">
                                                    <input type="radio" name="type" value="admin"> Admin
                                                </label>
                                                <label class="control-label">
                                                    <input type="radio" name="type" value="user" checked> User
                                                </label>
                                            </div>
                                        </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="exampleInputusername">Username (used for login)</label>
                                                <div class="col-sm-7">
                                                    <input type="text" name="username" id="username" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="exampleInputFullname">Full Name</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="name" name="name">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="exampleInputEmail1">Email address</label>
                                                <div class="col-sm-7">
                                                    <input type="email" class="form-control" id="emailid" name="emailid">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="exampleInputPassword1">Password</label>
                                                <div class="col-sm-7">
                                                    <input type="password" class="form-control" id="inputpwd" name="inputpwd">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-9 col-sm-offset-4">
                                                <button type="submit" class="btn btn-success" name="submit" style="border-radius:0%">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<!-- BOOTSTRAP SCRIPTS -->
<script src="js/bootstrap.js"></script>
<!-- METISMENU SCRIPTS -->
<script src="js/jquery.metisMenu.js"></script>
<!-- CUSTOM SCRIPTS -->
<script src="js/custom1.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#adduser").validate({
            rules: {
                username: {
                    required: true,
                    minlength: 4,
                    // remote: {
                    //     url: "adduser.php",
                    //     type: "post",
                    //     data: {
                    //         username: function () {
                    //             return $("#username").val();
                    //         }
                    //     }
                    // }
                },
                name: "required",
                emailid: {
                    required: true,
                    email: true
                },
                inputpwd: {
                    required: true,
                    minlength: 6
                },
            //     "type": {
            //     required: true
            // }
            },
            messages: {
                username: {
                    required: "Please enter a username",
                    minlength: "Username must be at least 4 characters long",
                    remote: function() {
                    return $("#username").val() + " is " + (this.previousValue === "available" ? "available" : "taken");
                }
                },
                name: "Please enter a name for the user",
                emailid: {
                    required: "Please enter an email address",
                    email: "Please enter a valid email address"
                },
                inputpwd: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 6 characters long"
                },
                // "type": "Please select the type of user"
            },
            errorElement: "em",
            errorPlacement: function (error, element) {
                error.addClass("help-block");
                element.parents(".col-sm-7").addClass("has-feedback");
                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.parent("label"));
                } else {
                    error.insertAfter(element);
                }
                if (!element.next("span")[0]) {
                    $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
                }
            },
            success: function (label, element) {
                if (!$(element).next("span")[0]) {
                    $("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents(".col-sm-7").addClass("has-error").removeClass("has-success");
                $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".col-sm-7").addClass("has-success").removeClass("has-error");
                $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
            }
        });

        $("#username").keyup(function() {
        validator.element("#username"); // Trigger validation for the username field
    });
    });
</script>
</body>
</html>
