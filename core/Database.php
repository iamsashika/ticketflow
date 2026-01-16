<?php
/**
 * Database Connection Class
 * Implements Singleton pattern for database connection
 * Uses PDO for secure database operations
 */

class Database {
    // Singleton instance
    private static $instance = null;
    
    // PDO connection object
    private $connection;
    
    /**
     * Private constructor to prevent direct instantiation
     * Establishes PDO connection using config settings
     */
    private function __construct() {
        require_once __DIR__ . '/../config/database.php';
        
        try {
            // Create DSN (Data Source Name)
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            
            // PDO options for security and error handling
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // Throw exceptions on errors
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // Fetch associative arrays
                PDO::ATTR_EMULATE_PREPARES   => false,                   // Use real prepared statements
            ];
            
            // Create PDO instance
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
            
        } catch(PDOException $e) {
            // Log error and display user-friendly message
            die("Database connection failed: " . $e->getMessage());
        }
    }
    
    /**
     * Get singleton instance
     * @return Database Single instance of Database class
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Get PDO connection object
     * @return PDO Connection object
     */
    public function getConnection() {
        return $this->connection;
    }
    
    // Prevent cloning of the instance
    private function __clone() {}
    
    // Prevent unserialization of the instance
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

?>
