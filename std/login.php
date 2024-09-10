<?php

include("../php/dbconnect.php");

$error = '';

if(isset($_POST['login'])) {
    // Sanitize user inputs
    $regno = mysqli_real_escape_string($conn, trim($_POST['regno']));
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);

    // Check if required fields are filled
    if($regno == '' || $dob == '') {
        $error = 'All fields are required';
    } else {
        // Fetch student ID associated with the provided email ID and DOB
        $sql_stdid = "SELECT id FROM student WHERE roll_no='$regno' AND dob='$dob'";
        $q_stdid = $conn->query($sql_stdid);

        if($q_stdid) {
            if($q_stdid->num_rows > 0) {
                // Student found, fetch their IDs
                $stdids = array();
                while($row = $q_stdid->fetch_assoc()) {
                    $stdids[] = $row['id'];
                }
                
                // Store data in session variables
                $_SESSION['rainbow_regno'] = $regno;
                $_SESSION['rainbow_stdids'] = $stdids;

                // Redirect to logged-in page
                header("Location: index.php");
                exit();
            } else {
                $error = 'No matching record found. Please check your credentials.';
            }
        } else {
            $error = 'Database error: ' . $conn->error;
        }
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
    <link href="../css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES -->
    <link href="../css/font-awesome.css" rel="stylesheet" />
    <!-- GOOGLE FONTS -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

    <style>
        body {
            background-color: #f5f5f5;
        }

        .container {
            max-width: 400px;
            margin-top: 50px;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .panel-body {
            background-color: #fff;
            padding: 20px;
        }

        .myhead {
            text-align: center;
            margin-bottom: 20px;
            font-family: 'Open Sans', sans-serif;
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

        /* .input-group-addon {
            border: none;
            border-radius: 0;
            padding: 8px 15px;
            background-color: #5cb85c;
            color: #fff;
            font-weight: bold;
        } */

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
        }

        .forgot-password a:hover {
            text-decoration: none;
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
                        <input type="text" class="form-control" placeholder="Reg No" name="regno" required />
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                        <input type="date" class="form-control" placeholder="DOB" name="dob" required />
                    </div>
                </div>

                <button class="btn btn-login" type="submit" name="login">Login</button>

                <!-- <div class="forgot-password">
                    <a href="forgot_password.php">Forgot Password?</a>
                </div> -->
            </form>
        </div>
    </div>

    <!-- BOOTSTRAP SCRIPTS -->
    <script src="js/bootstrap.js"></script>
</body>
</html>
