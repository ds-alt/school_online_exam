<?php
session_start();
include '../includes/config.php'; // Include database configuration file


// Check if the user is not logged in or is not a teacher or admin
// if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'Teacher' && $_SESSION['role'] !== 'Admin')) {
//     header("Location: auth/unauthorized.php");
//     exit;
// }

// Initialize variables
$examId = $questionText = $questionType = $option1 = $option2 = $option3 = $correctAnswer = '';
$message = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Retrieve and sanitize form data
    $examId = $_POST['exam_id'];
    $questionText = mysqli_real_escape_string($conn, $_POST['question_text']);
    $questionType = $_POST['question_type'];
    $option1 = mysqli_real_escape_string($conn, $_POST['option_1']);
    $option2 = mysqli_real_escape_string($conn, $_POST['option_2']);
    $option3 = mysqli_real_escape_string($conn, $_POST['option_3']);
    $correctAnswer = $_POST['correct_answer'];

    // Insert question data into the database
    $insertQuery = "INSERT INTO questions (exam_id, question_text, question_type, option_1, option_2, option_3, correct_answer) 
                    VALUES ('$examId', '$questionText', '$questionType', '$option1', '$option2', '$option3', '$correctAnswer')";

    if (mysqli_query($conn, $insertQuery)) {
        $message = "Question created successfully";
        // Clear the form fields after successful submission
        $examId = $questionText = $questionType = $option1 = $option2 = $option3 = $correctAnswer = '';
    } else {
        $message = "Error creating question: " . mysqli_error($conn);
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Question</title>
    <link rel="stylesheet" href="../css/styles.css"> 
    <script src="js/scripts.js"></script>
</head>
<body>
    <div class="container">
        <h2>Create New Question</h2>
        <!-- Display message here -->
        <?php echo $message; ?>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="exam_id">Select Exam:</label><br>
            
            <select id="exam_id" name="exam_id" required>
            <option value="" disabled selected>Select Option</option>
                <?php
                // Fetch exam names from database
                $examQuery = "SELECT exam_id, exam_name FROM exams";
                $examResult = mysqli_query($conn, $examQuery);

                // Check if there are exams available
                if (mysqli_num_rows($examResult) > 0) {
                    while ($row = mysqli_fetch_assoc($examResult)) {
                        echo "<option value='{$row['exam_id']}'>{$row['exam_name']}</option>";
                    }
                } else {
                    echo "<option value=''>No exams available</option>";
                }
                ?>
            </select><br><br>

            <label for="question_text">Question:</label><br>
            <textarea id="question_text" name="question_text" rows="4" cols="50" required><?php echo $questionText; ?></textarea><br><br>
            
            <label for="question_type">Question Type:</label><br>
            <select id="question_type" name="question_type" required>
            <option value="Multiple Choice" <?php if ($questionType == 'Multiple Choice') echo 'selected'; ?>>Multiple Choice</option>
                <option value="Text" <?php if ($questionType == 'Text') echo 'selected'; ?>>Text</option>
                <option value="Numbers" <?php if ($questionType == 'Numbers') echo 'selected'; ?>>Numbers</option>
            </select><br><br>
            
            <label for="option_1">Option 1:</label><br>
            <input type="text" id="option_1" name="option_1" value="<?php echo $option1; ?>" required><br>
            <label for="option_2">Option 2:</label><br>
            <input type="text" id="option_2" name="option_2" value="<?php echo $option2; ?>" required><br><br>
            <label for="option_3">Option 3:</label><br>
            <input type="text" id="option_3" name="option_3" value="<?php echo $option3; ?>" required><br><br>
            
            <label for="correct_answer">Correct Answer:</label>
            <select id="correct_answer" name="correct_answer" required>
                <option value="option_1" <?php if ($correctAnswer == 'option_1') echo 'selected'; ?>>Option 1</option>
                <option value="option_2" <?php if ($correctAnswer == 'option_2') echo 'selected'; ?>>Option 2</option>
                <option value="option_3" <?php if ($correctAnswer == 'option_3') echo 'selected'; ?>>Option 3</option>
            </select><br><br>
            
            <input type="submit" name="submit" value="Create Question">
        </form>
        <button class="admin-button" onclick="window.location.href = '../admin-panel.php';">Back to Admin Panel</button>        
    </div>
    
</body>
</html>
