<?php
session_start();
require_once 'db_connect.php'; // includes database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        header("Location: index.php?error=All fields are required");
        exit();
    }

    // validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.php?error=Invalid email format");
        exit();
    }

    // check if username or email already exists
    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        
        if ($stmt->rowCount() > 0) {
            header("Location: index.php?error=Username or email already exists");
            exit();
        }

        // hash the password (encryption stuff)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // inserts new user
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashedPassword]);

        // success - redirects back to login page
        header("Location: index.php?success=Registration successful! You can now log in");
        exit();

    } catch (PDOException $e) {
        header("Location: index.php?error=Database error: " . $e->getMessage());
        exit();
    }
} else {
    // if not a POST request, redirect back
    header("Location: index.php");
    exit();
}
?>