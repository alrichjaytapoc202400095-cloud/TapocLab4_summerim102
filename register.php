<?php
include 'config.php';
include 'auth.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$message = '';
$color = 'red';
$username = '';
$email = '';
$password = '';
$confirm = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($username === '' || $email === '' || $password === '' || $confirm === '') {
        $message = 'All fields are required.';
    } elseif (strlen($username) < 4) {
        $message = 'Username must be at least 4 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Invalid email address.';
    } elseif (strlen($password) < 6) {
        $message = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $message = 'Passwords do not match.';
    } else {
        $stmt = $conn->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
        if ($stmt) {
            $stmt->bind_param('ss', $username, $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $message = 'Username or email already exists.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt->close();

                $insert = $conn->prepare('INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)');
                if ($insert) {
                    $role = 'user';
                    $insert->bind_param('ssss', $username, $email, $hash, $role);
                    if ($insert->execute()) {
                        $message = 'Registration successful.';
                        $color = 'black';
                        $username = '';
                        $email = '';
                    } else {
                        $message = 'Registration failed. Please try again.';
                    }
                    $insert->close();
                } else {
                    $message = 'Registration failed. Please try again.';
                }
            }
        } else {
            $message = 'Registration failed. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { display:flex; align-items:center; justify-content:center; min-height:100vh; margin:0; background:#f4f4f4; }
        .auth-card { width:360px; background:#fff; padding:28px; border-radius:10px; box-shadow:0 12px 30px rgba(0,0,0,.12); }
        .auth-card h1 { margin:0 0 18px; font-size:24px; }
        .auth-card input { width:100%; padding:12px 14px; margin:8px 0 16px; border:1px solid #ccc; border-radius:6px; }
        .auth-card button { width:100%; padding:12px; border:none; border-radius:6px; background:#007BFF; color:#fff; font-size:16px; cursor:pointer; }
        .auth-card button:hover { background:#0056b3; }
        .auth-card .message { margin-bottom:16px; color:<?= htmlspecialchars($color) ?>; }
        .auth-card .alt { margin-top:14px; text-align:center; font-size:14px; }
        .auth-card .alt a { color:#007BFF; text-decoration:none; }
    </style>
</head>
<body>
<div class="auth-card">
    <h1>Create Account</h1>
    <?php if ($message !== ''): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="post" autocomplete="off">
        <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($username) ?>" required>
        <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit">Register</button>
    </form>
    <div class="alt">
        Already have an account? <a href="login.php">Login</a>
    </div>
</div>
</body>
</html>
