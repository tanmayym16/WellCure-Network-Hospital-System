<?php
header('Content-Type: text/html; charset=utf-8');

// Initialize message variables
$message = ''; // Initialize message
$message_type = ''; // Initialize message type

// Include database configuration
require_once 'db_config.php';

// Process medicine order form submission
if(isset($_POST['order_submit'])){
    // Sanitize input data
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $medicine = isset($_POST['medicine']) ? trim($_POST['medicine']) : '';
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;

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

    if(empty($phone)) {
        $errors[] = 'Phone number is required';
    } elseif(!preg_match('/^[0-9]{10}$/', $phone)) {
        $errors[] = 'Please enter a valid 10-digit phone number';
    }

    if(empty($address)) {
        $errors[] = 'Delivery address is required';
    }

    if(empty($medicine)) {
        $errors[] = 'Medicine selection is required';
    }

    if($quantity <= 0) {
        $errors[] = 'Please select a valid quantity';
    }

    // Process form if no errors
    if(empty($errors)) {
        try {
            // Prepare SQL statement
            $sql = "INSERT INTO pharmacy_form (name, email, phone, address, medicine, quantity) 
                    VALUES (?, ?, ?, ?, ?, ?)";

            // Create prepared statement
            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt) {
                // Bind parameters
                mysqli_stmt_bind_param($stmt, 'sssssi', $name, $email, $phone, $address, $medicine, $quantity);

                // Execute statement
                if (mysqli_stmt_execute($stmt)) {
                    $message = 'Your medicine order has been placed successfully! We will contact you shortly.';
                    $message_type = 'success';

                    // Clear form data after successful submission
                    $name = $email = $phone = $address = $medicine = '';
                    $quantity = 1;
                } else {
                    throw new Exception('Error executing statement: ' . mysqli_stmt_error($stmt));
                }

                // Close statement
                mysqli_stmt_close($stmt);
            } else {
                throw new Exception('Error preparing statement: ' . mysqli_error($conn));
            }
        } catch (Exception $e) {
            $message = 'Database error: ' . $e->getMessage();
            $message_type = 'error';
        }
    } else {
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
    <meta name="description" content="WellCure Network Hospital E-Pharmacy - Order medicines online with fast delivery.">
    <meta name="theme-color" content="#4CAF50">
    <title>E-Pharmacy - WellCure Network Hospital</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- GSAP Animation Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <style>
        /* Additional styles for the pharmacy page */
        .medicine-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .medicine-card {
            background: var(--bg-light);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .medicine-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-hover);
        }

        .medicine-image {
            height: 200px;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .medicine-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .medicine-icon {
            font-size: 5rem;
            color: var(--primary-color);
        }

        .medicine-details {
            padding: 1.5rem;
        }

        .medicine-name {
            font-size: 1.3rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .medicine-description {
            color: var(--text-light);
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }

        .medicine-price {
            font-weight: 700;
            color: var(--text-color);
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        .order-btn {
            width: 100%;
            padding: 0.8rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .order-btn:hover {
            background-color: var(--secondary-color);
        }

        .order-form-container {
            max-width: 700px;
            margin: 0 auto;
            padding: 2.5rem;
            background: var(--bg-light);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
        }

        .pharmacy-hero {
            background: linear-gradient(rgba(76, 175, 80, 0.7), rgba(46, 125, 50, 0.7)), url('image/home-img.svg') no-repeat center center/cover;
            color: var(--bg-light);
            padding: 4rem 0;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 40vh;
            position: relative;
            overflow: hidden;
            background-attachment: fixed;
        }

        .pharmacy-hero::before {
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

        .pharmacy-hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: 0 auto;
        }

        .pharmacy-hero h1 {
            font-family: var(--font-primary);
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .pharmacy-hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            font-weight: 300;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .medicine-grid {
                grid-template-columns: 1fr;
            }

            .pharmacy-hero h1 {
                font-size: 2rem;
            }

            .pharmacy-hero p {
                font-size: 1rem;
            }
        }
    </style>
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
            <a href="pharmacy.php" class="active">E-Pharmacy</a>
        </div>
        <button id="toggle-mode" class="btn theme-toggle-btn">🌙</button>
    </nav>
</header>

<!-- Pharmacy Hero Section -->
<section class="pharmacy-hero">
    <div class="pharmacy-hero-content">
        <h1>WellCure E-Pharmacy</h1>
        <p>Order your medicines online and get them delivered to your doorstep. Fast, reliable, and convenient.</p>
        <a href="#order-form" class="btn btn-primary">Order Now</a>
    </div>
</section>

<!-- Medicines Section -->
<section id="medicines" class="fade-in visible">
    <div class="container">
        <h2 class="section-title">Available Medicines</h2>
        <p class="text-center" style="margin-bottom: 2rem;">Browse our selection of high-quality medicines. Click on "Order Now" to add them to your order.</p>

        <div class="medicine-grid">
            <!-- Medicine 1 -->
            <div class="medicine-card">
                <div class="medicine-image">
                    <div class="medicine-icon"><i class="fas fa-pills"></i></div>
                </div>
                <div class="medicine-details">
                    <h3 class="medicine-name">Paracetamol 500mg</h3>
                    <p class="medicine-description">Effective pain reliever and fever reducer. Box of 20 tablets.</p>
                    <p class="medicine-price">₹50.00</p>
                    <a href="#order-form" class="order-btn" onclick="selectMedicine('Paracetamol 500mg')">Order Now</a>
                </div>
            </div>

            <!-- Medicine 2 -->
            <div class="medicine-card">
                <div class="medicine-image">
                    <div class="medicine-icon"><i class="fas fa-capsules"></i></div>
                </div>
                <div class="medicine-details">
                    <h3 class="medicine-name">Amoxicillin 250mg</h3>
                    <p class="medicine-description">Antibiotic for treating bacterial infections. Pack of 10 capsules.</p>
                    <p class="medicine-price">₹120.00</p>
                    <a href="#order-form" class="order-btn" onclick="selectMedicine('Amoxicillin 250mg')">Order Now</a>
                </div>
            </div>

            <!-- Medicine 3 -->
            <div class="medicine-card">
                <div class="medicine-image">
                    <div class="medicine-icon"><i class="fas fa-tablets"></i></div>
                </div>
                <div class="medicine-details">
                    <h3 class="medicine-name">Cetirizine 10mg</h3>
                    <p class="medicine-description">Antihistamine for allergy relief. Box of 10 tablets.</p>
                    <p class="medicine-price">₹45.00</p>
                    <a href="#order-form" class="order-btn" onclick="selectMedicine('Cetirizine 10mg')">Order Now</a>
                </div>
            </div>

            <!-- Medicine 4 -->
            <div class="medicine-card">
                <div class="medicine-image">
                    <div class="medicine-icon"><i class="fas fa-prescription-bottle"></i></div>
                </div>
                <div class="medicine-details">
                    <h3 class="medicine-name">Omeprazole 20mg</h3>
                    <p class="medicine-description">For acid reflux and heartburn relief. Pack of 14 capsules.</p>
                    <p class="medicine-price">₹85.00</p>
                    <a href="#order-form" class="order-btn" onclick="selectMedicine('Omeprazole 20mg')">Order Now</a>
                </div>
            </div>

            <!-- Medicine 5 -->
            <div class="medicine-card">
                <div class="medicine-image">
                    <div class="medicine-icon"><i class="fas fa-pills"></i></div>
                </div>
                <div class="medicine-details">
                    <h3 class="medicine-name">Ibuprofen 400mg</h3>
                    <p class="medicine-description">Anti-inflammatory pain reliever. Box of 15 tablets.</p>
                    <p class="medicine-price">₹60.00</p>
                    <a href="#order-form" class="order-btn" onclick="selectMedicine('Ibuprofen 400mg')">Order Now</a>
                </div>
            </div>

            <!-- Medicine 6 -->
            <div class="medicine-card">
                <div class="medicine-image">
                    <div class="medicine-icon"><i class="fas fa-capsules"></i></div>
                </div>
                <div class="medicine-details">
                    <h3 class="medicine-name">Multivitamin Complex</h3>
                    <p class="medicine-description">Daily multivitamin supplement. Bottle of 60 tablets.</p>
                    <p class="medicine-price">₹250.00</p>
                    <a href="#order-form" class="order-btn" onclick="selectMedicine('Multivitamin Complex')">Order Now</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Order Form Section -->
<section id="order-form" class="fade-in visible">
    <div class="container">
        <h2 class="section-title">Order Medicines</h2>

        <?php if (!empty($message)): ?>
            <p class="form-message <?php echo $message_type; ?>"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <div class="order-form-container">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>#order-form" method="post" class="contact-form">
                <div class="form-group">
                    <label for="name">Your Name:</label>
                    <input type="text" id="name" name="name" placeholder="Enter Your Full Name" required value="<?php echo htmlspecialchars($name ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="email">Your Email:</label>
                    <input type="email" id="email" name="email" placeholder="Enter Your Email Address" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="phone">Your Phone Number:</label>
                    <input type="tel" id="phone" name="phone" placeholder="Enter Your Phone Number" required pattern="[0-9]{10}" value="<?php echo htmlspecialchars($phone ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="address">Delivery Address:</label>
                    <textarea id="address" name="address" rows="3" placeholder="Enter Your Complete Delivery Address" required><?php echo htmlspecialchars($address ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="medicine">Select Medicine:</label>
                    <select id="medicine" name="medicine" required>
                        <option value="">Select a Medicine</option>
                        <option value="Paracetamol 500mg">Paracetamol 500mg - ₹50.00</option>
                        <option value="Amoxicillin 250mg">Amoxicillin 250mg - ₹120.00</option>
                        <option value="Cetirizine 10mg">Cetirizine 10mg - ₹45.00</option>
                        <option value="Omeprazole 20mg">Omeprazole 20mg - ₹85.00</option>
                        <option value="Ibuprofen 400mg">Ibuprofen 400mg - ₹60.00</option>
                        <option value="Multivitamin Complex">Multivitamin Complex - ₹250.00</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" min="1" max="10" value="1" required>
                </div>

                <button type="submit" name="order_submit" class="btn btn-primary btn-submit">Place Order</button>
            </form>
        </div>
    </div>
</section>

<!-- Information Section -->
<section class="fade-in visible">
    <div class="container">
        <h2 class="section-title">Ordering Information</h2>

        <div class="card-grid">
            <div class="card">
                <div class="card-icon"><i class="fas fa-truck"></i></div>
                <h3>Fast Delivery</h3>
                <p>We deliver medicines within 24-48 hours in Jaipur and 3-5 days for other locations.</p>
            </div>

            <div class="card">
                <div class="card-icon"><i class="fas fa-shield-alt"></i></div>
                <h3>Genuine Medicines</h3>
                <p>All our medicines are sourced directly from authorized distributors and manufacturers.</p>
            </div>

            <div class="card">
                <div class="card-icon"><i class="fas fa-credit-card"></i></div>
                <h3>Secure Payment</h3>
                <p>Cash on delivery and all major online payment methods accepted.</p>
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
<script>
    // Function to select medicine in the dropdown
    function selectMedicine(medicineName) {
        const selectElement = document.getElementById('medicine');

        // Find the option with the matching medicine name
        for (let i = 0; i < selectElement.options.length; i++) {
            if (selectElement.options[i].value === medicineName) {
                selectElement.selectedIndex = i;
                break;
            }
        }

        // Smooth scroll to the form
        document.getElementById('order-form').scrollIntoView({
            behavior: 'smooth'
        });
    }

    // Initialize the page
    document.addEventListener('DOMContentLoaded', function() {
        // Add animation to medicine cards
        const medicineCards = document.querySelectorAll('.medicine-card');

        if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
            gsap.registerPlugin(ScrollTrigger);

            gsap.from(medicineCards, {
                opacity: 0,
                y: 50,
                stagger: 0.1,
                duration: 0.7,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: '.medicine-grid',
                    start: 'top 85%',
                    toggleActions: 'play none none none',
                    once: true
                }
            });
        }
    });
</script>
</body>
</html>
<?php
// Close database connection
if ($conn) {
    mysqli_close($conn);
}
?>
