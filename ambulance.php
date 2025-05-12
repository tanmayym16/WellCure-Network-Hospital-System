<?php
// Include database configuration
require_once 'db_config.php';

$message = ''; // Initialize message
$message_type = ''; // Initialize message type

if(isset($_POST['submit_ambulance'])){
    // Sanitize and validate input data
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $landmark = isset($_POST['landmark']) ? trim($_POST['landmark']) : '';
    $emergency_type = isset($_POST['emergency_type']) ? trim($_POST['emergency_type']) : '';

    $errors = [];

    // Validation
    if(empty($name)) {
        $errors[] = 'Name is required';
    } elseif(strlen($name) > 100) {
        $errors[] = 'Name must be less than 100 characters';
    }

    if(empty($phone)) {
        $errors[] = 'Phone number is required';
    } elseif(!preg_match('/^[0-9]{10}$/', $phone)) {
        $errors[] = 'Please enter a valid 10-digit phone number';
    }

    if(empty($address)) {
        $errors[] = 'Address is required';
    } elseif(strlen($address) > 255) {
        $errors[] = 'Address must be less than 255 characters';
    }

    if(empty($landmark)) {
        $errors[] = 'Landmark is required';
    } elseif(strlen($landmark) > 100) {
        $errors[] = 'Landmark must be less than 100 characters';
    }

    if(empty($emergency_type)) {
        $errors[] = 'Emergency type is required';
    } elseif(!in_array($emergency_type, ['Medical Emergency', 'Accident', 'Cardiac Arrest', 'Respiratory Distress', 'Pregnancy', 'Other'])) {
        $errors[] = 'Please select a valid emergency type';
    }

    // Process form if no errors
    if(empty($errors) && $conn) {
        // Use prepared statement to prevent SQL injection
        $stmt = mysqli_prepare($conn, "INSERT INTO `ambulance_bookings`(name, phone, address, landmark, emergency_type) VALUES(?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'sssss', $name, $phone, $address, $landmark, $emergency_type);

        $success = mysqli_stmt_execute($stmt);

        if($success) {
            $message = 'Ambulance dispatched! Our team will reach you within 10 minutes.';
            $message_type = 'success';

            // Clear form data after successful submission
            $name = $phone = $address = $landmark = $emergency_type = '';
        } else {
            $message = 'Booking failed. Please try again or call our emergency number.';
            $message_type = 'error';
            // Log the specific error (in a production environment)
            // error_log('Database error: ' . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);
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
    <meta name="description" content="WellCure Network Hospital - 10 Minutes Ambulance Service. Book an ambulance for emergency medical assistance.">
    <meta name="theme-color" content="#4CAF50">
    <title>WellCure Network Hospital - 10 Minutes Ambulance Service</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- GSAP Animation Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <style>
        /* Additional styles for ambulance page */
        .ambulance-hero {
            background: linear-gradient(rgba(220, 53, 69, 0.8), rgba(165, 29, 42, 0.8)), url('image/appointment-img.svg') no-repeat center center/cover;
            color: var(--bg-light);
            padding: 6rem 0;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 60vh;
            position: relative;
            overflow: hidden;
            background-attachment: fixed;
        }

        .ambulance-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, transparent 20%, rgba(0, 0, 0, 0.2) 100%);
            opacity: 0.8;
            z-index: 1;
        }

        .timer-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 2rem 0;
        }

        .timer {
            background-color: rgba(255, 255, 255, 0.9);
            color: #dc3545;
            font-size: 3rem;
            font-weight: 700;
            padding: 1rem 2rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            display: inline-block;
            margin: 0 auto;
        }

        .emergency-badge {
            background-color: #dc3545;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
            }
            70% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }

        .ambulance-form {
            max-width: 700px;
            margin: 0 auto 3rem auto;
            padding: 2.5rem;
            background: var(--bg-light);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            position: relative;
        }

        .ambulance-form::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background-color: #dc3545;
            border-top-left-radius: var(--radius);
            border-bottom-left-radius: var(--radius);
        }

        .ambulance-form .btn-submit {
            background-color: #dc3545;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.2);
        }

        .ambulance-form .btn-submit:hover {
            background-color: #c82333;
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
        }

        .ambulance-info {
            background-color: rgba(220, 53, 69, 0.1);
            border-left: 4px solid #dc3545;
            padding: 1.5rem;
            border-radius: var(--radius);
            margin-bottom: 2rem;
        }

        .ambulance-info h3 {
            color: #dc3545;
            margin-top: 0;
        }

        .ambulance-info ul {
            padding-left: 1.5rem;
        }

        .ambulance-info li {
            margin-bottom: 0.5rem;
        }

        .emergency-contact {
            font-size: 1.2rem;
            font-weight: 700;
            color: #dc3545;
            margin-top: 1rem;
            display: block;
        }

        .form-group select {
            width: 100%;
            padding: 0.9rem 1rem;
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            font-size: 1rem;
            font-family: var(--font-secondary);
            box-sizing: border-box;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23343a40' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 16px 12px;
        }

        .form-group select:focus {
            border-color: #dc3545;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .form-group textarea {
            width: 100%;
            padding: 0.9rem 1rem;
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            font-size: 1rem;
            font-family: var(--font-secondary);
            box-sizing: border-box;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            min-height: 100px;
            resize: vertical;
        }

        .form-group textarea:focus {
            border-color: #dc3545;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
    </style>
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
            <a href="ambulance.php" class="active">Ambulance</a>
            <a href="pharmacy.php">E-Pharmacy</a>
        </div>
        <button id="toggle-mode" class="btn theme-toggle-btn">🌙</button>
    </nav>
</header>

<section class="ambulance-hero" id="ambulance-hero">
    <div class="hero-text-container">
        <span class="emergency-badge"><i class="fas fa-ambulance"></i> EMERGENCY SERVICE</span>
        <h1>10 Minutes Ambulance Service</h1>
        <p>Our ambulance will reach you within 10 minutes of booking. Fast, reliable emergency medical transportation when you need it most.</p>
        <div class="timer-container">
            <div class="timer">10:00</div>
        </div>
        <a href="#book-ambulance" class="btn btn-primary">Book Now</a>
    </div>
</section>

<section id="book-ambulance" class="contact-section fade-in visible">
    <div class="container">
        <h2 class="section-title">Book an Ambulance</h2>

        <div class="ambulance-info">
            <h3>Emergency Ambulance Service</h3>
            <p>Our 10-minute ambulance service ensures rapid response to medical emergencies. Our ambulances are:</p>
            <ul>
                <li>Fully equipped with advanced life support systems</li>
                <li>Staffed with trained paramedics and emergency medical technicians</li>
                <li>Available 24/7 for immediate dispatch</li>
                <li>Tracked in real-time for efficient routing</li>
            </ul>
            <p>For immediate assistance, you can also call our emergency hotline:</p>
            <span class="emergency-contact"><i class="fas fa-phone-alt"></i> +91 9079778688</span>
        </div>

        <?php if (!empty($message)): ?>
            <p class="form-message <?php echo $message_type; ?>"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>#book-ambulance" method="post" class="ambulance-form">
            <div class="form-group">
                <label for="name">Patient/Contact Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter Full Name" required value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" placeholder="Enter 10-digit Phone Number" required pattern="[0-9]{10}" value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="address">Pickup Address:</label>
                <textarea id="address" name="address" placeholder="Enter Complete Address" required><?php echo isset($address) ? htmlspecialchars($address) : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label for="landmark">Nearest Landmark:</label>
                <input type="text" id="landmark" name="landmark" placeholder="Enter a Nearby Landmark" required value="<?php echo isset($landmark) ? htmlspecialchars($landmark) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="emergency_type">Emergency Type:</label>
                <select id="emergency_type" name="emergency_type" required>
                    <option value="" <?php echo !isset($emergency_type) || empty($emergency_type) ? 'selected' : ''; ?>>Select Emergency Type</option>
                    <option value="Medical Emergency" <?php echo isset($emergency_type) && $emergency_type == 'Medical Emergency' ? 'selected' : ''; ?>>Medical Emergency</option>
                    <option value="Accident" <?php echo isset($emergency_type) && $emergency_type == 'Accident' ? 'selected' : ''; ?>>Accident</option>
                    <option value="Cardiac Arrest" <?php echo isset($emergency_type) && $emergency_type == 'Cardiac Arrest' ? 'selected' : ''; ?>>Cardiac Arrest</option>
                    <option value="Respiratory Distress" <?php echo isset($emergency_type) && $emergency_type == 'Respiratory Distress' ? 'selected' : ''; ?>>Respiratory Distress</option>
                    <option value="Pregnancy" <?php echo isset($emergency_type) && $emergency_type == 'Pregnancy' ? 'selected' : ''; ?>>Pregnancy</option>
                    <option value="Other" <?php echo isset($emergency_type) && $emergency_type == 'Other' ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
            <button type="submit" name="submit_ambulance" class="btn btn-primary btn-submit">Request Ambulance Now</button>
        </form>
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

<!-- Countdown timer for ambulance arrival -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timerElement = document.querySelector('.timer');
        let timeLeft = 600; // 10 minutes in seconds

        function updateTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

            if (timeLeft > 0) {
                timeLeft--;
                setTimeout(updateTimer, 1000);
            } else {
                timerElement.textContent = "ARRIVED";
                timerElement.style.backgroundColor = "#4CAF50";
            }
        }

        // Start the timer when the page loads
        updateTimer();
    });
</script>
</body>
</html>
