<?php
include("php/dbconnect.php");
include("php/checklogin.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master\src\Exception.php';
require 'PHPMailer-master\src\PHPMailer.php';
require 'PHPMailer-master\src\SMTP.php';

// Assuming you have fetched student records from your database
// Replace this with your database connection and query logic
$query = "SELECT student.*, grade.grade ,grade.yearofstart ,grade.yearofend 
          FROM student 
          JOIN grade ON student.grade = grade.id 
          WHERE student.delete_status = '0'
          LIMIT 5000";
$result = mysqli_query($conn, $query);

if ($result) {
    $student_records = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Loop through the student records
foreach ($student_records as $student) {
    $balance = $student['balance'];
    $email = $student['emailid'];
    $name = $student['sname'];
    $class = $student['grade'] . ' ' . $student['yearofstart'] . '-' . $student['yearofend'];
    $sem = $student['sem'];
    $fees = $student['fees'];
    $paid = $student['classpaid'] + $student['buspaid'] + $student['vanpaid'] + $student['skillpaid'] + $student['otherpaid'];

    if ($balance != 0) {
        $mail = new PHPMailer();

        $to = $email;
        $subject = "Reminder: Pending Fees Payment";
        $message = 'Dear '.$name.',<br><br>
            This is a gentle reminder regarding the pending fees payment for your course at SSB College. Kindly make the payment at your earliest convenience to avoid any inconvenience.<br><br>
            Below are the details of your pending payment:<br>
            <pre>
            <ul>
              <li><b>Student Name :</b> '.$name.'</li>
              <li><b>Course       :</b> '.$class.'</li>
              <li><b>Semester     :</b> '.$sem.'</li>
              <li><b>Total Fees   :</b> Rs.'.$fees.'</li>
              <li><b>Paid Amount  :</b> Rs.'.$paid.'</li>
              <li><b>Amount Due   :</b> Rs.'.$balance.'</li>
            </ul></pre><br>
            If you have already made the payment, please disregard this message. For any queries or assistance, feel free to contact us.<br><br>
            Best regards,<br>
            Sri Sankara Bagavathi Arts And Science College<br>
            Tisaiyanvillai - Udangudi Main Road, Kommadikottai - 628 653<br>
            Thoothukudi District, Tamilnadu<br>
            <a href="tel:04639-253605">04639-253605</a><br>
            <a href="tel:9956609113">9956609113</a>';

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'haritharshinibscit@gmail.com'; // Your Gmail username
        $mail->Password = 'gmpz lmrf upvc bqql'; // Your Gmail password
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465; // Or 587 for TLS

        $mail->setFrom('haritharshinit@gmail.com', 'Hari Tharshini');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        if ($mail->send()) {
            header("location: student.php?act=6");
        } else {
            echo "<script>alert('Error sending email to ".$name.": ".$mail->ErrorInfo."')</script>";
        }
    }
}
} else {
echo "Error fetching records: " . mysqli_error($conn);
}
?>
