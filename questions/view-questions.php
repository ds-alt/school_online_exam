<?php
session_start();
include '../includes/config.php'; // Adjust the path to config.php


// Check if the user is not logged in or is not a teacher or admin
// if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'Teacher' && $_SESSION['role'] !== 'Admin')) {
//     header("Location: unauthorized.php");
//     exit;
// }

// Initialize variables
$selectedExam = isset($_GET['exam']) ? $_GET['exam'] : '';

// Retrieve all distinct exams from the database
$examQuery = "SELECT DISTINCT exam_id, exam_name FROM exams";
$examResult = mysqli_query($conn, $examQuery);

// Retrieve questions based on the selected exam
if (!empty($selectedExam)) {
    $query = "SELECT q.*, e.exam_name 
              FROM questions q
              INNER JOIN exams e ON q.exam_id = e.exam_id
              WHERE q.exam_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $selectedExam);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    // Retrieve all questions with their associated exams from the database
    $query = "SELECT q.*, e.exam_name 
              FROM questions q
              INNER JOIN exams e ON q.exam_id = e.exam_id";
    $result = mysqli_query($conn, $query);
}

// Check if there are questions available
if (mysqli_num_rows($result) > 0) {
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>View Questions</title>
        <link rel="stylesheet" href="../css/styles.css"> 
        
    </head>
    <body>
        <div class="container">
            <h2>View Questions</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
                <label for="exam">Select Exam:</label>
                <select id="exam" name="exam" onchange="this.form.submit()">
                    <option value="">Select Exam</option>
                    <?php
                    // Display each exam as a dropdown option
                    while ($row = mysqli_fetch_assoc($examResult)) {
                        $selected = ($row['exam_id'] == $selectedExam) ? 'selected' : '';
                        echo "<option value='{$row['exam_id']}' $selected>{$row['exam_name']}</option>";
                    }
                    ?>
                </select>
            </form>
            <table>
                <tr>
                    <th>Exam</th>
                    <th>Question Text</th>
                    <th>Options</th>
                    <th>Correct Answer</th>
                    <th>Action</th>
                </tr>
                <?php
                // Display each question as a table row
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td><?php echo $row['exam_name']; ?></td>
                        <td><?php echo $row['question_text']; ?></td>
                        <td><?php echo $row['option_1'] . ', ' . $row['option_2'] . ', ' . $row['option_3']; ?></td>
                        <td><?php echo $row['correct_answer']; ?></td>
                        <td>
                            <a href="edit-question.php?id=<?php echo $row['question_id']; ?>">Edit | </a>
                            <a href="delete-question.php?id=<?php echo $row['question_id']; ?>">Delete</a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            </br>
            <button class="admin-button" onclick="window.location.href = '../admin-panel.php';">Back to Admin Panel</button>
        </div>
    </body>
    </html>
    <?php
} else {
    echo "No questions found.";
}

// Close database connection
mysqli_close($conn);
?>
