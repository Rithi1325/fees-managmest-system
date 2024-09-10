<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include("php/dbconnect.php");

$error = '';
$success = '';

// Function to generate OTP
function generateOTP($length = 6) {
    return substr(str_shuffle(str_repeat($x='0123456789', ceil($length/strlen($x)) )),1,$length);
}

if(isset($_POST['reset_password'])) {
    require 'PHPMailer-master\src\Exception.php';
    require 'PHPMailer-master\src\PHPMailer.php';
    require 'PHPMailer-master\src\SMTP.php';
    
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));

    // Check if the username exists
    $sql = "SELECT * FROM user WHERE username = '$username'";
    $result = $conn->query($sql);

    if($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $email = $user['emailid'];
        
    $_SESSION['rainbow_username']=$user['username'];
    $_SESSION['rainbow_uid']=$user['id'];

        // Generate OTP
        $otp = generateOTP();

        // Send OTP to user's email
        $mail = new PHPMailer();
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'haritharshinibscit@gmail.com'; // Your Gmail email address
            $mail->Password   = 'gmpz lmrf upvc bqql';  // Your Gmail password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('haritharshinit@gmail.com', 'Hari Tharshini');
            $mail->addAddress($email, $username);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset OTP';
            $mail->Body    = "<h1>Your OTP:</h1><br><h2>$otp</h2>";

            $mail->send();

            // Store OTP in session for verification
            session_start();
            $_SESSION['reset_otp'] = $otp;

            // Redirect to OTP verification page
            header("Location: otp_verification.php?id=$id&username=$username&email=$email");
            exit();
        } catch (Exception $e) {
            $error = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $error = "Username not found.";
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


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
        .note {
            background-color: #ffffcc; /* Yellow background color */
            border: 1px solid #cccc00; /* Yellow border color */
            padding: 5px; /* Reduced padding */
            border-radius: 3px; /* Rounded corners */
            margin-bottom: 10px; /* Reduced margin */
            display: inline-flex; /* Display as inline-flex for smaller size */
            align-items: center; /* Align items vertically */
        }

        .note i {
            font-size: 14px; /* Reduced icon size */
            margin-right: 5px; /* Spacing between icon and text */
            color: #666; /* Icon color */
        }

        .note-content {
            font-size: 14px; /* Reduced font size */
            color: #333; /* Text color */
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="panel-body">
            <h3 class="myhead">Forgot Password</h3>

            <!-- Display success or error messages -->
            <?php if($success != ''): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <?php if($error != ''): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <!-- Forgot Password Form -->
            <form role="form" action="forgot_password.php" method="post">
                <div class="form-group input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" class="form-control" placeholder="Username" name="username" required />
                </div>
                <!-- <div class="note">
                    <i class="fas fa-info-circle"></i> 
                    <span class="note-content">Please enter your username. A verification email will be sent to your registered email address to proceed with the password reset.</span>
                </div> -->
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
