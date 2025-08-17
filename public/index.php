<?php
session_start();

// Load essential files
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Core/UIHelper.php';

// --- NEW PAGE PROTECTION LOGIC ---
$is_logged_in = isset($_SESSION['user_id']);
$path = strtok(str_replace('/client-portal/public', '', $_SERVER['REQUEST_URI']), '?');
$allowed_paths = ['/login', '/actions/handle_login.php']; // Pages accessible without login

// If the user is NOT logged in and is trying to access a protected page...
if (!$is_logged_in && !in_array($path, $allowed_paths)) {
    // ...redirect them to the login page.
    header("Location: " . APP_URL . "/public/login");
    exit();
}
// --------------------------------

// This loads the main layout file, which will handle displaying the correct page
include __DIR__ . '/../src/templates/layout/main.phtml';