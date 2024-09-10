<?php
include("php/dbconnect.php");
$error = '';

// Start session
// session_start();
$username = isset($_GET['username']) ? $_GET['username'] : '';
$email = isset($_GET['email']) ? $_GET['email'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';

// Check if OTP form is submitted
if(isset($_POST['verify_otp'])) {
    // Validate OTP
    $enteredOTP = mysqli_real_escape_string($conn, $_POST['otp']);
    $expectedOTP = isset($_SESSION['reset_otp']) ? $_SESSION['reset_otp'] : '';
    $username = isset($_GET['username']) ? $_GET['username'] : '';
    $email = isset($_GET['email']) ? $_GET['email'] : '';
    $id = isset($_GET['id']) ? $_GET['id'] : '';

    if($enteredOTP == $expectedOTP) {
            header("Location: reset_password_form.php?id=$id&username=$username&emailid=$email");
            exit();
    } 
    else {
        $error = "Invalid OTP. Please try again.";
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
            <h3 class="myhead">OTP Verification</h3>

            <!-- Display success message if OTP is sent to the email -->
            
                <div class="alert alert-info">OTP has been sent to your registered email: <?= $email ?></div>
            
            <!-- Display error message if any -->
            <?php if($error != ''): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <!-- OTP Verification Form -->
            <form role="form" action="otp_verification.php?id=<?= $id ?>&username=<?= $username ?>&email=<?= $email ?>" method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
            <input type="hidden" name="username" value="<?= htmlspecialchars($username) ?>">
            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">

                <div class="form-group input-group">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input type="text" class="form-control" placeholder="Enter OTP" name="otp" required />
                </div>
                <button class="btn btn-primary" type="submit" name="verify_otp">Verify OTP</button>

                <!-- Back to Login Button -->
                <a href="login.php" class="btn btn-back">Back to Login</a>
            </form>
        </div>
    </div>

    <!-- BOOTSTRAP SCRIPTS -->
    <script src="js/bootstrap.js"></script>
</body>
</html>
