<?php
session_start();
// Include database configuration file
include '../includes/config.php';


// Perform the deletion operation for the student results table
$deleteQuery = "DELETE FROM student_results";
$deleteResult = mysqli_query($conn, $deleteQuery);

if ($deleteResult) {
    // Deletion successful
    echo json_encode(array("status" => "success"));
} else {
    // Deletion failed
    echo json_encode(array("status" => "error", "message" => mysqli_error($conn)));
}
?>
