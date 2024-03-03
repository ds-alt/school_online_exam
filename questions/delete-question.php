<?php
session_start();
include '../includes/config.php'; // Adjust the path to config.php


// Check if the user is not logged in or is not a teacher or admin
// if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'Teacher' && $_SESSION['role'] !== 'Admin')) {
//     header("Location: auth/unauthorized.php");
//     exit;
// }

// Check if question ID is provided in the URL
if (isset($_GET['id'])) {
    $questionId = $_GET['id'];

    // Delete responses associated with the question
    $deleteResponsesQuery = "DELETE FROM responses WHERE question_id = $questionId";
    if (mysqli_query($conn, $deleteResponsesQuery)) {
        // Responses deleted successfully, proceed to delete the question
        $deleteQuestionQuery = "DELETE FROM questions WHERE question_id = $questionId";
        if (mysqli_query($conn, $deleteQuestionQuery)) {
            // Question deleted successfully
            echo "Question and associated responses deleted successfully";
            // Redirect
            header("refresh:2; url=view-questions.php");
            exit;
        } else {
            // Error deleting question
            echo "Error deleting question: " . mysqli_error($conn);
        }
    } else {
        // Error deleting responses
        echo "Error deleting responses: " . mysqli_error($conn);
    }
    
    //Close database connection
    mysqli_close($conn);
} else {
    // Question ID not provided
    echo "Question ID not provided";
}
