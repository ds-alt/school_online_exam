<?php
session_start();
include '../includes/config.php';


// Check if the form is submitted for login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and bind parameters for the query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);

    // Execute the query
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows == 1) {
        // User found, fetch user data
        $user = $result->fetch_assoc();
        $hashedPassword = $user['password'];

        // Verify password using password_verify()
        if (password_verify($password, $hashedPassword)) {
            // Set user data in session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on user's role
            if ($user['role'] == 'Admin' || $user['role'] == 'Teacher') {
                header("Location: ../admin-panel.php");
                exit;
            } elseif ($user['role'] == 'Student') {
                header("Location: ../student/exam-page.php");
                exit;
            }
        } else {
            $loginError = "<b>Incorrect password</b>";
        }
    } else {
        $loginError = "<b>Username not found</b>";
    }

    // Close the statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/styles.css"> <!-- Adjust the path to CSS file -->
</head>

<body>
    <header>
    <img class="exam-logo" src="../img/dslogo.png" alt="Logo">
    </header>
    <div class="container">
        <h2>Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <input type="submit" name="login" value="Login">
        </form>
        <?php if (isset($loginError)) echo "<p class='error'>$loginError</p>"; ?>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
    <footer class="exam-footer">
        <p>© 2024 Dushko Stankovski's Workspace. All rights reserved.</p>
    </footer>
</body>

</html>