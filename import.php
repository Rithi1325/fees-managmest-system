<?php
// session_start(); // Start the session if not already started

include('php/dbconnect.php');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_POST['save_excel_data'])) {
    $fileName = $_FILES['import_file']['name'];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

    $allowed_ext = ['xls', 'csv', 'xlsx'];

    if (in_array($file_ext, $allowed_ext)) {
        $inputFileNamePath = $_FILES['import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $errormsg = false; // Initialize error message variable

        foreach ($data as $count => $row) {
            if ($count > 0) {
                // Sanitize and validate input (consider using prepared statements)
                $roll = mysqli_real_escape_string($conn, $row['0']);
                $sname = mysqli_real_escape_string($conn, $row['1']);
                $gender = mysqli_real_escape_string($conn, $row['2']);
                $dob = mysqli_real_escape_string($conn, $row['3']);
                $contact = mysqli_real_escape_string($conn, $row['4']);
                $emailid = mysqli_real_escape_string($conn, $row['5']);
                $about = mysqli_real_escape_string($conn, $row['6']);
                $grade = mysqli_real_escape_string($conn, $row['7']);
                $classfee = mysqli_real_escape_string($conn, $row['8']);
                $sem = mysqli_real_escape_string($conn, $row['9']);
                $bus = mysqli_real_escape_string($conn, $row['10']);
                $busfee = mysqli_real_escape_string($conn, $row['11']);
                $van = mysqli_real_escape_string($conn, $row['12']);
                $vanfee = mysqli_real_escape_string($conn, $row['13']);
                $skill = mysqli_real_escape_string($conn, $row['14']);
                $skillfee = mysqli_real_escape_string($conn, $row['15']);
                $other = mysqli_real_escape_string($conn, $row['16']);
                $otherfee = mysqli_real_escape_string($conn, $row['17']);
                $fees = mysqli_real_escape_string($conn, $row['18']);
                $balance = mysqli_real_escape_string($conn, $row['18']);

                // Corrected SQL query with backticks around column names
                $studentQuery = "INSERT INTO student (`roll_no`, `emailid`, `sname`, `gender`, `dob`, `sem`, `about`, `contact`, `classfee`, `bus`, `busfee`, `van`, `vanfee`, `skill`, `skillfee`, `other`, `otherfee`, `fees`, `grade`, `balance`) VALUES ('$roll','$emailid','$sname','$gender','$dob','$sem','$about','$contact','$classfee','$bus','$busfee','$van','$vanfee','$skill','$skillfee','$other','$otherfee','$fees','$grade','$balance')";
                $result = mysqli_query($conn, $studentQuery);

                if (!$result) {
                    $errormsg = true; // Set error message if query fails
                    break; // Exit the loop if an error occurs
                }
            }
        }

        if ($errormsg) {
            $_SESSION['message'] = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>File not imported</div>";
        } else {
            $_SESSION['message'] = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Success!</strong> File has been imported</div>";
        }

        header('Location: student.php');
        exit(0);
    } else {
        $_SESSION['message'] = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Choose any XLS, CSV, or XLSX file only</div>";
        header('Location: student.php');
        exit(0);
    }
}
?>
