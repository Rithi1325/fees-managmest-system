<?php
    include("php/dbconnect.php");

    $error = '';
    if(isset($_POST['login']))
    {

    $username =  mysqli_real_escape_string($conn,trim($_POST['username']));
    $password =  mysqli_real_escape_string($conn,$_POST['password']);

    if($username=='' || $password=='')
    {
    $error='All fields are required';
    }

    $sql = "select * from user where username='".$username."' and password = '".md5($password)."'";

    $q = $conn->query($sql);
    if($q->num_rows==1)
    {
    $res = $q->fetch_assoc();
    $_SESSION['rainbow_username']=$res['username'];
    $_SESSION['rainbow_uid']=$res['id'];
    $_SESSION['rainbow_name']=$res['name'];
    $_SESSION['typeUser']=$res['type'];
    // $typeUser=$res['type'];
    if($_SESSION['typeUser'] == 'admin' || $_SESSION['typeUser'] == 'user') {
        header("Location: index.php"); // Redirect to admin dashboard
        exit();
    } else {
        $error = 'Invalid User Authentication'; // Handle invalid user type
    }
} else {
    $error = 'Invalid Username or Password'; // Handle invalid credentials
}
}


?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fees Management System For SSB college - Kommadikottai</title>

    <!-- BOOTSTRAP STYLES -->
    <link href="css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES -->
    <link href="css/font-awesome.css" rel="stylesheet" />
    <!-- GOOGLE FONTS -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

    <style>
        body {
            background-color: #f5f5f5;
        }

        .container {
            max-width: 400px;
            margin-top: 50px;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .panel-body {
            background-color: rgba(255, 255, 255, 0.9); /* Decrease the contrast */
            padding: 20px;
        }

        .myhead {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 3px;
            box-shadow: none;
        }

        .btn-login {
            border-radius: 0;
            background-color: #5cb85c;
            color: #fff;
            width: 100%;
            border: 1px solid #4cae4c;
            transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-login:hover {
            background-color: #4cae4c;
            border-color: #4cae4c;
        }

        .forgot-password {
            text-align: center;
            margin-top: 20px;
            color: #3498db; /* Blue color */
        }

        .forgot-password a {
            color: #3498db; /* Blue color */
            text-decoration: none;
            font-weight: bold;
            font-family: "Times New Roman", Times, serif;
            font-size: 16px;
        }

        .forgot-password a:hover {
            text-decoration: none;
            color: black;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="panel-body">
            <h3 class="myhead">SSB Fees Management System</h3>
            <form role="form" action="login.php" method="post">
                <?php if ($error != ''): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input type="text" class="form-control" placeholder="Username" name="username" required />
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                        <input type="password" class="form-control" placeholder="Password" name="password" required />
                    </div>
                </div>

                <button class="btn btn-login" type="submit" name="login">Login</button>

                <div class="forgot-password">
                    <a href="forgot_password.php">Forgot Password?</a><br/>
                    <a href="std/login.php">Student Login</a>
                </div>
            </form>
        </div>
    </div>

    <!-- BOOTSTRAP SCRIPTS -->
    <script src="js/bootstrap.js"></script>
</body>
</html>
