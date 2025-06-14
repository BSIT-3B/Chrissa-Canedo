<?php
require_once 'config/database.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin.php');
    exit();
}

// Initialize database connection
$db = new Database();
$conn = $db->getConnection();

// Handle CRUD operations
$response = ['success' => false, 'message' => '', 'type' => 'info'];

// Image upload function
function handleImageUpload($file, $oldImageUrl = '')
{
    $uploadDir = 'assets/images/products/';

    // Create directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Check if file was uploaded
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return $oldImageUrl; // Keep existing image if no new file uploaded
    }

    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Upload failed with error code: ' . $file['error']);
    }

    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $fileType = mime_content_type($file['tmp_name']);

    if (!in_array($fileType, $allowedTypes)) {
        throw new Exception('Invalid file type. Please upload JPEG, PNG, GIF, or WebP images only.');
    }

    // Validate file size (max 5MB)
    $maxSize = 5 * 1024 * 1024; // 5MB in bytes
    if ($file['size'] > $maxSize) {
        throw new Exception('File size too large. Maximum size is 5MB.');
    }

    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'product_' . uniqid() . '_' . time() . '.' . $extension;
    $filepath = $uploadDir . $filename;

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        throw new Exception('Failed to save uploaded file.');
    }

    // Delete old image if it exists and is not a placeholder
    if ($oldImageUrl && $oldImageUrl !== '' && !strpos($oldImageUrl, 'placeholder-')) {
        $oldFilePath = $uploadDir . $oldImageUrl;
        if (file_exists($oldFilePath)) {
            unlink($oldFilePath);
        }
    }

    return $filename;
}

// Add new product with image upload
if (isset($_POST['add'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
    $stock = filter_var($_POST['stock'], FILTER_VALIDATE_INT);
    $category_id = filter_var($_POST['category_id'], FILTER_VALIDATE_INT);
    $min_stock = filter_var($_POST['min_stock'] ?? 10, FILTER_VALIDATE_INT);

    // Enhanced validation
    $errors = [];
    if (!$name || strlen($name) < 2)
        $errors[] = "Product name must be at least 2 characters.";
    if (!$description || strlen($description) < 10)
        $errors[] = "Description must be at least 10 characters.";
    if ($price === false || $price < 0)
        $errors[] = "Price must be a valid positive number.";
    if ($stock === false || $stock < 0)
        $errors[] = "Stock must be a valid non-negative number.";
    if (!$category_id)
        $errors[] = "Please select a valid category.";

    if (empty($errors)) {
        try {
            // Handle image upload
            $imageUrl = handleImageUpload($_FILES['product_image'] ?? null);

            $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, category_id, min_stock, image_url, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
            if ($stmt->execute([$name, $description, $price, $stock, $category_id, $min_stock, $imageUrl])) {
                $response = [
                    'success' => true,
                    'message' => "Product '$name' added successfully!",
                    'type' => 'success'
                ];
            }
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => "Error: " . $e->getMessage(),
                'type' => 'error'
            ];
        }
    } else {
        $response = [
            'success' => false,
            'message' => implode(' ', $errors),
            'type' => 'error'
        ];
    }
}

// Update product with image upload
if (isset($_POST['update'])) {
    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
    $stock = filter_var($_POST['stock'], FILTER_VALIDATE_INT);
    $category_id = filter_var($_POST['category_id'], FILTER_VALIDATE_INT);
    $min_stock = filter_var($_POST['min_stock'] ?? 10, FILTER_VALIDATE_INT);

    if ($id && $name && $description && $price !== false && $stock !== false && $category_id) {
        try {
            // Get current image URL
            $currentImageStmt = $conn->prepare("SELECT image_url FROM products WHERE id = ?");
            $currentImageStmt->execute([$id]);
            $currentImageUrl = $currentImageStmt->fetchColumn();

            // Handle image upload
            $imageUrl = handleImageUpload($_FILES['product_image'] ?? null, $currentImageUrl);

            $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ?, category_id = ?, min_stock = ?, image_url = ?, updated_at = NOW() WHERE id = ?");
            if ($stmt->execute([$name, $description, $price, $stock, $category_id, $min_stock, $imageUrl, $id])) {
                $response = [
                    'success' => true,
                    'message' => "Product '$name' updated successfully!",
                    'type' => 'success'
                ];
            }
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => "Error: " . $e->getMessage(),
                'type' => 'error'
            ];
        }
    }
}

// Delete product
if (isset($_POST['delete']) && isset($_POST['id'])) {
    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    if ($id) {
        // Get image URL before deletion
        $imageStmt = $conn->prepare("SELECT image_url FROM products WHERE id = ?");
        $imageStmt->execute([$id]);
        $imageUrl = $imageStmt->fetchColumn();

        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        if ($stmt->execute([$id])) {
            // Delete associated image
            if ($imageUrl && !strpos($imageUrl, 'placeholder-')) {
                $imagePath = 'assets/images/products/' . $imageUrl;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $response = [
                'success' => true,
                'message' => "Product deleted successfully!",
                'type' => 'success'
            ];
        }
    }
}

// Fetch categories for dropdowns
$categoriesStmt = $conn->prepare("SELECT * FROM categories ORDER BY name");
$categoriesStmt->execute();
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch products with pagination and search
$search = trim($_GET['search'] ?? '');
$category_filter = filter_var($_GET['category'] ?? '', FILTER_VALIDATE_INT);
$page = max(1, filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT));
$per_page = 10;
$offset = ($page - 1) * $per_page;

$where_conditions = [];
$params = [];

if ($search) {
    $where_conditions[] = "(p.name LIKE ? OR p.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($category_filter) {
    $where_conditions[] = "p.category_id = ?";
    $params[] = $category_filter;
}

$where_sql = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

// Get total count
$countSql = "SELECT COUNT(*) FROM products p LEFT JOIN categories c ON p.category_id = c.id $where_sql";
$countStmt = $conn->prepare($countSql);
$countStmt->execute($params);
$total_products = $countStmt->fetchColumn();
$total_pages = ceil($total_products / $per_page);

// Get products
$sql = "SELECT p.*, c.name as category_name FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        $where_sql 
        ORDER BY p.id DESC 
        LIMIT $per_page OFFSET $offset";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SongSong Mart</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #cd2e3a;
            --secondary-color: #0047a0;
            --bg-primary: #f8f9fa;
            --bg-secondary: #ffffff;
            --text-primary: #2c3e50;
            --text-secondary: #34495e;
            --text-muted: #6c757d;
            --border-color: #dee2e6;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
        }

        .admin-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1rem 2rem;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }

        .admin-title {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .admin-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: #b02733;
            transform: translateY(-1px);
        }

        .btn-outline {
            background: transparent;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--bg-secondary);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow);
            border-left: 4px solid var(--primary-color);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        .controls-section {
            background: var(--bg-secondary);
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }

        .controls-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .controls-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .search-form {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: end;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.9rem;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(205, 46, 58, 0.1);
        }

        .products-section {
            background: var(--bg-secondary);
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .section-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .table-container {
            overflow-x: auto;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
        }

        .products-table th,
        .products-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .products-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid var(--border-color);
        }

        .no-image {
            width: 50px;
            height: 50px;
            background: #f8f9fa;
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }

        .btn-secondary {
            background: var(--secondary-color);
            color: white;
        }

        .btn-danger {
            background: var(--danger-color);
            color: white;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .modal-content {
            background: var(--bg-secondary);
            margin: 2rem auto;
            padding: 0;
            border-radius: 12px;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-muted);
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .image-upload-area {
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.3s ease;
            background: #fafbfc;
        }

        .image-upload-area:hover {
            border-color: var(--primary-color);
        }

        .image-preview {
            max-width: 200px;
            max-height: 200px;
            border-radius: 8px;
            margin: 1rem auto;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
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

        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            padding: 1.5rem;
        }

        .pagination a {
            padding: 0.5rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            text-decoration: none;
            color: var(--text-secondary);
        }

        .pagination a:hover {
            background: var(--primary-color);
            color: white;
        }

        .pagination .active {
            background: var(--primary-color);
            color: white;
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }

            .search-form {
                flex-direction: column;
            }

            .form-group {
                min-width: auto;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="admin-header">
        <div class="header-content">
            <h1 class="admin-title">
                <i class="fas fa-tachometer-alt"></i>
                SongSong Mart Admin Dashboard
            </h1>
            <div class="admin-actions">
                <button class="btn btn-primary" onclick="showAddModal()">
                    <i class="fas fa-plus"></i> Add Product
                </button>
                <a href="index.php" class="btn btn-outline">
                    <i class="fas fa-home"></i> View Site
                </a>
                <a href="logout.php" class="btn btn-outline">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </header>

    <div class="main-container">
        <!-- Alert Messages -->
        <?php if (!empty($response['message'])): ?>
            <div class="alert alert-<?php echo $response['type'] === 'success' ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($response['message']); ?>
            </div>
        <?php endif; ?>

        <!-- Dashboard Stats -->
        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-number"><?php echo $total_products; ?></div>
                        <div class="stat-label">Total Products</div>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-number"><?php echo count($categories); ?></div>
                        <div class="stat-label">Categories</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="controls-section">
            <div class="controls-header">
                <h2 class="controls-title">Product Management</h2>
            </div>
            <form method="GET" class="search-form">
                <div class="form-group">
                    <label class="form-label">Search Products</label>
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                        placeholder="Search by name or description..." class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-control">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" <?php echo $category_filter == $category['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>
        </div>

        <!-- Products Table -->
        <div class="products-section">
            <div class="section-header">
                <h2 class="section-title">Products (<?php echo $total_products; ?>)</h2>
            </div>

            <?php if (empty($products)): ?>
                <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
                    <i class="fas fa-box-open" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                    <h3>No products found</h3>
                    <p>Start by adding your first product.</p>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table class="products-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td>#<?php echo $product['id']; ?></td>
                                    <td>
                                        <?php if ($product['image_url']): ?>
                                            <img src="assets/images/products/<?php echo htmlspecialchars($product['image_url']); ?>"
                                                alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                                        <?php else: ?>
                                            <div class="no-image">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td><?php echo htmlspecialchars(substr($product['description'], 0, 50)) . '...'; ?></td>
                                    <td>₱<?php echo number_format($product['price'], 2); ?></td>
                                    <td><?php echo $product['stock']; ?></td>
                                    <td><?php echo htmlspecialchars($product['category_name'] ?? 'No Category'); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button type="button" class="btn btn-secondary btn-sm"
                                                onclick="showEditModal(<?php echo htmlspecialchars(json_encode($product)); ?>)">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="deleteProduct(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo $category_filter; ?>"
                                class="<?php echo $i === $page ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Add New Product</h2>
                <button class="close-btn" onclick="hideModal('addModal')">&times;</button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Product Image</label>
                        <div class="image-upload-area" onclick="document.getElementById('add_image').click()">
                            <i class="fas fa-cloud-upload-alt"
                                style="font-size: 2rem; color: var(--primary-color);"></i>
                            <p>Click to upload image</p>
                            <small>JPEG, PNG, GIF, WebP (Max 5MB)</small>
                        </div>
                        <input type="file" id="add_image" name="product_image" accept="image/*" style="display: none;"
                            onchange="previewImage(this, 'add')">
                        <img id="add_preview" class="image-preview" style="display: none;">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Price</label>
                            <input type="number" name="price" step="0.01" min="0" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Stock</label>
                            <input type="number" name="stock" min="0" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>">
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Min Stock Alert</label>
                            <input type="number" name="min_stock" min="0" value="10" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="hideModal('addModal')">Cancel</button>
                    <button type="submit" name="add" class="btn btn-primary">Add Product</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Edit Product</h2>
                <button class="close-btn" onclick="hideModal('editModal')">&times;</button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Product Name</label>
                        <input type="text" id="edit_name" name="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea id="edit_description" name="description" class="form-control" rows="3"
                            required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Current Image</label>
                        <div id="current_image_container" style="display: none;">
                            <img id="current_image" style="max-width: 100px; border-radius: 8px; margin-bottom: 1rem;">
                        </div>
                        <label class="form-label">New Image (optional)</label>
                        <div class="image-upload-area" onclick="document.getElementById('edit_image').click()">
                            <i class="fas fa-cloud-upload-alt"
                                style="font-size: 2rem; color: var(--primary-color);"></i>
                            <p>Click to upload new image</p>
                            <small>JPEG, PNG, GIF, WebP (Max 5MB)</small>
                        </div>
                        <input type="file" id="edit_image" name="product_image" accept="image/*" style="display: none;"
                            onchange="previewImage(this, 'edit')">
                        <img id="edit_preview" class="image-preview" style="display: none;">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Price</label>
                            <input type="number" id="edit_price" name="price" step="0.01" min="0" class="form-control"
                                required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Stock</label>
                            <input type="number" id="edit_stock" name="stock" min="0" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Category</label>
                            <select id="edit_category" name="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>">
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Min Stock Alert</label>
                            <input type="number" id="edit_min_stock" name="min_stock" min="0" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="hideModal('editModal')">Cancel</button>
                    <button type="submit" name="update" class="btn btn-primary">Update Product</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showAddModal() {
            document.getElementById('addModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function showEditModal(product) {
            document.getElementById('edit_id').value = product.id;
            document.getElementById('edit_name').value = product.name;
            document.getElementById('edit_description').value = product.description;
            document.getElementById('edit_price').value = product.price;
            document.getElementById('edit_stock').value = product.stock;
            document.getElementById('edit_category').value = product.category_id;
            document.getElementById('edit_min_stock').value = product.min_stock || 10;
            
            // Show current image if exists
            if (product.image_url) {
                document.getElementById('current_image').src = 'assets/images/products/' + product.image_url;
                document.getElementById('current_image_container').style.display = 'block';
            } else {
                document.getElementById('current_image_container').style.display = 'none';
            }
            
            document.getElementById('editModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function hideModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
            document.body.style.overflow = 'auto';
            
            // Clear image previews
            const preview = document.getElementById(modalId === 'addModal' ? 'add_preview' : 'edit_preview');
            const input = document.getElementById(modalId === 'addModal' ? 'add_image' : 'edit_image');
            if (preview) preview.style.display = 'none';
            if (input) input.value = '';
        }

        function previewImage(input, type) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById(type + '_preview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function deleteProduct(id, name) {
            if (confirm(`Are you sure you want to delete "${name}"? This action cannot be undone.`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="delete" value="1">
                    <input type="hidden" name="id" value="${id}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                hideModal(event.target.id);
            }
        }
    </script>
</body>
</html> 