<?php

include("php/dbconnect.php");

$error = '';
$username = isset($_GET['username']) ? $_GET['username'] : '';
$email = isset($_GET['email']) ? $_GET['email'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';

// Check if form is submitted
if(isset($_POST['reset_password'])) {
    // Validate and update password
    $newPassword = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $username = isset($_GET['username']) ? $_GET['username'] : '';
    $email = isset($_GET['email']) ? $_GET['email'] : '';
    $id = isset($_GET['id']) ? $_GET['id'] : '';

    // echo "New Password: " . $newPassword . "<br>";
    // echo "Confirm Password: " . $confirmPassword . "<br>";
    // echo "ID: " . $id . "<br>";

    if($newPassword === $confirmPassword) {
        $hashedPassword = md5($newPassword); // Hash the password
        // echo "Hashed Password: " . $hashedPassword . "<br>";

        $sql = "UPDATE user SET password = '".$hashedPassword."' WHERE id = '".$_SESSION['rainbow_uid']."'";
        // echo "SQL Query: " . $sql . "<br>";

        if($conn->query($sql)) {
            echo '<script type="text/javascript">';
            echo 'alert("Password updated successfully");';
            echo 'setTimeout(function(){ window.location.href = "login.php"; }, 1000);'; // Redirect after 1 seconds
            echo '</script>';
            exit();
        } else {
            echo '<script type="text/javascript">';
            echo 'alert("Error updating password:  '.mysqli_error($conn).'");';
            echo '</script>';
        }
        
    } else {
        $error = "Passwords do not match. Please try again.";
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

    <!-- Your CSS Styles -->
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

        .btn-primary,
        .btn-back {
            border-radius: 0;
            width: 100%;
            transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px; /* Added spacing */
        }

        .btn-primary {
            background-color: #3498db;
            color: #fff;
            border: 1px solid #3498db;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }

        .btn-back {
            background-color: #fff;
            color: #333;
            border: 1px solid #ddd;
        }

        .btn-back:hover {
            background-color: #f5f5f5;
            border-color: #ccc;
        }

        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="panel-body">
            <h3 class="myhead">Reset Password</h3>

            <!-- Display error message if any -->
            <?php if($error != ''): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <!-- Password Reset Form -->
            <form role="form" action="reset_password_form.php?username=<?= $username ?>&email=<?= $email ?>" method="post">
                <div class="form-group input-group">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input type="password" class="form-control" placeholder="New Password" name="new_password" required />
                </div>
                <div class="form-group input-group">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password" required />
                </div>
                <button class="btn btn-primary" type="submit" name="reset_password">Reset Password</button>

                <!-- Back to Login Button -->
                <a href="login.php" class="btn btn-back">Back to Login</a>
            </form>
        </div>
    </div>

    <!-- BOOTSTRAP SCRIPTS -->
    <script src="js/bootstrap.js"></script>
</body>
</html>
