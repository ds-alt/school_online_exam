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
    // Retrieve and sanitize form data
    $examName = mysqli_real_escape_string($conn, $_POST['exam_name']);
    $description = mysqli_real_escape_string($conn, $_POST['exam_description']);
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);
    $startTime = mysqli_real_escape_string($conn, $_POST['start_time']);
    $endTime = mysqli_real_escape_string($conn, $_POST['end_time']);

    // Insert exam data into the database
    $insertQuery = "INSERT INTO exams (exam_name, exam_description, duration, start_time, end_time) 
                    VALUES ('$examName', '$description', '$duration', '$startTime', '$endTime')";

    if (mysqli_query($conn, $insertQuery)) {
        echo "Exam created successfully";
    } else {
        echo "Error creating exam: " . mysqli_error($conn);
    }

    // Close database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Exam</title>
    <link rel="stylesheet" href="../css/styles.css"> <!-- Adjust the path to CSS file -->
    <script src="../js/scripts.js"></script>
</head>
<body>
    <div class="container">
        <h2>Create New Exam</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="exam_name">Exam Name:</label>
            <input type="text" id="exam_name" name="exam_name" required><br><br>

            <label for="exam_description">Description:</label><br>
            <textarea id="exam_description" name="exam_description" rows="4" cols="50"></textarea><br><br>

            <label for="start_time">Start Time:</label>
            <input type="datetime-local" id="start_time" name="start_time" required><br><br>

            <label for="duration">Duration (minutes):</label>
            <input type="number" id="duration" name="duration" min="1" required><br><br>

            <label for="end_time">End Time:</label>
            <input type="datetime-local" id="end_time" name="end_time" required><br><br>

            <input type="submit" value="Create Exam">
        </form>
        <button class="admin-button" onclick="window.location.href = '../admin-panel.php';">Back to Admin Panel</button>
    </div>
</body>
</html>
