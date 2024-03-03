<?php
include '../includes/config.php';

// Define roles array
$roles = ['Admin', 'Teacher', 'Student'];

// Check if the form is submitted for registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    // Retrieve form data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = $_POST['role']; // Get selected role

    // Check if email contains "@"
    if (!strpos($email, '@')) {
        $registerError = "<b>Email must be valid.</b>";
    }
    // Check if password is at least 6 characters long
    elseif (strlen($password) < 6) {
        $registerError = "<b>Password must be at least 6 characters long.</b>";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into the database
        $insertQuery = "INSERT INTO Users (username, password, email, role) 
                        VALUES ('$username', '$hashedPassword', '$email', '$role')";

        if (mysqli_query($conn, $insertQuery)) {
            $message = "<b>Registration successful. You can now login.</b>";
            // Redirect to login page after 2 seconds
            header("refresh:2; url=login.php");
            exit;
        } else {
            $registerError = "<b>Error registering user:</b> " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/styles.css"> <!-- Adjust the path to CSS file -->
</head>

<body>
    <header>
    <img class="exam-logo" src="../img/dslogo.png" alt="Logo">
    </header>
    <div class="container">
        <h2>Register</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <?php foreach ($roles as $role) : ?>
                    <option value="<?php echo $role; ?>"><?php echo $role; ?></option>
                <?php endforeach; ?>
            </select><br><br>
            <input type="submit" name="register" value="Register">
        </form>
        <?php
        if (isset($registerError)) {
            echo "<b><p class='error'>$registerError</p></b>";
        } elseif (isset($message)) {
            echo "<b><p class='success'>$message</p></b>";
        }
        ?>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
    <footer class="exam-footer">

        <p>© 2024 Dushko Stankovski's Workspace. All rights reserved.</p>

    </footer>
</body>

</html>