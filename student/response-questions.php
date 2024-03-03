<?php
// Include database configuration file
include '../includes/config.php';

// Check if the form is submitted for submitting answers
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_answers'])) {
    // Capture submitted answers and insert into Responses table
    $user_id = $_SESSION['user_id'] ?? null; // Make sure user_id is set
    $exam_id = $_POST['exam_id'] ?? null;  
    

    if ($user_id && $exam_id) {
        foreach ($_POST as $key => $value) {
            // Check if the key corresponds to a question response
            if (strpos($key, 'question_') !== false) {
                // Extract the question ID from the key
                $question_id = substr($key, strlen('question_'));
                
                // Insert the response into Responses table
                $chosen_answer = mysqli_real_escape_string($conn, $value); // Sanitize user input
                $response_time = date('Y-m-d H:i:s');
                $query = "INSERT INTO Responses (user_id, exam_id, question_id, chosen_answer, response_time) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $query);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, 'iiiss', $user_id, $exam_id, $question_id, $chosen_answer, $response_time);
                    mysqli_stmt_execute($stmt);
                    // Check if the query executed successfully
                    if (mysqli_stmt_affected_rows($stmt) > 0) {
                        $successMessage = "Answers submitted successfully.";
                    } else {
                        // Handle query execution failure
                        $errorMessage = "Error submitting answers: " . mysqli_error($conn);
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    // Handle statement preparation failure
                    $errorMessage = "Error preparing statement: " . mysqli_error($conn);
                }
            }
        }
    } else {
        // Handle missing user_id or exam_id
        $errorMessage = "User ID or Exam ID missing.";
    }
    
}
