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
    <title>WellCure Network Hospital - Services</title>

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

<!-- Services Section -->
<section id="services" class="services-section">
  <div class="container">
    <h2 class="section-title">Our Services</h2>
    <div class="card-grid">
      <div class="card">
        <div class="card-icon">🩺</div>
        <h3>General Checkup</h3>
        <p>Comprehensive health evaluations for all age groups.</p>
      </div>
      <div class="card">
        <div class="card-icon">💉</div>
        <h3>Vaccination</h3>
        <p>Timely and safe vaccinations by certified professionals.</p>
      </div>
      <div class="card">
        <div class="card-icon">🧪</div>
        <h3>Laboratory Tests</h3>
        <p>Accurate and timely diagnostic lab testing services.</p>
      </div>
      <div class="card">
        <div class="card-icon">🏥</div>
        <h3>Specialist Consultation</h3>
        <p>Access to experienced specialists across various fields.</p>
      </div>
      <div class="card">
        <div class="card-icon">🚑</div>
        <h3>Emergency Care</h3>
        <p>24/7 emergency medical services with rapid response teams.</p>
      </div>
      <div class="card">
        <div class="card-icon">👶</div>
        <h3>Pediatric Services</h3>
        <p>Specialized healthcare for infants, children, and adolescents.</p>
      </div>
      <div class="card">
        <div class="card-icon">❤️</div>
        <h3>Cardiology</h3>
        <p>Comprehensive heart care with advanced diagnostic technology.</p>
      </div>
      <div class="card">
        <div class="card-icon">🦴</div>
        <h3>Orthopedics</h3>
        <p>Expert care for bone, joint, and muscle conditions.</p>
      </div>
      <div class="card">
        <div class="card-icon">🧠</div>
        <h3>Mental Health Services</h3>
        <p>Compassionate support for psychological and emotional wellbeing.</p>
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
