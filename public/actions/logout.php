<?php
session_start(); // Start the session to access it
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Load config to get the APP_URL for a stable redirect
require_once __DIR__ . '/../../config/config.php';

// Redirect to the login page
header("Location: " . APP_URL . "/public/login");
exit();