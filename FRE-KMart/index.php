<?php
require_once 'config/database.php';
require_once 'classes/product.php';
require_once 'classes/category.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Initialize objects
$product = new Product($db);
$category = new Category($db);

// Get featured products
$featured_products = $product->getFeatured();

// Get categories
$categories = $category->getAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Korean Convenience Store</title>
    <meta name="description" content="<?php echo SITE_DESCRIPTION; ?>">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>Song Song Mart</h1>
                    <div class="hero-tagline">
                        <span style="color: #fff;">송송마트</span> KOREAN CONVENIENCE STORE
                    </div>
                    <p class="hero-subtitle">
                        Authentic Korean food products, including DIY ramyun, EZ cook meals,
                        and fresh imports. We bring the taste of Korea to your home through
                        our online platform on Shopee, Lazada, and TikTok Shop.
                    </p>
                </div>
                <div class="hero-image">
                    <img src="assets/images/hero-ramen.png" alt="Korean Ramen Bowl" style="width: 500px; height: auto;">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <h3>Wide Selection</h3>
                    <p>Explore our extensive range of authentic Korean food products, from instant ramyun to snacks,
                        ice cream, and meat & seafood.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-fire"></i>
                    </div>
                    <h3>Dine-In Experience</h3>
                    <p>Enjoy our self-cooking ramyun experience with various ramyun flavors and unlimited toppings for
                        the ultimate Korean meal.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3>Fresh Imports</h3>
                    <p>We regularly import fresh products directly from Korea and are located at QWWH+QR3, Bocaue,
                        Bulacan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about">
        <div class="container">
            <div class="about-content">
                <div class="about-image">
                    <img src="assets/images/about-image.png" alt="About SongSong Mart">
                </div>
                <div class="about-text">
                    <h2>ABOUT US</h2>
                    <p>
                        SongSong Mart is a Korean mart that offers delicious and affordable Korean food
                        products, including DIY ramyun, EZ cook meals, and we also operate branches.
                        We are located in various areas such as Bocaue, Guiguinto, and Balagtas, Tondo,
                        and we are also available online through our online platforms on Shopee, Lazada,
                        and TikTok Shop.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="products">
        <div class="container">
            <h2 class="section-title">PRODUCT CATEGORIES</h2>

            <!-- Categories Navigation -->
            <div class="categories-nav">
                <a href="products.php" class="category-btn active">All Products</a>
                <?php while ($row = $categories->fetch(PDO::FETCH_ASSOC)): ?>
                    <a href="products.php?category=<?php echo $row['id']; ?>" class="category-btn">
                        <?php echo htmlspecialchars($row['name']); ?>
                    </a>
                <?php endwhile; ?>
            </div>

            <!-- Featured Products Grid -->
            <div class="products-grid">
                <?php while ($row = $featured_products->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="assets/images/products/<?php echo $row['image_url']; ?>"
                                alt="<?php echo htmlspecialchars($row['name']); ?>"
                                onerror="this.parentElement.innerHTML='<i class=\'fas fa-image\'></i> Product Image'">
                        </div>
                        <div class="product-info">
                            <h3 class="product-name"><?php echo htmlspecialchars($row['name']); ?></h3>
                            <p class="product-description">
                                <?php echo htmlspecialchars(substr($row['description'], 0, 80)) . '...'; ?>
                            </p>
                            <div class="product-price">
                                <span class="current-price">
                                    <?php echo $product->formatPrice($row['price']); ?>
                                </span>
                                <?php if ($row['old_price']): ?>
                                    <span class="old-price">
                                        <?php echo $product->formatPrice($row['old_price']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div style="text-align: center; margin-top: 3rem;">
                <a href="products.php" class="btn">View All Products</a>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact">
        <div class="container">
            <div class="contact-content">
                <div class="contact-info">
                    <h2>CONTACT US</h2>
                    <div class="contact-details">
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt contact-icon"></i>
                            <span>QWWH+QR3, Bocaue, Bulacan</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone contact-icon"></i>
                            <span>+63 123 456 7891</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope contact-icon"></i>
                            <span>songsongmart2024@gmail.com</span>
                        </div>
                    </div>
                </div>
                <div class="social-links">
                    <h3>FOLLOW US</h3>
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
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script src="assets/js/main.js"></script>
</body>

</html>