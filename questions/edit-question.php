<?php
session_start();
include '../includes/config.php'; // Adjust the path to config.php


// Check if the user is not logged in or is not a teacher or admin
// if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'Teacher' && $_SESSION['role'] !== 'Admin')) {
//     header("Location: unauthorized.php");
//     exit;
// }

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $questionId = $_POST['question_id'];
    $questionText = mysqli_real_escape_string($conn, $_POST['question_text']);
    $option1 = mysqli_real_escape_string($conn, $_POST['option_1']);
    $option2 = mysqli_real_escape_string($conn, $_POST['option_2']);
    $option3 = mysqli_real_escape_string($conn, $_POST['option_3']);
    $correctAnswer = mysqli_real_escape_string($conn, $_POST['correct_answer']);

    // Update question data in the database
    $updateQuery = "UPDATE questions 
                    SET question_text = '$questionText', 
                        option_1 = '$option1', 
                        option_2 = '$option2',
                        option_3 = '$option3', 
                        correct_answer = '$correctAnswer' 
                    WHERE question_id = $questionId";

    if (mysqli_query($conn, $updateQuery)) {
        echo "Question edited successfully";        
        //redirect
        header("refresh:2; url=view-questions.php");
        exit; // Stop further execution                
    } else {
        echo "Error updating question: " . mysqli_error($conn);
    }

    // Close database connection
    mysqli_close($conn);

    // Exit to prevent rendering the HTML below
    exit;
}

// Continue rendering the HTML below if the form is not submitted or after processing
// Check if question ID is provided in the URL
if (isset($_GET['id'])) {
    $questionId = $_GET['id'];

    // Retrieve question details from the database
    $query = "SELECT * FROM questions WHERE question_id = $questionId";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $questionText = $row['question_text'];
        $option1 = $row['option_1'];
        $option2 = $row['option_2'];
        $option2 = $row['option_3'];
        $correctAnswer = $row['correct_answer'];
        // Add more variables for additional question details if needed
    } else {
        echo "Question not found.";
        exit;
    }
} else {
    echo "Question ID not provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question</title>
    <link rel="stylesheet" href="../css/styles.css"> 
    
</head>
<body>
    <div class="container">
            <form action="edit-question.php?id=<?php echo $questionId; ?>" method="POST">
            <!-- Hidden input field to store question ID -->
            <input type="hidden" name="question_id" value="<?php echo $questionId; ?>">

            <label for="exam_id">Select Exam:</label><br>
            <select id="exam_id" name="exam_id" required>
                <?php
                // Fetch exam names from database
                $examQuery = "SELECT exam_id, exam_name FROM exams";
                $examResult = mysqli_query($conn, $examQuery);

                // Check if there are exams available
                if (mysqli_num_rows($examResult) > 0) {
                    while ($row = mysqli_fetch_assoc($examResult)) {
                        $selected = ($row['exam_id'] == $examId) ? 'selected' : '';
                        echo "<option value='{$row['exam_id']}' $selected>{$row['exam_name']}</option>";
                    }
                } else {
                    echo "<option value='' disabled>No exams available</option>";
                }
                ?>
            </select><br><br>

            <label for="question_text">Question:</label><br>
            <textarea id="question_text" name="question_text" rows="4" cols="50" required><?php echo $questionText; ?></textarea><br><br>
            
            <label for="options">Options:</label><br>
            <input type="text" id="option_1" name="option_1" value="<?php echo $option1; ?>" required><br>
            <input type="text" id="option_2" name="option_2" value="<?php echo $option2; ?>" required><br>
            <input type="text" id="option_3" name="option_3" value="<?php echo $option2; ?>" required><br>
            <!-- Add more input fields for additional options if needed -->
            
            <label for="correct_answer">Correct Answer:</label>
            <select id="correct_answer" name="correct_answer" required>
                <option value="option_1" <?php if ($correctAnswer === "option_1") echo "selected"; ?>>Option 1</option>
                <option value="option_2" <?php if ($correctAnswer === "option_2") echo "selected"; ?>>Option 2</option>
                <option value="option_3" <?php if ($correctAnswer === "option_3") echo "selected"; ?>>Option 3</option>
                <!-- Add more options for additional options if needed -->
            </select><br><br>
            
            <input type="submit" value="Save Changes">
        </form>
        </br>
        <button class="admin-button" onclick="window.location.href = '../admin-panel.php';">Back to Admin Panel</button>
    </div>
</body>
</html>
