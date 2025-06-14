<?php
require_once 'config/database.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <section class="category-header">
        <div class="container">
            <h1>About SongSong Mart</h1>
            <p>Your trusted Korean convenience store bringing authentic flavors to the Philippines</p>
        </div>
    </section>

    <!-- About Content -->
    <section class="about">
        <div class="container">
            <div class="about-content">
                <div class="about-image">
                    <img src="assets/images/about-songsong.jpg" alt="SongSong Mart Store"
                        onerror="this.src='https://via.placeholder.com/400x300/e74c3c/ffffff?text=SongSong+Mart'">
                </div>
                <div class="about-text">
                    <h2>ABOUT US</h2>
                    <p>
                        SongSong Mart is a Korean mart that offers delicious and affordable Korean food
                        products, including DIY ramyun, EZ cook meals, and we also operate branches.
                        We are located at QWWH+QR3, Bocaue, Bulacan.
                    </p>
                    <p>
                        At SongSong Mart, we bring the taste of Korea to your home through our extensive
                        collection of authentic Korean products. From instant ramyun to traditional
                        snacks, ice cream to premium meat & seafood, we have everything you
                        need to create an authentic Korean dining experience.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Mission -->
    <section class="features">
        <div class="container">
            <h2 class="section-title">Our Mission & Values</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Authentic Quality</h3>
                    <p>We import directly from Korea to ensure you get the most authentic taste and highest quality
                        Korean products available in the Philippines.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Community Focus</h3>
                    <p>We serve multiple communities across Bocaue, Guiguinto, Balagtas, and Tondo, making Korean
                        culture accessible to Filipino families.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3>Fresh & Affordable</h3>
                    <p>Our commitment is to provide fresh, high-quality Korean products at affordable prices that every
                        Filipino family can enjoy.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Store Locations -->
    <section class="products" style="background: #f8f9fa;">
        <div class="container">
            <h2 class="section-title">Our Store Locations</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3>Bocaue Branch</h3>
                    <p>Located at QWWH+QR3, Bocaue, Bulacan. Serving the local community with fresh Korean products
                        and DIY ramyun experience.</p>
                    <div style="margin-top: 1rem;">
                        <span
                            style="background: #e74c3c; color: white; padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.9rem;">
                            Now Open
                        </span>
                    </div>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3>Guiguinto Branch</h3>
                    <p>Conveniently located in Guiguinto, Bulacan. Offering the complete SongSong Mart experience with
                        self-cooking ramyun.</p>
                    <div style="margin-top: 1rem;">
                        <span
                            style="background: #e74c3c; color: white; padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.9rem;">
                            Now Open
                        </span>
                    </div>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3>Balagtas Branch</h3>
                    <p>Serving Balagtas, Bulacan with authentic Korean products. Visit us for the freshest Korean
                        ingredients and snacks.</p>
                    <div style="margin-top: 1rem;">
                        <span
                            style="background: #e74c3c; color: white; padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.9rem;">
                            Now Open
                        </span>
                    </div>
                </div>

            </div>
        </div>
        </div>
    </section>

    <!-- Online Presence -->
    <section class="contact">
        <div class="container">
            <div class="contact-content">
                <div class="contact-info">
                    <h2>Shop Online With Us</h2>
                    <p style="margin-bottom: 2rem;">
                        Can't visit our physical stores? No problem! You can shop our complete range
                        of Korean products through our official online stores.
                    </p>
                    <div class="contact-details">
                        <div class="contact-item">
                            <i class="fas fa-shopping-bag contact-icon"></i>
                            <span>Official Shopee Store</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-store contact-icon"></i>
                            <span>Official Lazada Store</span>
                        </div>
                        <div class="contact-item">
                            <i class="fab fa-tiktok contact-icon"></i>
                            <span>TikTok Shop</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-truck contact-icon"></i>
                            <span>Fast Delivery Nationwide</span>
                        </div>
                    </div>
                </div>
                <div class="social-links">
                    <h3>Connect With Us</h3>
                    <p style="margin-bottom: 2rem;">
                        Follow us on social media for the latest updates, new product arrivals,
                        and special promotions!
                    </p>
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
                        <a href="#" class="social-icon" title="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="assets/js/main.js"></script>
</body>

</html>