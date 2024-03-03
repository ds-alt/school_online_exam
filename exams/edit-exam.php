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

    // Retrieve exam details from the database
    $query = "SELECT * FROM exams WHERE exam_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $examId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $examName = $row['exam_name'];
        $description = $row['exam_description'];
        $duration = $row['duration'];
        $startTime = $row['start_time'];
        $endTime = $row['end_time'];
    } else {
        echo "Exam not found.";
        exit;
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if exam ID is provided
    if (isset($_POST['exam_id']) && !empty($_POST['exam_id'])) {
        $examId = $_POST['exam_id'];

        // Retrieve and sanitize form data
        $examName = mysqli_real_escape_string($conn, $_POST['exam_name']);
        $description = mysqli_real_escape_string($conn, $_POST['exam_description']);
        $duration = mysqli_real_escape_string($conn, $_POST['duration']);
        $startTime = mysqli_real_escape_string($conn, $_POST['start_time']);
        $endTime = mysqli_real_escape_string($conn, $_POST['end_time']);

        // Update exam data in the database
        $updateQuery = "UPDATE exams 
                        SET exam_name = ?, exam_description = ?, duration = ?, start_time = ?, end_time = ? 
                        WHERE exam_id = ?";
        $stmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmt, 'sssiii', $examName, $description, $duration, $startTime, $endTime, $examId);
        if (mysqli_stmt_execute($stmt)) {
            echo "Exam updated successfully";
        } else {
            echo "Error updating exam: " . mysqli_error($conn);
        }

        // Close statement and database connection
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    } else {
        echo "Exam ID not provided.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Exam</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/scripts.js"></script>
</head>

<body>
    <div class="container">
        <h2>Edit Exam</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="hidden" name="exam_id" value="<?php echo $examId; ?>">
            <label for="exam_name">Exam Name:</label>            
            <input type="text" id="exam_name" name="exam_name" value="<?php echo $examName; ?>" required>
            <label for="exam_description">Description:</label>
            <textarea id="exam_description" name="exam_description" rows="4" cols="50"><?php echo $description; ?></textarea>
            <label for="duration">Duration (in minutes):</label>
            <input type="number" id="duration" name="duration" value="<?php echo $duration; ?>" required>
            <label for="start_time">Start Time:</label>
            <input type="datetime-local" id="start_time" name="start_time" value="<?php echo date('Y-m-d\TH:i', strtotime($startTime)); ?>" required>
            <label for="end_time">End Time:</label>
            <input type="datetime-local" id="end_time" name="end_time" value="<?php echo date('Y-m-d\TH:i', strtotime($endTime)); ?>" required>
            </br>
            <input type="submit" value="Save Changes">
        </form>
        </br>        
        <button class="admin-button" onclick="window.location.href = '../admin-panel.php';">Back to Admin Panel</button>
    </div>
</body>

</html>