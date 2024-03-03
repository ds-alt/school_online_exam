<?php
session_start();
include '../includes/config.php'; // Adjust the path to config.php


// Check if the user is not logged in or is not a teacher or admin
// if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'Teacher' && $_SESSION['role'] !== 'Admin')) {
//     header("Location: unauthorized.php");
//     exit;
// }

// Check if exam ID is provided in the URL
if (isset($_GET['id'])) {
    $examId = $_GET['id'];

    // Delete related questions first
    $deleteQuestionsQuery = "DELETE FROM questions WHERE exam_id = $examId";

    if (mysqli_query($conn, $deleteQuestionsQuery)) {
        // Questions deleted successfully or no questions found
        // Now delete the exam
        $deleteQuery = "DELETE FROM exams WHERE exam_id = $examId";

        if (mysqli_query($conn, $deleteQuery)) {
            // Exam deleted successfully
            echo "Exam deleted successfully";
            //redirect
            header("refresh:2; url=view-exams.php");
            exit; // Stop further execution
        } else {
            // Error deleting exam
            echo "Error deleting exam: " . mysqli_error($conn);
        }
    } else {
        // Error deleting questions
        echo "Error deleting questions: " . mysqli_error($conn);
    }

    // Close database connection
    mysqli_close($conn);
} else {
    // Exam ID not provided
    echo "Exam ID not provided";
}
?>
