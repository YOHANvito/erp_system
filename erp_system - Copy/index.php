<?php
session_start();
if (isset($_POST['login'])) {
    if ($_POST['username'] === 'admin' && $_POST['password'] === 'admin') {
        $_SESSION['user'] = 'admin';
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid credentials.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>CloudIT - Login</title>
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
            --input-bg: #1e1e1e;
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
        
        .login-box {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            text-align: center;
            width: 320px;
        }
        
        .login-box img {
            margin-bottom: 1.5rem;
            max-width: 120px;
            height: auto;
        }
        
        .login-box h2 {
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }
        
        .error {
            color: #f64e60;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            background: rgba(246, 78, 96, 0.1);
            padding: 8px;
            border-radius: 4px;
            border: 1px solid rgba(246, 78, 96, 0.2);
        }
        
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 1rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            box-sizing: border-box;
            background: var(--input-bg);
            color: var(--text-primary);
            font-size: 0.9rem;
        }
        
        input:focus {
            outline: none;
            border-color: var(--accent-color);
        }
        
        button {
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
            transition: background-color 0.2s;
        }
        
        button:hover {
            background: var(--accent-hover);
        }
    </style>
</head>
<body>
    <div class="login-box">
        <img src="logo-transparent.png" alt="CloudIT Logo" width="120">
        <h2>Welcome Back</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Sign In</button>
        </form>
    </div>
</body>
</html>
