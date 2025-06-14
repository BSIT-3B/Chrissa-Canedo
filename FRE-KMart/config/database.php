<?php
// Database configuration
class Database
{
    private $host = "localhost";
    private $db_name = "songsong_mart";
    private $username = "root"; // Change this to your MySQL username
    private $password = ""; // Change this to your MySQL password
    public $conn;

    // Get database connection
    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}

// Site configuration
define('SITE_URL', 'http://localhost/KMart/');
define('SITE_NAME', 'SongSong Mart');
define('SITE_DESCRIPTION', 'Korean Convenience Store - Authentic Korean food products');
?>