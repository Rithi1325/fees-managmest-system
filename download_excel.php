<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Establish MySQL connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "feesys";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch data for dropdown lists
// function fetchDataForDropdown($columnName, $conn)
// {
//     $data = array();
//     $sql = "SELECT DISTINCT $columnName FROM grade";
//     $result = $conn->query($sql);
//     if ($result->num_rows > 0) {
//         while ($row = $result->fetch_assoc()) {
//             $data[] = $row[$columnName];
//         }
//     }
//     return $data;
// }

// Fetch data for dropdown lists
// $grade_options = fetchDataForDropdown('grade', $conn);
// $bus_options = fetchDataForDropdown('from_location', $conn);
// $van_options = fetchDataForDropdown('from_location', $conn);
// $skill_options = fetchDataForDropdown('skill_name', $conn);

// Close MySQL connection
$conn->close();

// Create Excel file
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Add column names
$columnNames = array(
    'Reg No', 'Name', 'Gender', 'DOB(YYYY-MM-DD)', 'Contact No', 'Email Id', 'Community',
    'Course ID', 'Course Fees', 'Semester', 'Bus Route ID', 'Bus Fees', 'Van Route ID',
    'Van Fees', 'Skill ID', 'Skill Fees', 'Others Fees Type', 'Others Fees',
    'Total Fees'
);

// Set column names
foreach ($columnNames as $index => $columnName) {
    $sheet->setCellValueByColumnAndRow($index + 1, 1, $columnName);
}

// Set protection for cells A1 to T1
for ($col = 'A'; $col <= 'T'; $col++) {
    $sheet->getStyle($col . '1')->getProtection()->setLocked(false);
}

// Populate dropdowns in Excel
// $dropdownValues = array($grade_options, $bus_options, $van_options, $skill_options);
// foreach ($dropdownValues as $index => $values) {
//     $columnIndex = $index + 8; // Starting from column H (Course)
//     $dropdownFormula = '"' . implode(',', $values) . '"';
//     $dropdownCell = $sheet->getCellByColumnAndRow($columnIndex, 2)->getDataValidation();
//     $dropdownCell->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
//     $dropdownCell->setFormula1($dropdownFormula);
// }

// Save the Excel file
$filename = 'Student_Details.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($filename);

// Set headers to force download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $filename . '"');

// Send file to the browser
readfile($filename);

// Remove the file after sending
unlink($filename);

?>
