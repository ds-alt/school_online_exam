<?php
session_start();

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to get user ID
function getUserId() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

// Function to get user role
function getUserRole() {
    return isset($_SESSION['role']) ? $_SESSION['role'] : null;
}

// Example usage:
// Check if user is logged in
if (isLoggedIn()) {
    // User is logged in, perform actions accordingly
} else {
    // User is not logged in, redirect to login page or perform other actions
}
?>
