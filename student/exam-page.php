<?php
session_start();
include '../includes/config.php'; // Include database configuration file


// Hardcode the role to 'Student' for testing
//$_SESSION['role'] = 'Student';

// Check if the user is not logged in, or if they are not a student, teacher, or admin
// if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'Student')){
//     header("Location: ../auth/unauthorized.php");
//     exit;
// }

// Fetch the user_id of the current user from the session
$userId = $_SESSION['user_id'];

// Fetch the username of the current user from the Users table
$queryUsername = "SELECT username FROM Users WHERE user_id = ?";
$stmtUsername = mysqli_prepare($conn, $queryUsername);
mysqli_stmt_bind_param($stmtUsername, 'i', $userId);
mysqli_stmt_execute($stmtUsername);
$resultUsername = mysqli_stmt_get_result($stmtUsername);

// Check if the username is fetched successfully
if ($rowUsername = mysqli_fetch_assoc($resultUsername)) {
    $username = $rowUsername['username'];

    // Fetch the list of available exams from the database
    $queryExams = "SELECT * FROM Exams";
    $resultExams = mysqli_query($conn, $queryExams);

    if (!$resultExams) {
        echo "Error: " . mysqli_error($conn);
    } else {
        $exams = mysqli_fetch_all($resultExams, MYSQLI_ASSOC);
    }

    // Check if the form is submitted for starting the exam
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['start_exam'])) {
        // Process starting the exam
        // Your code for starting the exam goes here
    }

    // Check if the form is submitted for submitting answers
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_answers'])) {
        // Process submitting answers
        // Your code for submitting answers goes here
    }
}


// Fetch the list of available exams from the database
$query = "SELECT * FROM Exams";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
} else {
    $exams = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Check if the form is submitted for starting the exam
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['start_exam'])) {
    $selected_exam_id = $_POST['exam_id'];

    // Fetch questions for the selected exam from the database
    $query = "SELECT * FROM Questions WHERE exam_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $selected_exam_id);
    mysqli_stmt_execute($stmt);
    $questionsResult = mysqli_stmt_get_result($stmt);

    if (!$questionsResult) {
        // Handle query execution error
        echo "Error: " . mysqli_stmt_error($stmt);
    } else {
        // Process fetched questions
        $questions = mysqli_fetch_all($questionsResult, MYSQLI_ASSOC);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_answers'])) {
    // Initialize counts for correct and incorrect answers
    $correctCount = 0;
    $incorrectCount = 0;

    foreach ($_POST as $key => $value) {
        if (strpos($key, 'question_') === 0) {
            $questionId = substr($key, strlen('question_'));

            // Retrieve the correct answer from the database
            $query = "SELECT TRIM(correct_answer) AS correct_answer FROM Questions WHERE question_id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'i', $questionId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            $correctAnswer = $row['correct_answer'];

            // Get the chosen answer submitted by the user
            $chosenAnswer = $_POST[$key];

            // Normalize both correct and chosen answers to lowercase
            $correctAnswerNormalized = strtolower(trim($correctAnswer));
            $chosenAnswerNormalized = strtolower(trim($chosenAnswer));

            // Output for debugging
            //echo "Correct Answer: " . $correctAnswerNormalized . " (length: " . strlen($correctAnswerNormalized) . "), Chosen Answer: " . $chosenAnswerNormalized . " (length: " . strlen($chosenAnswerNormalized) . ")<br>";

            // Define mapping between correct answer and choices presented to the user
            $answerMapping = array(
                'option_1' => 'a',
                'option_2' => 'b',
                'option_3' => 'c',
                // Add more mappings if needed
            );

            // Map correct answer to user choice
            $correctChoice = $answerMapping[$correctAnswerNormalized];

            // Compare normalized chosen answer with correct choice
            if ($chosenAnswerNormalized === $correctChoice) {
                // Answer is correct
                $correctCount++;
            } else {
                // Answer is incorrect
                $incorrectCount++;
            }
        }
    }

    // Calculate total points
    $totalPoints = $correctCount * 20; // Assuming each correct answer is worth 20 points

    // Set the current date and time as the exam time
    $exam_time = date('Y-m-d H:i:s');

    // Insert or update the results in the student_results table
    $insertQuery = "INSERT INTO student_results (user_id, username, correct_count, incorrect_count, total_points, exam_time) 
                    VALUES (?, ?, ?, ?, ?, ?) 
                    ON DUPLICATE KEY UPDATE 
                    correct_count = VALUES(correct_count), 
                    incorrect_count = VALUES(incorrect_count), 
                    total_points = VALUES(total_points)";
    $stmt = mysqli_prepare($conn, $insertQuery);
    mysqli_stmt_bind_param($stmt, 'isiiis', $userId, $username, $correctCount, $incorrectCount, $totalPoints, $exam_time);
    mysqli_stmt_execute($stmt);



    // Output the value of $exam_time to check if it's correct
    echo "Exam completed in: " . $exam_time . "<br>";

    // Provide a success message
    $successMessage = "Answers submitted successfully. Results calculated and stored.";

    // Store the success message in a session variable
    $_SESSION['successMessage'] = $successMessage;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Page</title>
    <link rel="stylesheet" href="../css/styles.css">
    
</head>

<body>
    <img class="exam-logo" src="../img/dslogo.png" alt="Logo">
    <div class="container">
        <div class="form-container">
            <h2>Welcome, <?php echo $_SESSION["username"]; ?>!</h2>
            <h3>Choose an Exam</h3>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="exam_id">Select Exam:</label>
                <select name="exam_id" id="exam_id" required>
                    <option value="" disabled selected>Select Option</option>
                    <?php foreach ($exams as $exam) : ?>
                        <option value="<?php echo $exam['exam_id']; ?>"><?php echo $exam['exam_name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="start_exam">Start Exam</button>
            </form>

            <?php if (isset($questions)) : ?>
                <h3>Exam Questions</h3>
                <form method="post" action="">
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                    <input type="hidden" name="exam_id" value="<?php echo $selected_exam_id; ?>">

                    <?php foreach ($questions as $question) : ?>
                        <p><?php echo $question['question_text']; ?></p>

                        <?php // Assuming you have only two options for each question, 'A' and 'B' 
                        ?>
                        <label>
                            <input type="radio" name="question_<?php echo $question['question_id']; ?>" value="A">
                            <?php echo 'A: ' . $question['option_1']; ?>
                        </label><br>

                        <label>
                            <input type="radio" name="question_<?php echo $question['question_id']; ?>" value="B">
                            <?php echo 'B: ' . $question['option_2']; ?>
                        </label><br>

                        <label>
                            <input type="radio" name="question_<?php echo $question['question_id']; ?>" value="C">
                            <?php echo 'C: ' . $question['option_3']; ?>
                        </label><br>

                        <?php // Add more labels for additional options if needed 
                        ?>

                    <?php endforeach; ?>

                    <button type="submit" name="submit_answers">Submit Answers</button>
                </form>
            <?php endif; ?>
            <?php include '../student/response-questions.php'; ?>
            <a href="../auth/logout.php">Logout</a>

        </div>
    </div>
    <footer class="exam-footer">
        <p>© 2024 Dushko Stankovski's Workspace. All rights reserved.</p>
    </footer>
</body>

</html>