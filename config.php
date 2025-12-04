<?php
// DSOG Fashion Store Configuration
define('SITE_NAME', 'DSOG Fashion Store');
define('SITE_URL', 'http://localhost/dsog-fashion-store');
define('ADMIN_EMAIL', 'care.dsogstores@gmail.com');
define('WHATSAPP_NUMBER', '254733737983');

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'dsog_fashion_store');

// Timezone
date_default_timezone_set('Africa/Nairobi');

// Start session
session_start();

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
