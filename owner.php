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
    <title>WellCure Network Hospital - Owner</title>

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

<section id="owner" class="owner-section fade-in visible">
    <div class="container">
        <h2 class="section-title">About the Owner</h2>
        <div class="owner-content">
            <div class="owner-image">
                <img src="image/pic-1.jpg" alt="Tanmay Mathur" class="owner-avatar">
            </div>
            <div class="owner-info">
                <h3>Tanmay Mathur</h3>
                <p>Tanmay is an enthusiastic and driven Computer Science and Engineering student at JECRC University. With a strong foundation in technology and a passion for improving healthcare systems, Tanmay founded Wellcure Network to bridge the gap between technology and healthcare services.</p>
                <p>As a budding entrepreneur, Tanmay has always been motivated to use innovative solutions to tackle real-world challenges. With a keen interest in hospital management systems, he is dedicated to creating a seamless experience for healthcare providers and patients alike. Wellcure Network reflects his vision of enhancing the efficiency and accessibility of healthcare services through advanced digital solutions.</p>
                <p>Committed to constant learning and growth, Tanmay aims to revolutionize the healthcare landscape with the help of cutting-edge technology and user-centric design.</p>
                <div class="social-links">
                    <a href="https://www.linkedin.com/in/tanmay-mathur-825a9624b/" target="_blank" class="linkedin-link">
                        <i class="fab fa-linkedin"></i> Connect on LinkedIn
                    </a>
                </div>
            </div>
        </div>
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
