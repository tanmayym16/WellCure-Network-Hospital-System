<?php
/**
 * Database Configuration File
 * 
 * This file contains the database connection parameters.
 * Update these values according to your hosting environment.
 */

// Database connection parameters
$db_host = 'localhost';     // Database host (usually 'localhost' or provided by your hosting company)
$db_user = 'root';          // Database username (change this for production)
$db_pass = '';              // Database password (change this for production)
$db_name = 'contact_db';    // Database name

// Create database connection
$conn = null;
try {
    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    if (!$conn) {
        throw new Exception('Database connection failed: ' . mysqli_connect_error());
    }
    
    // Set charset for better security and internationalization
    mysqli_set_charset($conn, 'utf8mb4');
    
} catch (Exception $e) {
    $error_message = $e->getMessage();
    // Display error message
    $message = 'Database connection error: ' . $error_message;
    $message_type = 'error';
    // Log error (in a production environment)
    // error_log($error_message);
}
?>