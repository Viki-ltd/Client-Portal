<?php
// Database Configuration
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'client_portal_db');
define('DB_USER', 'root');
define('DB_PASS', ''); // 👈 Change this password

// API Configuration
define('PYTHON_API_ENDPOINT', 'http://127.0.0.1:5000/api/enhance');
define('PYTHON_API_KEY', 'a_very_secret_key');

// Application Configuration
define('APP_URL', 'http://localhost/client-portal'); // 👈 Adjust if your local path is different
define('APP_NAME', 'Client Portal');

// Enable error reporting for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);