<?php
session_start();

// Clear all session variables
$_SESSION = array();

// Destroy the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Destroy the session
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logged Out - CloudIT ERP</title>
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
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: var(--dark-bg);
            color: var(--text-primary);
        }

        .logout-container {
            background: var(--card-bg);
            padding: 2.5rem;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        .logo {
            margin-bottom: 2rem;
        }

        .logo img {
            max-width: 120px;
            height: auto;
        }

        h1 {
            color: var(--text-primary);
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        p {
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        .login-link {
            display: inline-block;
            background: var(--accent-color);
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 4px;
            transition: background-color 0.2s;
            font-weight: 500;
        }

        .login-link:hover {
            background: var(--accent-hover);
        }

        .footer-text {
            margin-top: 1.5rem;
            font-size: 0.8rem;
            color: var(--text-secondary);
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <div class="logo">
            <img src="logo-transparent.png" alt="CloudIT" width="120">
        </div>
        <h1>Successfully Logged Out</h1>
        <p>Thank you for using CloudIT ERP System. You have been safely logged out of your account.</p>
        <a href="index.php" class="login-link">Log In Again</a>
        <p class="footer-text">For security reasons, please close your browser window.</p>
    </div>
</body>
</html> 