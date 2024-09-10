<?php

include('php/dbconnect.php');


if (isset($_POST['grade_id'])) {
    $gradeId = mysqli_real_escape_string($conn, $_POST['grade_id']);

    // Fetch grade amount
    $sqlGrade = "SELECT amount FROM grade WHERE id = $gradeId";
    $resultGrade = $conn->query($sqlGrade);

    if ($resultGrade) {
        // Fetch the amount from the result
        $rowGrade = $resultGrade->fetch_assoc();
        $amountGrade = $rowGrade['amount'];

        // Prepare JSON response
        $response = array(
            'success' => true,
            'amount' => $amountGrade
        );
        echo json_encode($response);
    } else {
        // If query execution fails
        $response = array(
            'success' => false,
            'message' => 'Failed to fetch grade details'
        );
        echo json_encode($response);
    }
} elseif (isset($_POST['bus_id'])) {
    $busId = mysqli_real_escape_string($conn, $_POST['bus_id']);

    // Fetch bus fees
    $sqlBus = "SELECT fees FROM bus_fees WHERE id = $busId";
    $resultBus = $conn->query($sqlBus);

    if ($resultBus) {
        // Fetch the fees from the result
        $rowBus = $resultBus->fetch_assoc();
        $feesBus = $rowBus['fees'];

        // Prepare JSON response
        $response = array(
            'success' => true,
            'fees' => $feesBus
        );
        echo json_encode($response);
    } else {
        // If query execution fails
        $response = array(
            'success' => false,
            'message' => 'Failed to fetch bus details'
        );
        echo json_encode($response);
    }
} elseif (isset($_POST['van_id'])) {
    $vanId = mysqli_real_escape_string($conn, $_POST['van_id']);

    // Fetch van fees
    $sqlVan = "SELECT fees FROM van_fees WHERE id = $vanId";
    $resultVan = $conn->query($sqlVan);

    if ($resultVan) {
        // Fetch the fees from the result
        $rowVan = $resultVan->fetch_assoc();
        $feesVan = $rowVan['fees'];

        // Prepare JSON response
        $response = array(
            'success' => true,
            'fees' => $feesVan
        );
        echo json_encode($response);
    } else {
        // If query execution fails
        $response = array(
            'success' => false,
            'message' => 'Failed to fetch van details'
        );
        echo json_encode($response);
    }
} elseif (isset($_POST['skill_id'])) {
    $skillId = mysqli_real_escape_string($conn, $_POST['skill_id']);

    // Fetch skill fees
    $sqlSkill = "SELECT fees FROM skill_fees WHERE id = $skillId";
    $resultSkill = $conn->query($sqlSkill);

    if ($resultSkill) {
        // Fetch the fees from the result
        $rowSkill = $resultSkill->fetch_assoc();
        $feesSkill = $rowSkill['fees'];

        // Prepare JSON response
        $response = array(
            'success' => true,
            'fees' => $feesSkill
        );
        echo json_encode($response);
    } else {
        // If query execution fails
        $response = array(
            'success' => false,
            'message' => 'Failed to fetch skill details'
        );
        echo json_encode($response);
    }
} else {
    // If required ID is not provided in the POST request
    $response = array(
        'success' => false,
        'message' => 'ID not provided'
    );
    echo json_encode($response);
}

if(isset($_POST['courseId'])) {
    // Retrieve courseId from POST data
    $courseId = $_POST['courseId'];

    // Fetch semesters based on courseId
    $sql = "SELECT sem FROM grade WHERE id = $courseId"; // Assuming 'sem' is the column in your 'grade' table
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $semCount = intval($row['sem']);

        // Generate option values for Semester dropdown
        $options = '';
        for ($i = 1; $i <= $semCount; $i++) {
            $selected = '';
            if(isset($_POST['semester']) && $_POST['semester'] == $i) {
                $selected = 'selected';
            }
            $options .= "<option value='$i' $selected>$i</option>";
        }

        echo $options;
    } else {
        echo "<option value=''>No Semesters found</option>";
    }
}


// Close connection
$conn->close();
?>
