<?php
require_once 'config/database.php';

// Handle form submission
$message_sent = false;
$error_message = '';

if ($_POST) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Basic validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_message = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Please enter a valid email address.';
    } else {
        // In a real application, you would save to database or send email
        // For now, we'll just show a success message
        $message_sent = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .contact-form {
            background: white;
            padding: 3rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 2rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #2c3e50;
            font-weight: bold;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 1rem;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #e74c3c;
        }

        .form-group textarea {
            height: 150px;
            resize: vertical;
        }

        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 2rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <section class="category-header">
        <div class="container">
            <h1><i class="fas fa-envelope"></i> Contact Us</h1>
            <p>Get in touch with SongSong Mart - We'd love to hear from you!</p>
        </div>
    </section>

    <!-- Contact Form and Info -->
    <section class="products">
        <div class="container">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: start;">

                <!-- Contact Form -->
                <div class="contact-form">
                    <h2 style="margin-bottom: 2rem; color: #2c3e50;">Send us a Message</h2>

                    <?php if ($message_sent): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            Thank you for your message! We'll get back to you within 24 hours.
                        </div>
                    <?php endif; ?>

                    <?php if ($error_message): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-triangle"></i>
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="contact.php">
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" required
                                value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" required
                                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="subject">Subject *</label>
                            <select id="subject" name="subject" required>
                                <option value="">Select a subject</option>
                                <option value="General Inquiry" <?php echo ($_POST['subject'] ?? '') == 'General Inquiry' ? 'selected' : ''; ?>>General Inquiry</option>
                                <option value="Product Question" <?php echo ($_POST['subject'] ?? '') == 'Product Question' ? 'selected' : ''; ?>>Product Question</option>
                                <option value="Store Location" <?php echo ($_POST['subject'] ?? '') == 'Store Location' ? 'selected' : ''; ?>>Store Location</option>
                                <option value="Online Order" <?php echo ($_POST['subject'] ?? '') == 'Online Order' ? 'selected' : ''; ?>>Online Order Support</option>
                                <option value="Partnership" <?php echo ($_POST['subject'] ?? '') == 'Partnership' ? 'selected' : ''; ?>>Partnership Opportunity</option>
                                <option value="Feedback" <?php echo ($_POST['subject'] ?? '') == 'Feedback' ? 'selected' : ''; ?>>Feedback</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" required
                                placeholder="Tell us how we can help you..."><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                        </div>

                        <button type="submit" class="btn" style="width: 100%;">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>

                <!-- Contact Information -->
                <div>
                    <div class="contact-form">
                        <h2 style="margin-bottom: 2rem; color: #2c3e50;">Get in Touch</h2>

                        <div style="margin-bottom: 2rem;">
                            <div class="contact-item" style="margin-bottom: 1.5rem;">
                                <i class="fas fa-envelope contact-icon"></i>
                                <div>
                                    <strong>Email:</strong><br>
                                    <a href="mailto:songsongmart2024@gmail.com" style="color: #e74c3c;">
                                        songsongmart2024@gmail.com
                                    </a>
                                </div>
                            </div>

                            <div class="contact-item" style="margin-bottom: 1.5rem;">
                                <i class="fas fa-phone contact-icon"></i>
                                <div>
                                    <strong>Phone:</strong><br>
                                    +63 XXX XXX XXXX
                                </div>
                            </div>

                            <div class="contact-item" style="margin-bottom: 1.5rem;">
                                <i class="fas fa-clock contact-icon"></i>
                                <div>
                                    <strong>Business Hours:</strong><br>
                                    Monday - Sunday: 9:00 AM - 9:00 PM
                                </div>
                            </div>
                        </div>

                        <h3 style="margin-bottom: 1rem; color: #2c3e50;">Store Locations</h3>
                        <div style="margin-bottom: 2rem;">
                            <div style="margin-bottom: 1rem;">
                                <i class="fas fa-map-marker-alt" style="color: #e74c3c; margin-right: 0.5rem;"></i>
                                <strong>Bocaue Branch</strong><br>
                                <span style="margin-left: 1.2rem; color: #666;">QWWH+QR3, Bocaue, Bulacan</span>
                            </div>
                            <div style="margin-bottom: 1rem;">
                                <i class="fas fa-map-marker-alt" style="color: #e74c3c; margin-right: 0.5rem;"></i>
                                <strong>Guiguinto Branch</strong><br>
                                <span style="margin-left: 1.2rem; color: #666;">Guiguinto, Bulacan</span>
                            </div>
                            <div style="margin-bottom: 1rem;">
                                <i class="fas fa-map-marker-alt" style="color: #e74c3c; margin-right: 0.5rem;"></i>
                                <strong>Balagtas Branch</strong><br>
                                <span style="margin-left: 1.2rem; color: #666;">Balagtas, Bulacan</span>
                            </div>
                            <div style="margin-bottom: 1rem;">
                                <i class="fas fa-map-marker-alt" style="color: #e74c3c; margin-right: 0.5rem;"></i>
                                <strong>Tondo Branch</strong><br>
                                <span style="margin-left: 1.2rem; color: #666;">Tondo, Manila</span>
                            </div>
                        </div>

                        <h3 style="margin-bottom: 1rem; color: #2c3e50;">Follow Us Online</h3>
                        <div class="social-icons">
                            <a href="#" class="social-icon" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-icon" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-icon" title="TikTok">
                                <i class="fab fa-tiktok"></i>
                            </a>
                            <a href="#" class="social-icon" title="Shopee">
                                <i class="fas fa-shopping-bag"></i>
                            </a>
                            <a href="#" class="social-icon" title="Lazada">
                                <i class="fas fa-store"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="features" style="background: #f8f9fa;">
        <div class="container">
            <h2 class="section-title">Frequently Asked Questions</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem;">
                <div class="feature-card">
                    <h3 style="color: #e74c3c; margin-bottom: 1rem;">
                        <i class="fas fa-question-circle"></i> Do you deliver nationwide?
                    </h3>
                    <p>Yes! We deliver nationwide through our online stores on Shopee, Lazada, and TikTok Shop. Delivery
                        times vary by location, typically 3-7 business days for Metro Manila and 5-10 business days for
                        provincial areas.</p>
                </div>
                <div class="feature-card">
                    <h3 style="color: #e74c3c; margin-bottom: 1rem;">
                        <i class="fas fa-question-circle"></i> Are your products authentic Korean?
                    </h3>
                    <p>Absolutely! We import directly from Korea to ensure authenticity and quality. All our products
                        are genuine Korean brands with proper importation documentation and expiration dates.</p>
                </div>
                <div class="feature-card">
                    <h3 style="color: #e74c3c; margin-bottom: 1rem;">
                        <i class="fas fa-question-circle"></i> Can I dine in at your stores?
                    </h3>
                    <p>Yes! All our physical locations offer comfortable dine-in areas where you can enjoy our DIY
                        ramyun experience. We provide all cooking equipment and utensils for a complete Korean
                        convenience store dining experience.</p>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="assets/js/main.js"></script>
</body>

</html>