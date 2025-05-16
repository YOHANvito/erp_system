<?php
// Turn off output buffering and error reporting to prevent headers already sent
ob_start();
session_start();
require_once 'database.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['file'])) {
    $original_name = $_GET['file'];
    
    // Get file information from database
    $stmt = $conn->prepare("SELECT id, file_path FROM files WHERE original_name = ? AND is_deleted = 0");
    $stmt->bind_param("s", $original_name);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // First try to delete the physical file
        $file_deleted = true;
        if (file_exists($row['file_path'])) {
            $file_deleted = unlink($row['file_path']);
        }
        
        if ($file_deleted) {
            // If file is deleted or doesn't exist, update database
            $updateStmt = $conn->prepare("UPDATE files SET is_deleted = 1 WHERE id = ?");
            $updateStmt->bind_param("i", $row['id']);
            
            if ($updateStmt->execute()) {
                $_SESSION['message'] = "File deleted successfully.";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Error updating database record.";
                $_SESSION['message_type'] = "error";
            }
            $updateStmt->close();
        } else {
            $_SESSION['message'] = "Error deleting file from server.";
            $_SESSION['message_type'] = "error";
        }
    } else {
        $_SESSION['message'] = "File not found in database.";
        $_SESSION['message_type'] = "error";
    }
    $stmt->close();
}

// Redirect back to dashboard
header("Location: dashboard.php");
exit();
?>
