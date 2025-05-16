<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $uploadDir = "uploads/";
    
    // Create uploads directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $file = $_FILES["file"];
    $fileName = basename($file["name"]);
    $targetFilePath = $uploadDir . time() . '_' . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    
    // Allow certain file formats
    $allowTypes = array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt');
    
    if (in_array($fileType, $allowTypes)) {
        if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
            // Insert file information into database
            $stmt = $conn->prepare("INSERT INTO files (filename, original_name, file_size, file_type, uploaded_by, file_path) VALUES (?, ?, ?, ?, ?, ?)");
            $storedFileName = time() . '_' . $fileName;
            $fileSize = $file["size"];
            $uploadedBy = $_SESSION['user'];
            
            $stmt->bind_param("ssssss", $storedFileName, $fileName, $fileSize, $fileType, $uploadedBy, $targetFilePath);
            
            if ($stmt->execute()) {
                $_SESSION['message'] = "The file " . $fileName . " has been uploaded successfully.";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Failed to save file information to database.";
                $_SESSION['message_type'] = "error";
            }
            $stmt->close();
        } else {
            $_SESSION['message'] = "Sorry, there was an error uploading your file.";
            $_SESSION['message_type'] = "error";
        }
    } else {
        $_SESSION['message'] = "Sorry, only JPG, JPEG, PNG, GIF, PDF, DOC, DOCX, XLS, XLSX & TXT files are allowed.";
        $_SESSION['message_type'] = "error";
    }
    
    header("Location: dashboard.php");
    exit();
}
?>
