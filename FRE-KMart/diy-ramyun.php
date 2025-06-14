<?php
require_once 'config/database.php';
require_once 'classes/Product.php';
require_once 'classes/Category.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Initialize objects
$product = new Product($db);

// Get DIY Ramyun products (category_id = 2)
$diy_products = $product->getByCategory(2);

// Get toppings (category_id = 3)
$toppings = $product->getByCategory(3);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DIY Self-Cooking Ramyun - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        /* DIY Ramyun Page Specific Styles */
        .diy-hero {
            background: linear-gradient(135deg, #cd2e3a, #0047a0);
            color: white;
            padding: 4rem 0;
            position: relative;
            overflow: hidden;
        }

        .diy-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="40" r="1.5" fill="rgba(255,255,255,0.15)"/><circle cx="40" cy="80" r="2.5" fill="rgba(255,255,255,0.08)"/></svg>');
        }

        .diy-hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .diy-hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .diy-hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }

        .korean-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            margin: 0 auto 2rem;
            display: inline-block;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .step-counter {
            background: linear-gradient(135deg, #cd2e3a, #0047a0);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            margin: 0 auto 1rem;
            box-shadow: 0 4px 15px rgba(205, 46, 58, 0.4);
        }

        .ramyun-section {
            padding: 4rem 0;
            position: relative;
        }

        .ramyun-section:nth-child(even) {
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
        }

        /* Add extra spacing between process steps and pricing */
        .process-steps {
            margin-bottom: 3rem;
        }

        .pricing-showcase {
            margin-top: 3rem;
            background: linear-gradient(135deg, #34495e, #2c3e50);
            color: white;
            padding: 5rem 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-header h2 {
            color: #2c3e50;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            position: relative;
        }

        .section-header h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(to right, #cd2e3a, #0047a0);
            border-radius: 2px;
        }

        .section-description {
            font-size: 1.2rem;
            color: #666;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .ramyun-base-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            border: 2px solid transparent;
        }

        .ramyun-base-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(205, 46, 58, 0.2);
            border-color: #cd2e3a;
        }

        .ramyun-base-card .product-image {
            height: 220px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            position: relative;
            overflow: hidden;
        }

        .ramyun-base-card .product-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 40%, rgba(205, 46, 58, 0.1) 50%, transparent 60%);
            transform: translateX(-100%);
            transition: transform 0.6s;
        }

        .ramyun-base-card:hover .product-image::before {
            transform: translateX(100%);
        }

        .spice-level {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(135deg, #cd2e3a, #a82329);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .topping-categories {
            display: grid;
            gap: 3rem;
        }

        .topping-tier {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .topping-tier.premium {
            border-color: #cd2e3a;
            background: white;
        }

        .topping-tier.standard {
            border-color: #f39c12;
            background: white;
        }

        .topping-tier-header {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }

        .topping-tier-header h3 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .tier-price-badge {
            background: linear-gradient(135deg, #cd2e3a, #a82329);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-size: 1.2rem;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(205, 46, 58, 0.3);
        }

        .standard .tier-price-badge {
            background: linear-gradient(135deg, #f39c12, #d68910);
            box-shadow: 0 4px 15px rgba(243, 156, 18, 0.3);
        }

        .topping-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }

        .topping-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border-color: #cd2e3a;
        }

        .process-steps {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            padding: 4rem 0;
            position: relative;
        }

        .process-steps::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path d="M0,50 Q25,30 50,50 T100,50" stroke="rgba(255,255,255,0.05)" stroke-width="0.5" fill="none"/></svg>');
        }

        .step-card {
            background: rgba(250, 248, 245, 0.1);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(250, 248, 245, 0.1);
        }

        .step-card:hover {
            background: rgba(250, 248, 245, 0.15);
            transform: translateY(-5px);
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .pricing-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            border: 2px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .pricing-card:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: #cd2e3a;
            transform: translateY(-5px);
        }

        .pricing-card h3 {
            color: #cd2e3a;
            margin-bottom: 1rem;
            font-size: 1.4rem;
        }

        .price-range {
            font-size: 1.1rem;
            margin: 0.5rem 0;
            padding: 0.3rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .price-range:last-child {
            border-bottom: none;
        }

        @media (max-width: 768px) {
            .diy-hero h1 {
                font-size: 2rem;
            }

            .diy-hero-subtitle {
                font-size: 1.1rem;
            }

            .section-header h2 {
                font-size: 2rem;
            }

            .topping-tier {
                padding: 1rem;
            }

            .pricing-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Ramyun Base Tier Styling */
        .ramyun-base-tier {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            border: 2px solid #0047a0;
            transition: all 0.3s ease;
        }

        .ramyun-base-tier-header {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }

        .ramyun-base-tier-header h3 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: #0047a0;
        }
    </style>
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <!-- Enhanced Hero Section -->
    <section class="diy-hero">
        <div class="container">
            <div class="diy-hero-content">
                <div class="korean-badge">
                    <i class="fas fa-fire"></i> 한국 라면 DIY
                </div>
                <h1>DIY Self-Cooking Ramyun</h1>
                <p class="diy-hero-subtitle">
                    Create your perfect Korean ramyun experience with our authentic bases and premium toppings
                </p>
                <div style="margin-top: 2rem;">
                    <a href="#ramyun-bases" class="btn"
                        style="background: rgba(255,255,255,0.2); border: 2px solid white; color: white; margin-right: 1rem;">
                        <i class="fas fa-arrow-down"></i> Start Building
                    </a>
                    <a href="#how-it-works" class="btn"
                        style="background: transparent; border: 2px solid white; color: white;">
                        <i class="fas fa-question-circle"></i> How It Works
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Ramyun Base Selection -->
    <section class="ramyun-section" id="ramyun-bases">
        <div class="container">
            <div class="step-counter">1</div>
            <div class="section-header">
                <h2><i class="fas fa-utensils"></i> Choose Your Ramyun Base</h2>
                <p class="section-description">
                    Start your DIY journey by selecting from our authentic Korean ramyun bases, each with unique flavors
                    and spice levels
                </p>
            </div>

            <!-- Ramyun Base Container -->
            <div class="ramyun-base-tier">
                <div class="ramyun-base-tier-header">
                    <h3>
                        <i class="fas fa-bowl-hot"></i>
                        Authentic Korean Ramyun Bases
                    </h3>
                    <p style="margin-top: 1rem; color: #666;">Choose your foundation for the perfect ramyun experience
                    </p>
                </div>
                <div class="products-grid">
                    <?php while ($row = $diy_products->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="product-card ramyun-base-card">
                            <?php
                            // Determine spice level based on product name
                            $spice_level = "Mild";
                            if (stripos($row['name'], 'super high spicy') !== false) {
                                $spice_level = "🌶️🌶️🌶️ Super High";
                            } elseif (stripos($row['name'], 'spicy') !== false) {
                                $spice_level = "🌶️🌶️ Spicy";
                            } elseif (stripos($row['name'], 'mild') !== false) {
                                $spice_level = "🌶️ Mild";
                            } elseif (stripos($row['name'], 'non-spicy') !== false) {
                                $spice_level = "😌 Non-Spicy";
                            }
                            ?>
                            <div class="spice-level"><?php echo $spice_level; ?></div>

                            <div class="product-image">
                                <img src="assets/images/products/<?php echo $row['image_url']; ?>"
                                    alt="<?php echo htmlspecialchars($row['name']); ?>"
                                    onerror="this.parentElement.innerHTML='<div style=\'display:flex;align-items:center;justify-content:center;height:100%;font-size:3rem;color:#cd2e3a;\'><i class=\'fas fa-bowl-hot\'></i></div>'">
                            </div>
                            <div class="product-info">
                                <h3 class="product-name"><?php echo htmlspecialchars($row['name']); ?></h3>
                                <p class="product-description">
                                    <?php echo htmlspecialchars($row['description']); ?>
                                </p>
                                <div class="product-price">
                                    <span class="current-price" style="font-size: 1.5rem; font-weight: bold;">
                                        <?php echo $product->formatPrice($row['price']); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Toppings Section -->
    <section class="ramyun-section" id="toppings">
        <div class="container">
            <div class="step-counter">2</div>
            <div class="section-header">
                <h2><i class="fas fa-plus-circle"></i> Add Your Favorite Toppings</h2>
                <p class="section-description">
                    Customize your ramyun with our premium and standard toppings. Mix and match to create your perfect
                    bowl!
                </p>
            </div>

            <div class="topping-categories">
                <!-- Premium Toppings -->
                <div class="topping-tier premium">
                    <div class="topping-tier-header">
                        <h3>
                            <i class="fas fa-crown"></i>
                            Premium Toppings
                        </h3>
                        <div class="tier-price-badge">₱20 each</div>
                        <p style="margin-top: 1rem; color: #666;">Elevate your ramyun experience</p>
                    </div>
                    <div class="products-grid">
                        <?php
                        $toppings_20 = $product->getByCategory(3);
                        while ($row = $toppings_20->fetch(PDO::FETCH_ASSOC)):
                            if ($row['price'] == 20.00):
                                ?>
                                <div class="product-card topping-card">
                                    <div class="product-image">
                                        <img src="assets/images/products/<?php echo $row['image_url']; ?>"
                                            alt="<?php echo htmlspecialchars($row['name']); ?>"
                                            onerror="this.parentElement.innerHTML='<div style=\'display:flex;align-items:center;justify-content:center;height:100%;font-size:2rem;color:#cd2e3a;\'><i class=\'fas fa-crown\'></i></div>'">
                                    </div>
                                    <div class="product-info">
                                        <h4 class="product-name"><?php echo htmlspecialchars($row['name']); ?></h4>
                                        <p class="product-description">
                                            <?php echo htmlspecialchars($row['description']); ?>
                                        </p>
                                    </div>
                                </div>
                                <?php
                            endif;
                        endwhile;
                        ?>
                    </div>
                </div>

                <!-- Standard Toppings -->
                <div class="topping-tier standard">
                    <div class="topping-tier-header">
                        <h3>
                            <i class="fas fa-star"></i>
                            Standard Toppings
                        </h3>
                        <div class="tier-price-badge">₱15 each</div>
                        <p style="margin-top: 1rem; color: #666;">Classic favorites to enhance your meal</p>
                    </div>
                    <div class="products-grid">
                        <?php
                        $toppings_15 = $product->getByCategory(3);
                        while ($row = $toppings_15->fetch(PDO::FETCH_ASSOC)):
                            if ($row['price'] == 15.00):
                                ?>
                                <div class="product-card topping-card">
                                    <div class="product-image">
                                        <img src="assets/images/products/<?php echo $row['image_url']; ?>"
                                            alt="<?php echo htmlspecialchars($row['name']); ?>"
                                            onerror="this.parentElement.innerHTML='<div style=\'display:flex;align-items:center;justify-content:center;height:100%;font-size:2rem;color:#f39c12;\'><i class=\'fas fa-star\'></i></div>'">
                                    </div>
                                    <div class="product-info">
                                        <h4 class="product-name"><?php echo htmlspecialchars($row['name']); ?></h4>
                                        <p class="product-description">
                                            <?php echo htmlspecialchars($row['description']); ?>
                                        </p>
                                    </div>
                                </div>
                                <?php
                            endif;
                        endwhile;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="process-steps" id="how-it-works">
        <div class="container">
            <div class="section-header">
                <h2 style="color: white; margin-bottom: 3rem;">How DIY Ramyun Works</h2>
            </div>
            <div class="features-grid">
                <div class="step-card">
                    <div class="step-counter">1</div>
                    <h3>Choose Your Base</h3>
                    <p>Select from our variety of authentic Korean ramyun bases including Jjajangmyeon, K-Ramyun Stir
                        Fry, or K-Ramyun Soup in different spice levels to match your taste.</p>
                </div>
                <div class="step-card">
                    <div class="step-counter">2</div>
                    <h3>Add Toppings</h3>
                    <p>Customize with unlimited toppings! Choose from premium options like Cheese Bun and Lobster Ball,
                        or classic favorites like Egg and Fishcake to create your perfect bowl.</p>
                </div>
                <div class="step-card">
                    <div class="step-counter">3</div>
                    <h3>Cook & Enjoy</h3>
                    <p>We provide everything you need for self-cooking including instructions and utensils. Follow our
                        simple steps and enjoy your authentic Korean ramyun experience!</p>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="assets/js/main.js"></script>

    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.transform = 'translateY(0)';
                    entry.target.style.opacity = '1';
                }
            });
        }, observerOptions);

        // Observe all cards for animation
        document.querySelectorAll('.product-card, .step-card, .pricing-card').forEach(card => {
            card.style.transform = 'translateY(30px)';
            card.style.opacity = '0';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });
    </script>
</body>

</html>