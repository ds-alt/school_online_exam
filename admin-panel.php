<?php
session_start();
// Include database configuration file
include 'includes/config.php';

// Check if the user is not logged in or is not a teacher or admin
// if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'Teacher' && $_SESSION['role'] !== 'Admin')) {
//     header("Location: auth/unauthorized.php");
//     exit;
// }

// Fetch data for current exams
$currentExamsQuery = "SELECT * FROM exams";
$currentExamsResult = mysqli_query($conn, $currentExamsQuery);
// Fetch data for recent questions
$recentQuestionsQuery = "SELECT * FROM questions ORDER BY question_id";
$recentQuestionsResult = mysqli_query($conn, $recentQuestionsQuery);

// Example notifications (replace this with your actual notification data)
$notifications = array(
    "New exam scheduled for next week.",
    "Reminder: Complete pending exam review.",
    "System maintenance scheduled for tomorrow."
);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Admin Panel</title>
</head>

<body>
    <div class="navbar admin-navbar">  
        <img class="exam-logo" src="img/dslogo_w.png" alt="Logo">        
        <!-- <a href="#dashboard.php" class="admin-nav-link"></a> -->
        <a href="admin-panel.php" class="admin-nav-link">Dashboard</a>
        <div class="dropdown admin-dropdown">
            <a href="javascript:void(0)" class="dropbtn admin-dropbtn">Exams</a>
            <div class="admin-dropdown-content">
                <a href="exams/create-exam.php" class="admin-dropdown-link">Create Exam</a>
                <a href="exams/view-exams.php" class="admin-dropdown-link">View Exams</a>
            </div>
        </div>
        <div class="dropdown admin-dropdown">
            <a href="javascript:void(0)" class="dropbtn admin-dropbtn">Questions</a>
            <div class="admin-dropdown-content">
                <a href="questions/create-question.php" class="admin-dropdown-link">Create Question</a>
                <a href="questions/view-questions.php" class="admin-dropdown-link">View Question</a>
            </div>
        </div>
        <div class="dropdown admin-dropdown">
            <a href="javascript:void(0)" class="dropbtn admin-dropbtn">User Management</a>
            <div class="admin-dropdown-content">
                <a href="users/manage-users.php" class="admin-dropdown-link"">Manage Users</a>                
            </div>
        </div>
        <a href=" help/help-support.php" class="admin-dropdown-link">Help/Support</a>
                <a href="auth/logout.php" class="admin-dropdown-link">Logout</a>
            </div>

            <section id="dashboard">
                <h2 class="admin-section-title"></h2>
                <div class="dashboard admin-dashboard">
                    <div class="dashboard-item admin-dashboard-item">
                        <h2 class="admin-subtitle">Current Exams</h2>
                        <ul class="exam-list admin-exam-list">
                            <?php
                            if (mysqli_num_rows($currentExamsResult) > 0) {
                                while ($row = mysqli_fetch_assoc($currentExamsResult)) {
                                    echo "<li>{$row['exam_name']} - {$row['exam_description']}</li>";
                                }
                            } else {
                                echo "<li>No exams found.</li>";
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="dashboard-item admin-dashboard-item">
                        <h2 class="admin-subtitle">Recent Questions</h2>
                        <!-- Display list of recent questions -->
                        <ul class="exam-list admin-exam-list">
                            <?php
                            if (mysqli_num_rows($recentQuestionsResult) > 0) {
                                while ($row = mysqli_fetch_assoc($recentQuestionsResult)) {
                                    echo "<li>{$row['question_text']}</li>";
                                }
                            } else {
                                echo "<li>No recent questions found.</li>";
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="dashboard-item admin-dashboard-item">
                        <h2 class="admin-subtitle">Results</h2>
                        <table class="exam-list admin-exam-list">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Correct Count</th>
                                    <th>Incorrect Count</th>
                                    <th>Total Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Retrieve results from the student_results table
                                $queryResults = "SELECT * FROM student_results";
                                $resultResults = mysqli_query($conn, $queryResults);

                                // Check if there are results available
                                if (mysqli_num_rows($resultResults) > 0) {
                                    // Fetch and display each result
                                    while ($rowResult = mysqli_fetch_assoc($resultResults)) {
                                        // Fetch username for the corresponding user_id
                                        $userId = $rowResult['user_id'];
                                        $queryUsername = "SELECT username FROM users WHERE user_id = $userId";
                                        $resultUsername = mysqli_query($conn, $queryUsername);
                                        $rowUsername = mysqli_fetch_assoc($resultUsername);
                                        $username = $rowUsername['username'];

                                        // Display result
                                        echo "<tr>";
                                        echo "<td>{$username}</td>";
                                        echo "<td>{$rowResult['correct_count']}</td>";
                                        echo "<td>{$rowResult['incorrect_count']}</td>";
                                        echo "<td>{$rowResult['total_points']}</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>No results found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </section>

        </div>
        <footer class="exam-footer">

            <p>© 2024 Dushko Stankovski's Workspace. All rights reserved.</p>

        </footer>
</body>

</html>