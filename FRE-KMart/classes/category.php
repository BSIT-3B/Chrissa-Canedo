<?php
class Category
{
    private $conn;
    private $table_name = "categories";

    public $id;
    public $name;
    public $description;
    public $image_url;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get all categories
    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get category by ID
    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->image_url = $row['image_url'];
            return true;
        }
        return false;
    }

    // Get categories with product count
    public function getCategoriesWithCount()
    {
        $query = "SELECT c.*, COUNT(p.id) as product_count 
                  FROM " . $this->table_name . " c
                  LEFT JOIN products p ON c.id = p.category_id AND p.in_stock = 1
                  GROUP BY c.id
                  ORDER BY c.name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>