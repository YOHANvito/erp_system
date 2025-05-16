<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Get files from database
$sql = "SELECT * FROM files WHERE is_deleted = 0 ORDER BY upload_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CloudIT Dashboard</title>
    <style>
        :root {
            --dark-bg: #1a1a1a;
            --darker-bg: #141414;
            --card-bg: #242424;
            --text-primary: #ffffff;
            --text-secondary: #b3b3b3;
            --accent-color: #3699ff;
            --accent-hover: #1a7ee3;
            --border-color: #2d2d2d;
            --danger-color: #f64e60;
            --success-bg: #1c3238;
            --success-color: #0bb783;
            --error-bg: #3a2434;
            --error-color: #f64e60;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background: var(--dark-bg);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background-color: var(--darker-bg);
            color: var(--text-primary);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid var(--border-color);
        }

        header img {
            width: 60px;
            height: auto;
            margin-right: 15px;
        }

        header h1 {
            display: inline;
            font-size: 1.4rem;
            margin: 0;
        }

        header .logo-container {
            display: flex;
            align-items: center;
        }

        header .logout-link {
            color: var(--text-secondary);
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 4px;
            border: 1px solid var(--border-color);
            transition: all 0.2s;
        }

        header .logout-link:hover {
            background: var(--card-bg);
            color: var(--text-primary);
        }

        main {
            padding: 1.5rem;
            flex: 1;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
        }

        section {
            margin-bottom: 1.5rem;
        }

        .welcome-section {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border: 1px solid var(--border-color);
        }

        .welcome-section h2 {
            margin: 0;
            font-size: 1.2rem;
        }

        .welcome-section p {
            color: var(--text-secondary);
            margin: 0.5rem 0 0;
        }

        .file-vault {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        h3 {
            margin: 0 0 1rem;
            font-size: 1.1rem;
        }

        form {
            display: flex;
            gap: 10px;
            margin-bottom: 1.5rem;
            align-items: center;
        }

        form input[type="file"] {
            flex: 1;
            padding: 8px;
            border-radius: 4px;
            background: var(--darker-bg);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        form button {
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            white-space: nowrap;
        }

        form button:hover {
            background: var(--accent-hover);
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 400px;
            overflow-y: auto;
        }

        ul::-webkit-scrollbar {
            width: 8px;
        }

        ul::-webkit-scrollbar-track {
            background: var(--darker-bg);
        }

        ul::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
        }

        ul li {
            background: var(--darker-bg);
            margin: 0.25rem 0;
            padding: 12px;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid var(--border-color);
        }

        ul li:hover {
            background: #2a2a2a;
        }

        .file-meta {
            color: var(--text-secondary);
            font-size: 0.75rem;
            margin-top: 4px;
        }

        .file-actions {
            display: flex;
            gap: 8px;
        }

        .file-actions a {
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .file-actions a.view {
            background: var(--accent-color);
            color: white;
        }

        .file-actions a.view:hover {
            background: var(--accent-hover);
        }

        .file-actions a.delete {
            background: var(--danger-color);
            color: white;
        }

        .file-actions a.delete:hover {
            background: #e1314a;
        }

        .message {
            padding: 12px;
            margin-bottom: 1rem;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .message.success {
            background: var(--success-bg);
            color: var(--success-color);
            border: 1px solid var(--success-color);
        }

        .message.error {
            background: var(--error-bg);
            color: var(--error-color);
            border: 1px solid var(--error-color);
        }

        .file-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .file-meta {
            display: flex;
            gap: 15px;
            font-size: 0.8rem;
            color: #666;
        }

        .file-actions {
            display: flex;
            gap: 10px;
        }

        .file-actions a {
            padding: 4px 8px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .file-actions a.view {
            background: #e3f2fd;
            color: #1976d2;
        }

        .file-actions a.delete {
            background: #fde7e7;
            color: #d32f2f;
        }

        footer {
            text-align: center;
            padding: 1rem;
            background: var(--darker-bg);
            color: var(--text-secondary);
            border-top: 1px solid var(--border-color);
        }

        @media (max-width: 768px) {
            main {
                padding: 1rem;
            }
            form {
                flex-direction: column;
                align-items: stretch;
            }
            form input[type="file"] {
                margin-bottom: 0.5rem;
            }
        }
    </style>
</head>
<body>
<header>
    <div class="logo-container">
        <img src="logo-transparent.png" alt="CloudIT" width="60">
        <h1>CloudIT ERP System</h1>
    </div>
    <a href="logout.php" class="logout-link">Logout</a>
</header>

<main>
    <section class="welcome-section">
        <h2>👋 Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h2>
        <p>This cloud-based ERP prototype improves collaboration between IT and Marketing.</p>
    </section>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="message <?php echo $_SESSION['message_type']; ?>">
            <?php 
                echo $_SESSION['message'];
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            ?>
        </div>
    <?php endif; ?>

    <section class="file-vault">
        <h3>📁 CloudIT File Vault</h3>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <input type="file" name="file" required>
            <button type="submit" name="upload">Upload File</button>
        </form>
        <ul>
            <?php if ($result->num_rows > 0): ?>
                <?php while($file = $result->fetch_assoc()): ?>
                    <li>
                        <div class="file-info">
                            <span>📄 <?php echo htmlspecialchars($file['original_name']); ?></span>
                            <div class="file-meta">
                                <span>Size: <?php echo round($file['file_size']/1024, 2); ?> KB</span>
                                <span>Type: <?php echo strtoupper($file['file_type']); ?></span>
                                <span>Uploaded: <?php echo date('Y-m-d H:i', strtotime($file['upload_date'])); ?></span>
                                <span>By: <?php echo htmlspecialchars($file['uploaded_by']); ?></span>
                            </div>
                        </div>
                        <div class="file-actions">
                            <a href="<?php echo $file['file_path']; ?>" target="_blank" class="view">View</a>
                            <a href="delete.php?file=<?php echo urlencode($file['original_name']); ?>" class="delete" onclick="return confirm('Are you sure you want to delete this file?')">Delete</a>
                        </div>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <li>No files uploaded yet.</li>
            <?php endif; ?>
        </ul>
    </section>
</main>

<footer>
    <p>&copy; 2025 CloudIT | ERP Collaboration System for IT & Marketing Departments</p>
</footer>
</body>
</html>
