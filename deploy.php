<?php
/**
 * WellCure Network Hospital - Deployment Script
 * 
 * This script helps with the deployment process by:
 * 1. Checking database connection
 * 2. Creating database tables if they don't exist
 * 3. Verifying file permissions
 * 4. Testing basic functionality
 */

// Display header
echo "=======================================================\n";
echo "WellCure Network Hospital - Deployment Assistant\n";
echo "=======================================================\n\n";

// Step 1: Check database connection
echo "Step 1: Checking database connection...\n";

// Include database configuration
require_once 'db_config.php';

if (!$conn) {
    echo "❌ Database connection failed. Please check your db_config.php file.\n";
    echo "   Error: " . mysqli_connect_error() . "\n\n";
    exit;
}

echo "✅ Database connection successful!\n\n";

// Step 2: Check if tables exist and create them if they don't
echo "Step 2: Checking database tables...\n";

// Check if contact_form table exists
$tableExists = mysqli_query($conn, "SHOW TABLES LIKE 'contact_form'");
if (mysqli_num_rows($tableExists) == 0) {
    echo "   - 'contact_form' table does not exist. Creating...\n";
    
    // Read SQL file
    $sql = file_get_contents('contact_form.sql');
    
    // Execute SQL
    if (mysqli_multi_query($conn, $sql)) {
        do {
            // Consume results to allow next query execution
            if ($result = mysqli_store_result($conn)) {
                mysqli_free_result($result);
            }
        } while (mysqli_next_result($conn));
        echo "   ✅ 'contact_form' table created successfully!\n";
    } else {
        echo "   ❌ Error creating 'contact_form' table: " . mysqli_error($conn) . "\n";
    }
} else {
    echo "   ✅ 'contact_form' table already exists.\n";
}

// Check if ambulance_bookings table exists
$tableExists = mysqli_query($conn, "SHOW TABLES LIKE 'ambulance_bookings'");
if (mysqli_num_rows($tableExists) == 0) {
    echo "   - 'ambulance_bookings' table does not exist. Creating...\n";
    
    // Read SQL file
    $sql = file_get_contents('ambulance_bookings.sql');
    
    // Execute SQL
    if (mysqli_multi_query($conn, $sql)) {
        do {
            // Consume results to allow next query execution
            if ($result = mysqli_store_result($conn)) {
                mysqli_free_result($result);
            }
        } while (mysqli_next_result($conn));
        echo "   ✅ 'ambulance_bookings' table created successfully!\n";
    } else {
        echo "   ❌ Error creating 'ambulance_bookings' table: " . mysqli_error($conn) . "\n";
    }
} else {
    echo "   ✅ 'ambulance_bookings' table already exists.\n";
}

echo "\n";

// Step 3: Check file permissions
echo "Step 3: Checking file permissions...\n";

$requiredFiles = [
    'index.php',
    'about.php',
    'services.php',
    'reviews.php',
    'ambulance.php',
    'pharmacy.php',
    'css/style.css',
    'js/script.js',
    'db_config.php',
    '.htaccess'
];

$allFilesAccessible = true;

foreach ($requiredFiles as $file) {
    if (file_exists($file)) {
        if (is_readable($file)) {
            echo "   ✅ File '$file' is readable.\n";
        } else {
            echo "   ❌ File '$file' exists but is not readable. Please check permissions.\n";
            $allFilesAccessible = false;
        }
    } else {
        echo "   ❌ File '$file' does not exist.\n";
        $allFilesAccessible = false;
    }
}

if ($allFilesAccessible) {
    echo "   All required files are accessible.\n";
} else {
    echo "   Some files are missing or not accessible. Please check the file structure.\n";
}

echo "\n";

// Step 4: Check PHP version and extensions
echo "Step 4: Checking PHP environment...\n";

echo "   PHP Version: " . phpversion() . "\n";
if (version_compare(phpversion(), '7.0.0', '<')) {
    echo "   ❌ PHP version is below 7.0. Some features may not work correctly.\n";
} else {
    echo "   ✅ PHP version is 7.0 or higher.\n";
}

if (extension_loaded('mysqli')) {
    echo "   ✅ MySQLi extension is loaded.\n";
} else {
    echo "   ❌ MySQLi extension is not loaded. Please enable it in your PHP configuration.\n";
}

echo "\n";

// Step 5: Summary
echo "Step 5: Deployment Summary\n";

if ($conn && $allFilesAccessible && extension_loaded('mysqli') && version_compare(phpversion(), '7.0.0', '>=')) {
    echo "✅ Your environment is ready for WellCure Network Hospital!\n";
    echo "   You can access the website at: http://localhost/hospitizer/ (if using localhost)\n";
} else {
    echo "❌ There are some issues that need to be resolved before deployment.\n";
    echo "   Please review the messages above and fix any issues.\n";
}

echo "\nFor more detailed deployment instructions, please refer to the README.md file.\n";
echo "=======================================================\n";

// Close database connection
if ($conn) {
    mysqli_close($conn);
}
?>