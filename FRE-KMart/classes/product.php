<?php
class Product
{
    private $conn;
    private $table_name = "products";

    public $id;
    public $name;
    public $description;
    public $price;
    public $old_price;
    public $category_id;
    public $image_url;
    public $in_stock;
    public $is_featured;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get all products
    public function getAll()
    {
        $query = "SELECT p.*, c.name as category_name 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.in_stock = 1
                  ORDER BY p.name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get featured products
    public function getFeatured()
    {
        $query = "SELECT p.*, c.name as category_name 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.in_stock = 1 AND p.is_featured = 1
                  ORDER BY p.name
                  LIMIT 8";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get products by category
    public function getByCategory($category_id)
    {
        $query = "SELECT p.*, c.name as category_name 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.category_id = ? AND p.in_stock = 1
                  ORDER BY p.name";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $category_id);
        $stmt->execute();
        return $stmt;
    }

    // Get product by ID
    public function getById($id)
    {
        $query = "SELECT p.*, c.name as category_name 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->price = $row['price'];
            $this->old_price = $row['old_price'];
            $this->category_id = $row['category_id'];
            $this->image_url = $row['image_url'];
            $this->in_stock = $row['in_stock'];
            $this->is_featured = $row['is_featured'];
            return true;
        }
        return false;
    }

    // Search products
    public function search($search_term, $category_id = null)
    {
        $query = "SELECT p.*, c.name as category_name 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.in_stock = 1 AND (p.name LIKE ? OR p.description LIKE ?)";

        if ($category_id) {
            $query .= " AND p.category_id = ?";
        }

        $query .= " ORDER BY p.name";

        $stmt = $this->conn->prepare($query);

        $search_term = "%{$search_term}%";
        if ($category_id) {
            $stmt->bindParam(1, $search_term);
            $stmt->bindParam(2, $search_term);
            $stmt->bindParam(3, $category_id);
        } else {
            $stmt->bindParam(1, $search_term);
            $stmt->bindParam(2, $search_term);
        }

        $stmt->execute();
        return $stmt;
    }

    // Format price for display
    public function formatPrice($price)
    {
        return '₱' . number_format($price, 2);
    }

    // Get product count by category
    public function getCountByCategory($category_id)
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " 
                  WHERE category_id = ? AND in_stock = 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $category_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }

    // Get total product count
    public function getTotalCount()
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE in_stock = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }
}
?>