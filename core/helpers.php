<?php
/**
 * CORE HELPERS
 * ------------
 * These functions allow us to write simple code without using "$this->".
 * They handle everything: Database, Views, Redirects, etc.
 */

// We need the Database class to connect to MySQL
require_once __DIR__ . '/Database.php';


/* ------------------------------------------------------------
   DATABASE HELPERS (Talk to your MySQL/MariaDB)
   ------------------------------------------------------------ */

/**
 * Get the database connection
 * (Internal helper, you don't usually call this directly)
 */
function db_connect() {
    return Database::getInstance()->getConnection();
}

/**
 * Run a command (Insert, Update, Delete)
 * Returns TRUE if successful, FALSE if failed.
 */
function db_execute($sql, $params = []) {
    try {
        $db = db_connect();
        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    } catch (PDOException $e) {
        $errorMsg = "DB Execute Error: " . $e->getMessage();
        error_log($errorMsg);
        // Also write to debug file
        file_put_contents('debug_create_event.txt', date('Y-m-d H:i:s') . " - " . $errorMsg . "\n", FILE_APPEND);
        return false;
    }
}

/**
 * Execute and return number of affected rows
 * Useful for UPDATE/DELETE checks
 */
function db_execute_return_affected($sql, $params = []) {
    try {
        $db = db_connect();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    } catch (PDOException $e) {
        $errorMsg = "DB Execute Affected Error: " . $e->getMessage();
        error_log($errorMsg);
        return false;
    }
}

/**
 * Get multiple rows (Select * from ...)
 * Returns a list of rows (array of arrays).
 */
function db_query_all($sql, $params = []) {
    try {
        $db = db_connect();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(); // Fetch All Rows
    } catch (PDOException $e) {
        error_log("DB Query All Error: " . $e->getMessage());
        return [];
    }
}

/**
 * Get one row (Select ... WHERE id = 1)
 * Returns a single row (array).
 */
function db_query_one($sql, $params = []) {
    try {
        $db = db_connect();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(); // Fetch One Row
    } catch (PDOException $e) {
        error_log("DB Query One Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Get the ID of the new item we just added
 */
function db_last_id() {
    return db_connect()->lastInsertId();
}

/**
 * Transaction Helpers (Start, Save, Undo)
 */
function db_transaction_start() {
    return db_connect()->beginTransaction();
}

function db_transaction_commit() {
    return db_connect()->commit();
}

function db_transaction_rollback() {
    return db_connect()->rollBack();
}


/* ------------------------------------------------------------
   APPLICATION HELPERS (Views, Models, Redirects)
   ------------------------------------------------------------ */

/**
 * Load a View file (HTML page)
 * @param string $path Path to view file (e.g. 'home/index')
 * @param array $data Variables to pass to the view
 */
function view($path, $data = []) {
    // Extract data creates variables from the array keys
    // processed into $key = $value
    if (!empty($data)) {
        extract($data);
    }
    
    // Check if file exists
    $file = __DIR__ . '/../views/' . $path . '.php';
    if (file_exists($file)) {
        require_once $file;
    } else {
        die("View not found: " . $path);
    }
}

/**
 * Load a Model (Tool)
 * @param string $name Name of the model (e.g. 'User')
 * @return object The tool ready to use
 */
function model($name) {
    // 1. Where is the file?
    $file = __DIR__ . '/../models/' . $name . '.php';
    
    // 2. Load it
    require_once $file;
    
    // 3. Give back a new copy of it
    return new $name();
}

/**
 * Redirect to a URL
 * -----------------
 */
function redirect($url) {
    // Assuming $base is defined globally or passed in, otherwise this will cause an error.
    // For now, keeping the original logic as requested.
    // If $base is not defined, you might want to define it or remove this if condition.
    // Hardcoded base path for simplicity in this procedural version
    $base = '/Event';
    
    if (strpos($url, $base) !== 0) {
        $url = $base . $url;
    }
    
    header("Location: " . $url);
    exit;
}

/**
 * Clean user input (Security)
 */
function sanitize($string) {
    return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
}

/**
 * Check if user is logged in
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Check if user is admin
 */
function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Force login (Redirect if not logged in)
 */
function require_login() {
    if (!is_logged_in()) {
        redirect('/login');
    }
}

/**
 * Force admin (Redirect if not admin)
 */
function require_admin() {
    if (!is_admin()) {
        redirect('/');
    }
}
