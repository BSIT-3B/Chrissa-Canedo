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

// Handle search
$search_keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_filter = isset($_GET['category']) ? (int) $_GET['category'] : 0;

// Get products based on search or category
if (!empty($search_keyword)) {
    $products = $product->search($search_keyword);
    $page_title = "Search Results for: " . htmlspecialchars($search_keyword);
} elseif ($category_filter > 0) {
    $products = $product->getByCategory($category_filter);
    $category->getById($category_filter);
    $page_title = "Products in: " . $category->name;
} else {
    $products = $product->getAll();
    $page_title = "All Products";
}

// Get all categories for filter
$categories = $category->getAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <section class="category-header">
        <div class="container">
            <h1><?php echo $page_title; ?></h1>
            <p>Discover our authentic Korean food products</p>
        </div>
    </section>

    <section class="products">
        <div class="container">
            <!-- Modern Search and Filter Section -->
            <div class="search-section">
                <div class="search-header">
                    <h2>Find Your Korean Products</h2>
                    <p>Search through our authentic Korean food collection</p>
                </div>

                <div class="search-controls">
                    <form method="GET" action="products.php" class="search-form">
                        <div class="search-grid">
                            <!-- Search Input -->
                            <div class="search-input-container">
                                <div class="search-box">
                                    <i class="fas fa-search"></i>
                                    <input type="text" name="search"
                                        placeholder="Search products, brands, or ingredients..."
                                        value="<?php echo htmlspecialchars($search_keyword); ?>" class="search-field">
                                    <?php if (!empty($search_keyword)): ?>
                                        <button type="button" class="clear-btn" onclick="clearSearch()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Category Filter -->
                            <div class="filter-container">
                                <select name="category" class="category-filter" onchange="this.form.submit()">
                                    <option value="0">All Categories</option>
                                    <?php
                                    $categories_for_filter = $category->getAll();
                                    while ($cat = $categories_for_filter->fetch(PDO::FETCH_ASSOC)):
                                        ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php echo ($category_filter == $cat['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- Search Button -->
                            <div class="search-actions">
                                <button type="submit" class="search-btn">
                                    <i class="fas fa-search"></i>
                                    Search
                                </button>

                                <?php if (!empty($search_keyword) || $category_filter > 0): ?>
                                    <a href="products.php" class="reset-btn">
                                        <i class="fas fa-refresh"></i>
                                        Reset
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>

                    <!-- Active Filters Display -->
                    <?php if (!empty($search_keyword) || $category_filter > 0): ?>
                        <div class="active-filters-bar">
                            <span class="filters-label">Active Filters:</span>

                            <?php if (!empty($search_keyword)): ?>
                                <span class="filter-pill">
                                    <i class="fas fa-search"></i>
                                    "<?php echo htmlspecialchars($search_keyword); ?>"
                                    <a href="<?php echo $category_filter > 0 ? "products.php?category={$category_filter}" : "products.php"; ?>"
                                        class="remove-pill">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            <?php endif; ?>

                            <?php if ($category_filter > 0): ?>
                                <?php
                                $category_temp = new Category($db);
                                $category_temp->getById($category_filter);
                                ?>
                                <span class="filter-pill">
                                    <i class="fas fa-tag"></i>
                                    <?php echo htmlspecialchars($category_temp->name); ?>
                                    <a href="<?php echo !empty($search_keyword) ? "products.php?search=" . urlencode($search_keyword) : "products.php"; ?>"
                                        class="remove-pill">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="products-grid" id="products-container">
                <?php
                $product_count = 0;
                while ($row = $products->fetch(PDO::FETCH_ASSOC)):
                    $product_count++;
                    ?>
                    <div class="product-card">
                        <?php if ($row['old_price'] && $row['old_price'] > $row['price']): ?>
                            <div class="promo-badge">SALE</div>
                        <?php endif; ?>

                        <div class="product-image">
                            <img src="assets/images/products/<?php echo $row['image_url']; ?>"
                                alt="<?php echo htmlspecialchars($row['name']); ?>"
                                onerror="this.parentElement.innerHTML='<i class=\'fas fa-image\'></i> Product Image'">
                        </div>

                        <div class="product-info">
                            <h3 class="product-name"><?php echo htmlspecialchars($row['name']); ?></h3>
                            <p class="product-description">
                                <?php echo htmlspecialchars(substr($row['description'], 0, 100)) . '...'; ?>
                            </p>
                            <div class="product-price">
                                <span class="current-price">
                                    <?php echo $product->formatPrice($row['price']); ?>
                                </span>
                                <?php if ($row['old_price'] && $row['old_price'] > $row['price']): ?>
                                    <span class="old-price">
                                        <?php echo $product->formatPrice($row['old_price']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div style="margin-top: 1rem;">
                                <span
                                    style="background: #e74c3c; color: white; padding: 0.3rem 0.6rem; border-radius: 15px; font-size: 0.8rem;">
                                    <?php echo htmlspecialchars($row['category_name']); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <?php if ($product_count == 0): ?>
                <div class="no-results">
                    <h3><i class="fas fa-search"></i> No Products Found</h3>
                    <p>Sorry, we couldn't find any products matching your criteria.</p>
                    <a href="products.php" class="btn" style="margin-top: 1rem;">View All Products</a>
                </div>
            <?php else: ?>
                <div style="text-align: center; margin-top: 2rem; color: #666;">
                    <p>Showing <?php echo $product_count; ?> product(s)</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="assets/js/main.js"></script>

    <!-- Enhanced Search & Filter JavaScript -->
    <script>
        function clearSearch() {
            const searchField = document.querySelector('.search-field');
            searchField.value = '';

            // Submit form to refresh with cleared search
            const form = document.querySelector('.search-form');
            form.submit();
        }

        document.addEventListener('DOMContentLoaded', function () {
            const searchField = document.querySelector('.search-field');
            const clearBtn = document.querySelector('.clear-btn');

            // Handle search input
            if (searchField) {
                searchField.addEventListener('input', function () {
                    if (clearBtn) {
                        clearBtn.style.display = this.value.length > 0 ? 'flex' : 'none';
                    }
                });

                // Handle enter key
                searchField.addEventListener('keypress', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const form = document.querySelector('.search-form');
                        form.submit();
                    }
                });
            }

            // Auto-scroll to results after filter/search
            if (window.location.search) {
                setTimeout(function () {
                    const productsGrid = document.querySelector('.products-grid');
                    if (productsGrid) {
                        productsGrid.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }, 300);
            }
        });
    </script>
</body>

</html>