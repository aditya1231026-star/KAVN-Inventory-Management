<?php
session_start();
require 'db.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    if (!empty($user) && !empty($pass)) {
        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $user, $hashed_password);
        
        if ($stmt->execute()) {
            $success = "Account created. <a href='login.php'>Login here</a>";
        } else {
            $error = "Username already exists.";
        }
        $stmt->close();
    } else {
        $error = "Please fill all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>KAVN | Sign Up</title>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: #fafafa; height: 100vh; display: flex; justify-content: center; align-items: center; color: #18181b; }
        .login-box { background: #ffffff; padding: 40px; border-radius: 12px; width: 380px; border: 1px solid #e4e4e7; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); text-align: center; }
        .login-box h2 { margin-bottom: 8px; font-weight: 700; font-size: 24px; display: flex; align-items: center; justify-content: center; gap: 8px; letter-spacing: -0.5px; }
        .input-group { position: relative; margin-bottom: 16px; }
        .input-group input { width: 100%; padding: 12px 16px; border: 1px solid #e4e4e7; border-radius: 8px; outline: none; font-size: 14px; background: #fafafa; transition: 0.2s; }
        .input-group input:focus { border-color: #18181b; background: #fff; }
        button { width: 100%; padding: 12px; background: #18181b; color: white; border: none; border-radius: 8px; font-weight: 500; font-size: 14px; cursor: pointer; transition: 0.2s; margin-top: 8px; }
        button:hover { background: #27272a; }
        .msg { margin-bottom: 16px; font-size: 13px; font-weight: 500; }
        .err { color: #ef4444; }
        .suc { color: #18181b; }
        a { color: #18181b; font-weight: 600; text-decoration: underline; text-underline-offset: 2px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2><i class="ph-fill ph-hexagon"></i> Join KAVN</h2>
        <p style="color: #71717a; margin-bottom: 24px; font-size: 14px;">Setup your admin account</p>
        
        <?php if($error) echo "<p class='msg err'>$error</p>"; ?>
        <?php if($success) echo "<p class='msg suc'>$success</p>"; ?>

        <form method="POST" action="signup.php">
            <div class="input-group">
                <input type="text" name="username" placeholder="Choose a Username" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Create a Password" required>
            </div>
            <button type="submit">Register Account</button>
        </form>
        <p style="margin-top:24px; font-size:13px; color: #71717a;">Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>