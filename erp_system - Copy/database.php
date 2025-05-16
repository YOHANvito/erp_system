<?php
require_once 'config.php';

try {
    // First, connect without selecting a database
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Create database if it doesn't exist
    $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
    if (!$conn->query($sql)) {
        throw new Exception("Error creating database: " . $conn->error);
    }
    
    // Select the database
    if (!$conn->select_db(DB_NAME)) {
        throw new Exception("Error selecting database: " . $conn->error);
    }
    
    // Create files table if not exists
    $sql = "CREATE TABLE IF NOT EXISTS files (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        filename VARCHAR(255) NOT NULL,
        original_name VARCHAR(255) NOT NULL,
        file_size INT NOT NULL,
        file_type VARCHAR(100) NOT NULL,
        upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        uploaded_by VARCHAR(100) NOT NULL,
        file_path VARCHAR(255) NOT NULL,
        s3_key VARCHAR(255) DEFAULT NULL,
        is_deleted TINYINT(1) DEFAULT 0
    )";
    
    if (!$conn->query($sql)) {
        throw new Exception("Error creating table: " . $conn->error);
    }

} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}
?> 