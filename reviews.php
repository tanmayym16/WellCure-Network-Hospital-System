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
    <title>WellCure Network Hospital - Reviews</title>

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

<section id="reviews" class="testimonials-section fade-in visible">
    <div class="container">
        <h2 class="section-title">Reviews</h2>
        <div class="testimonial-slider">
            <div class="slide">
                <img src="image/emily.jpeg" alt="Emily W." class="testimonial-avatar">
                <p>"The doctors at WellCure Network were incredibly professional and kind. This is the best care I've ever received!"</p>
                <span>– Emily W.</span>
            </div>
            <div class="slide">
                <img src="image/jack.png" alt="Jack B." class="testimonial-avatar">
                <p>"The quick ambulance service from WellCure Network was a lifesaver for my father. Highly recommended for their efficiency!"</p>
                <span>– Jack B.</span>
            </div>
            <div class="slide">
                <img src="image/pic-1.jpg" alt="Tanmay M." class="testimonial-avatar">
                <p>"Booking my appointment online was so easy and convenient. The entire experience at WellCure Network was great."</p>
                <span>– Tanmay M.</span>
            </div>
            <div class="slide">
                <img src="image/doc-1.jpg" alt="Priya S." class="testimonial-avatar">
                <p>"The pediatric services at WellCure Network are exceptional. My children always feel comfortable and well-cared for during their visits."</p>
                <span>– Priya S.</span>
            </div>
            <div class="slide">
                <img src="image/doc-2.jpg" alt="Rahul K." class="testimonial-avatar">
                <p>"I had an emergency situation and the response from WellCure Network was prompt and professional. Their emergency care team saved my life."</p>
                <span>– Rahul K.</span>
            </div>
            <div class="slide">
                <img src="image/doc-3.jpg" alt="Ananya P." class="testimonial-avatar">
                <p>"The cardiology department at WellCure Network is equipped with the latest technology. The doctors explained everything clearly and made me feel at ease."</p>
                <span>– Ananya P.</span>
            </div>
            <div class="slide">
                <img src="image/doc-4.jpg" alt="Vikram S." class="testimonial-avatar">
                <p>"After my orthopedic surgery, the rehabilitation program at WellCure Network helped me recover faster than expected. Highly professional staff!"</p>
                <span>– Vikram S.</span>
            </div>
            <div class="slide">
                <img src="image/doc-5.jpg" alt="Meera J." class="testimonial-avatar">
                <p>"The mental health services at WellCure Network have been transformative for me. The therapists are compassionate and truly understand their patients' needs."</p>
                <span>– Meera J.</span>
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
