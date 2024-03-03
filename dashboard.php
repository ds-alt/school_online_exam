<?php
// Include database configuration file
include 'includes/config.php';

// Fetch data for current exams
$currentExamsQuery = "SELECT * FROM exams";
$currentExamsResult = mysqli_query($conn, $currentExamsQuery);

// Fetch data for recent questions (assuming there's a questions table)
$recentQuestionsQuery = "SELECT * FROM questions ORDER BY question_id DESC LIMIT 5";
$recentQuestionsResult = mysqli_query($conn, $recentQuestionsQuery);

// Fetch notifications or alerts (you can customize this based on your system)
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
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../css/styles.css">
    
</head>

<body>
    <div class="container">
        <header>
            <h1>Admin Panel</h1>
        </header>
        <nav>
            <!-- Navigation links -->
        </nav>
        <section id="dashboard">
            <h2>Dashboard</h2>
            <div class="dashboard">
                <div class="dashboard-item">
                    <h3>Current Exams</h3>
                    <ul>
                        <?php
                        if (mysqli_num_rows($currentExamsResult) > 0) {
                            while ($row = mysqli_fetch_assoc($currentExamsResult)) {
                                echo "<li>{$row['exam_name']}</li>";
                            }
                        } else {
                            echo "<li>No exams found.</li>";
                        }
                        ?>
                    </ul>
                </div>
                <div class="dashboard-item">
                    <h3>Recent Questions</h3>
                    <ul>
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
                <div class="dashboard-item">
                    <h3>Notifications</h3>
                    <ul>
                        <?php
                        foreach ($notifications as $notification) {
                            echo "<li>$notification</li>";
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
                        $query = "SELECT * FROM student_results";
                        $result = mysqli_query($conn, $query);

                        $queryUsers = "SELECT user_id, username FROM users WHERE role = 'Student'";
                        $resultUsers = mysqli_query($conn, $queryUsers);
                        $rowUser = mysqli_fetch_assoc($resultUsers);

                        // Check if there are results available
                        if (mysqli_num_rows($result) > 0) {
                            // Display each result as a list item
                            while ($row = mysqli_fetch_assoc($result)) {                                
                                echo "<li>{$rowUser['username']}, Correct Count: {$row['correct_count']}, Incorrect Count: {$row['incorrect_count']}, Total Points: {$row['total_points']}</li>";
                            }
                        } else {
                            echo "<li>No results found.</li>";
                        }
                    ?>                            
                           
                            
                        </tbody>
                    </table>
                </div>

            </div>
    </div>
    </section>
    <!-- Other sections -->
    </div>
</body>

</html>
