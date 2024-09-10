<?php
// MySQL database information
$host = 'localhost'; // Your MySQL host
$user = 'root'; // Your MySQL username
$pass = ''; // Your MySQL password
$db_name = 'feesys'; // Your MySQL database name


// Establish a connection to MySQL
$conn = mysqli_connect($host, $user, $pass, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create a backup file name with a timestamp
$backup_file = 'SSB Fees System backup_' . date('Y-m-d_H-i-s') . '.sql';

// Set headers for file download
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $backup_file . '"');
header('Pragma: public');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Expires: 0');

// Get the list of tables in the database
$tables_query = mysqli_query($conn, 'SHOW TABLES');
if (!$tables_query) {
    die('Error: ' . mysqli_error($conn));
}

// Initialize the SQL dump content
$sql_dump = '';

// Loop through each table and generate SQL dump
while ($row = mysqli_fetch_row($tables_query)) {
    $table_name = $row[0];
    $sql_query = mysqli_query($conn, 'SHOW CREATE TABLE ' . $table_name);
    if ($sql_query) {
        $create_table_row = mysqli_fetch_row($sql_query);
        $sql_dump .= "\n\n" . $create_table_row[1] . ";\n\n";
        $data_query = mysqli_query($conn, 'SELECT * FROM ' . $table_name);
        while ($data_row = mysqli_fetch_assoc($data_query)) {
            $sql_dump .= 'INSERT INTO ' . $table_name . ' VALUES (';
            foreach ($data_row as $key => $value) {
                $sql_dump .= "'" . mysqli_real_escape_string($conn, $value) . "', ";
            }
            $sql_dump = rtrim($sql_dump, ", ") . ");\n";
        }
        mysqli_free_result($data_query);
    } else {
        die('Error fetching create table SQL for ' . $table_name . ': ' . mysqli_error($conn));
    }
    mysqli_free_result($sql_query);
}

// Write SQL dump content to the backup file
file_put_contents($backup_file, $sql_dump);

// Close the MySQL connection
mysqli_close($conn);

// Output the SQL dump file for download
readfile($backup_file);

// Delete the temporary backup file
unlink($backup_file);

// Exit the script after generating and downloading the backup file
exit;
?>