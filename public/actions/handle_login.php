<?php
session_start();

// Load essential files
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Core/Database.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Get email and password from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // 2. Connect to the database
        $db = \App\Core\Database::getInstance()->getConnection();

        // 3. Find the user by their email address
        $stmt = $db->prepare("SELECT id, email, password_hash, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // 4. Verify the password
        // Use password_verify() to securely check the submitted password against the hash stored in the database.
        if ($user && password_verify($password, $user['password_hash'])) {
            // Password is correct!

            // 5. Start a session and store user data
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];

            // 6. Redirect to the dashboard
            header("Location: " . APP_URL . "/public/dashboard");
            exit();
        } else {
            // Password is incorrect or user not found
            // Redirect back to the login page with an error message
            header("Location: " . APP_URL . "/public/login?error=1");
            exit();
        }

    } catch (PDOException $e) {
        // Handle database errors
        die("Database Error: " . $e->getMessage());
    }
} else {
    // Redirect if accessed directly
    header("Location: " . APP_URL . "/public/login");
    exit();
}