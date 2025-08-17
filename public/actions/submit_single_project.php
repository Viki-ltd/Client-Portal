<?php
session_start();

// Load the necessary files
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Core/Database.php';

// Check if the form was submitted using the POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- 1. Get Data From Form ---
    // Sanitize user input to prevent XSS attacks
    $title = htmlspecialchars($_POST['title']);
    $category = htmlspecialchars($_POST['category']);
    $priority = htmlspecialchars($_POST['priority']);
    $description = htmlspecialchars($_POST['description']);
    
    // For now, we'll use a placeholder user ID.
    // Later, this will come from the user's session after they log in.
    $user_id = 1; 

    try {
        // --- 2. Connect to the Database ---
        $db = \App\Core\Database::getInstance()->getConnection();

        // --- 3. Prepare the SQL INSERT Statement ---
        // Using a prepared statement with placeholders (?) is crucial for security to prevent SQL injection.
        $stmt = $db->prepare(
            "INSERT INTO submissions (user_id, title, category, priority, description) VALUES (?, ?, ?, ?, ?)"
        );

        // --- 4. Execute the Statement with the Form Data ---
        $stmt->execute([
            $user_id,
            $title,
            $category,
            $priority,
            $description
        ]);

        // --- 5. Redirect on Success ---
        // Redirect the user back to the dashboard with a success message in the URL
        header("Location: " . APP_URL . "/public/dashboard?status=success");
        exit();

    } catch (PDOException $e) {
        // In a real application, you would log this error and show a user-friendly message.
        die("Database Error: " . $e->getMessage());
    }
} else {
    // If someone tries to access this file directly, redirect them to the dashboard.
    header("Location: " . APP_URL . "/public/dashboard");
    exit();
}