<?php
session_start();
include '../includes/config.php'; // Adjust the path to config.php


// Check if the user is not logged in or is not a teacher or admin
// if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'Teacher' && $_SESSION['role'] !== 'Admin')) {
//     header("Location: unauthorized.php");
//     exit;
// }

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_exam'])) {
    $examId = $_POST['exam_id'];

    // Delete associated questions
    $deleteQuestionsQuery = "DELETE FROM questions WHERE exam_id = ?";
    $stmt = mysqli_prepare($conn, $deleteQuestionsQuery);
    mysqli_stmt_bind_param($stmt, 'i', $examId);
    mysqli_stmt_execute($stmt);

    // Then, delete the exam
    $deleteExamQuery = "DELETE FROM exams WHERE exam_id = ?";
    $stmt = mysqli_prepare($conn, $deleteExamQuery);
    mysqli_stmt_bind_param($stmt, 'i', $examId);
    mysqli_stmt_execute($stmt);

    // Check if any rows were affected to confirm deletion
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "Exam and associated questions deleted successfully.";
    } else {
        echo "No records deleted. Exam or associated questions not found.";
    }
}

// Retrieve all exams from the database
$query = "SELECT * FROM exams";
$result = mysqli_query($conn, $query);

// Check if there are exams available
if (mysqli_num_rows($result) > 0) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>View Exams</title>
        <link rel="stylesheet" href="../css/styles.css">         
    </head>
    <body>
        <div class="container">
            <h2>View Exams</h2>
            <table>
                <tr>
                    <th>Exam Name</th>
                    <th>Description</th>
                    <th>Duration</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Action</th>
                </tr>
                
                <?php
                // Display each exam as a table row
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td><?php echo $row['exam_name']; ?></td>
                        <td><?php echo $row['exam_description']; ?></td>
                        <td><?php echo $row['duration']; ?></td>
                        <td><?php echo $row['start_time']; ?></td>
                        <td><?php echo $row['end_time']; ?></td>
                        <td>
                            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this exam and its associated questions?');">
                                <a href="edit-exam.php?id=<?php echo $row['exam_id']; ?>">Edit</a>
                                <a href="delete-exam.php?id=<?php echo $row['exam_id']; ?>">Delete</a>
                            </form>
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
    echo "No exams found.";
}

// Close database connection
mysqli_close($conn);
?>
