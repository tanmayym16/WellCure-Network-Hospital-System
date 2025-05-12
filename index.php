<?php
header('Content-Type: text/html; charset=utf-8');

// Initialize message variables
$message = ''; // Initialize message
$message_type = ''; // Initialize message type

// Include database configuration
require_once 'db_config.php';

// Define valid services list once for reuse
$valid_services = ['General Checkup', 'Vaccination', 'Laboratory Tests', 'Specialist Consultation', 'Emergency Care', 'Pediatric Services', 'Cardiology', 'Orthopedics', 'Mental Health Services'];

// Initialize form variables
$name = $email = $number = $services = $date = '';

// Process form submission
if(isset($_POST['submit'])){
    // Sanitize input data
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $number = isset($_POST['number']) ? trim($_POST['number']) : '';
    $services = isset($_POST['services']) ? trim($_POST['services']) : '';
    $date = isset($_POST['date']) ? trim($_POST['date']) : '';

    $errors = [];

    // Validation
    if(empty($name)) {
        $errors[] = 'Name is required';
    } elseif(strlen($name) > 100) {
        $errors[] = 'Name must be less than 100 characters';
    }

    if(empty($email)) {
        $errors[] = 'Email is required';
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address';
    } elseif(strlen($email) > 100) {
        $errors[] = 'Email must be less than 100 characters';
    }

    if(empty($number)) {
        $errors[] = 'Phone number is required';
    } elseif(!preg_match('/^[0-9]{10}$/', $number)) {
        $errors[] = 'Please enter a valid 10-digit phone number';
    }

    if(empty($date)) {
        $errors[] = 'Date is required';
    } elseif(strtotime($date) < strtotime(date('Y-m-d'))) {
        $errors[] = 'Please select a future date';
    }

    if(empty($services)) {
        $errors[] = 'Service selection is required';
    } elseif(!in_array($services, $valid_services)) {
        $errors[] = 'Please select a valid service';
    }

    // Process form if no errors
    if(empty($errors) && $conn) {
        // Use prepared statement with improved error handling
        $stmt = mysqli_prepare($conn, "INSERT INTO `contact_form`(name, email, number, services, date) VALUES(?, ?, ?, ?, ?)");

        if ($stmt) {
            // Convert date to datetime format by adding time (00:00:00)
            $datetime = $date . ' 00:00:00';
            mysqli_stmt_bind_param($stmt, 'sssss', $name, $email, $number, $services, $datetime);
            $success = mysqli_stmt_execute($stmt);

            if($success) {
                $message = 'Appointment made successfully!';
                $message_type = 'success';

                // Clear form data after successful submission
                $name = $email = $number = $services = $date = '';
            } else {
                $error = mysqli_stmt_error($stmt);
                $message = 'Appointment failed: ' . $error;
                $message_type = 'error';
                // Log the specific error (in a production environment)
            }

            mysqli_stmt_close($stmt);
        } else {
            $error = mysqli_error($conn);
            $message = 'System error: ' . $error;
            $message_type = 'error';
            // Log the specific error (in a production environment)
        }
    } else if(!empty($errors)) {
        $message = implode('<br>', $errors);
        $message_type = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="WellCure Network Hospital - Quality healthcare with a modern approach. Book appointments online.">
    <meta name="theme-color" content="#4CAF50">
    <title>WellCure Network Hospital - Modern Healthcare</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- GSAP Animation Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
</head>

<body>

<!-- Page Loader -->
<div class="page-loader">
    <div class="loader"></div>
</div>

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

<section id="about" class="about-section fade-in visible">
    <div class="container">
        <div class="about-content">
            <h2 class="section-title">About WellCure Network</h2>
            <p>We are a state-of-the-art hospital dedicated to providing world-class medical facilities and compassionate care. Our team of top-tier medical professionals utilizes the latest advancements to ensure the best outcomes for our patients. From general health check-ups to specialized treatments, we are here to serve you and your family with excellence and empathy.</p>
            <a href="services.php" class="btn btn-secondary">Our Services</a>
        </div>
        <div class="about-image">
            <img src="image/about-img.svg" alt="Modern medical facility or diverse team of doctors at WellCure Network">
        </div>
    </div>
</section>

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

<section id="contact" class="contact-section fade-in visible">
    <div class="container">
        <h2 class="section-title">Contact Us</h2>

        <div class="contact-info">
            <div class="contact-details">
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h3>Phone Number:</h3>
                        <p>+919079778688</p>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-hospital"></i>
                    <div>
                        <h3>Organization:</h3>
                        <p>WellCure Network</p>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h3>Email:</h3>
                        <p>mathurtanmay03@gmail.com</p>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h3>Location:</h3>
                        <p>Jaipur, Rajasthan</p>
                    </div>
                </div>
                <div class="contact-message">
                    <p>Feel free to reach out</p>
                </div>
            </div>
        </div>

        <h2 class="section-title">Book an Appointment</h2>
        <?php if (!empty($message)): ?>
            <p class="form-message <?php echo $message_type; ?>"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>#contact" method="post" class="contact-form">
            <div class="form-group">
                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter Your Full Name" required>
            </div>
            <div class="form-group">
                <label for="email">Your Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter Your Email Address" required>
            </div>
            <div class="form-group">
                <label for="number">Your Phone Number:</label>
                <input type="tel" id="number" name="number" placeholder="Enter Your Phone Number" required pattern="[0-9]{10}">
            </div>
            <div class="form-group">
                <label for="services">Required Service:</label>
                <select id="services" name="services" required>
                    <option value="">Select a Service</option>
                    <option value="General Checkup">General Checkup</option>
                    <option value="Vaccination">Vaccination</option>
                    <option value="Laboratory Tests">Laboratory Tests</option>
                    <option value="Specialist Consultation">Specialist Consultation</option>
                    <option value="Emergency Care">Emergency Care</option>
                    <option value="Pediatric Services">Pediatric Services</option>
                    <option value="Cardiology">Cardiology</option>
                    <option value="Orthopedics">Orthopedics</option>
                    <option value="Mental Health Services">Mental Health Services</option>
                </select>
            </div>
            <div class="form-group">
                <label for="date">Preferred Date:</label>
                <input type="date" id="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
            </div>
            <button type="submit" name="submit" class="btn btn-primary btn-submit">Submit Appointment</button>
        </form>

        <div class="map-container">
            <h3 class="subsection-title">Our Location</h3>
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3559.1200082015113!2d75.7971233!3d26.8617046!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x396db6703d0e3ebf%3A0xf8c1db55a3c7c11e!2sJECRC%20University!5e0!3m2!1sen!2sin!4v1654321234567!5m2!1sen!2sin"
                width="100%" height="400" style="border:0; border-radius: var(--radius);" allowfullscreen=""
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</section>

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
<?php
// Close database connection
if ($conn) {
    mysqli_close($conn);
}
?>
