<?php
include("php/dbconnect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['student_id'])) {
    $studentId = mysqli_real_escape_string($conn, $_POST['student_id']);
    
    // Fetch student details along with grade details from the database
    $sql = "SELECT s.*, g.grade, g.yearofstart, g.yearofend 
            FROM student s 
            JOIN grade g ON s.grade = g.id 
            WHERE s.id = '$studentId'";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Prepare JSON response
            $response = array(
                'id' => $row['id'],
                'roll_no' => $row['roll_no'],
                'sname' => $row['sname'],
                'grade' => $row['grade'],
                'yearofstart' => $row['yearofstart'],
                'yearofend' => $row['yearofend'],
                'sem' => $row['sem'],
                'fees' => $row['fees'],
            );
            echo json_encode($response);
        } else {
            echo json_encode(array('error' => 'No student details found for the given ID.'));
        }
    } else {
        echo json_encode(array('error' => 'Error executing the SQL query: ' . $conn->error));
    }
} else {
    echo json_encode(array('error' => 'Invalid request.'));
}
?>
