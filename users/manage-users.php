<?php
session_start();
include '../includes/config.php'; // Adjust the path to config.php

// Check if the user is not logged in or is not a teacher or admin
// if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'Teacher' && $_SESSION['role'] !== 'Admin')) {
//     header("Location: auth/unauthorized.php");
//     exit;
// }

// Check if delete action is triggered
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Prepare and execute delete query
    $deleteQuery = "DELETE FROM users WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $deleteQuery);
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);

    // Redirect back to manage-users.php after deletion
    header("Location: manage-users.php");
    exit;
}

// Fetch user data from the database
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);

// Check if there are users available
if (mysqli_num_rows($result) > 0) {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Manage Users</title>
        <link rel="stylesheet" href="../css/styles.css">
    </head>

    <body>
        <div class="container">
            <h2>Manage Users</h2>
            <table>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
                <?php
                // Display each user as a table row
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                        <td><?php echo $row['user_id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><a href="manage-users.php?delete=true&id=<?php echo $row['user_id']; ?>">Delete</a></td>
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
    echo "No users found.";
}

// Close database connection
mysqli_close($conn);
?>