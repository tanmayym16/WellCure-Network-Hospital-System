<?php
// Optimized database connection with error handling
$conn = null;
try {
    $conn = mysqli_connect('localhost', 'root', '', 'contact_db');
    if (!$conn) {
        throw new Exception('Database connection failed: ' . mysqli_connect_error());
    }
    // Set charset for better security and internationalization
    mysqli_set_charset($conn, 'utf8mb4');
} catch (Exception $e) {
    $error_message = $e->getMessage();
    // Log error (in a production environment)
    // error_log($error_message);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="WellCure Network Hospital - Quality healthcare with a modern approach. Book appointments online.">
    <meta name="theme-color" content="#4CAF50">
    <title>WellCure Network Hospital - Home</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- GSAP Animation Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
</head>

<body>


<header>
    <nav>
        <div class="logo"><img src="image/wellcure-logo-new.svg" alt="WellCure Network Logo"></div>
        <input type="checkbox" id="menu-toggle">
        <label for="menu-toggle" class="menu-icon">&#9776;</label>
        <div class="nav-links">
            <a href="home.php">Home</a>
            <a href="about.php">About</a>
            <a href="services.php">Services</a>
            <a href="reviews.php">Reviews</a>
            <a href="owner.php">Owner</a>
            <a href="index.php#contact" class="direct-jump">Contact</a>
            <a href="ambulance.php">Ambulance</a>
            <a href="pharmacy.php">E-Pharmacy</a>
        </div>
        <button id="toggle-mode" class="btn theme-toggle-btn">🌙</button>
    </nav>
</header>

<section class="hero" id="home">
    <div class="hero-text-container">
        <h1>Quality Healthcare, Modern Approach</h1>
        <p>Your health is our top priority. Experience compassionate care with cutting-edge technology.</p>
        <a href="index.php#contact" class="btn btn-primary">Book Appointment</a>
        <a href="ambulance.php" class="btn btn-emergency" style="background-color: #dc3545; margin-left: 10px; box-shadow: 0 4px 15px rgba(220, 53, 69, 0.2);">Emergency Ambulance</a>
        <a href="pharmacy.php" class="btn btn-primary" style="background-color: #4CAF50; margin-left: 10px; box-shadow: 0 4px 15px rgba(76, 175, 80, 0.2);">E-Pharmacy</a>
        <a href="services.php" class="btn btn-primary" style="margin-left: 10px;">Services</a>
    </div>
</section>

<footer class="footer">
    <div class="container">
        <div class="footer-logo">
            <img src="image/wellcure-logo-new.svg" alt="WellCure Network Logo" height="40">
            <span class="footer-credit">| Created by Tanmay M.</span>
        </div>
        <p>&copy; 2025 WellCure Network by TanmayMatt. All Rights Reserved.</p>
        <p>Designed with care for your health.</p>
    </div>
</footer>

<a href="https://wa.me/919079778688" target="_blank" class="whatsapp-float" title="Chat with us on WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>

<!-- Script loading -->
<script src="js/script.js"></script>
</body>
</html>
