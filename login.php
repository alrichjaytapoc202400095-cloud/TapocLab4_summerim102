<?php
include 'config.php';
include 'auth.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Invalid username or password.';
    } else {
        $stmt = $conn->prepare('SELECT id, username, password_hash, role FROM users WHERE username = ? LIMIT 1');
        if ($stmt) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'] ?? 'user';

                header('Location: index.php');
                exit;
            }
        }

        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { display:flex; align-items:center; justify-content:center; min-height:100vh; background:#f4f4f4; margin:0; }
        .auth-card { width:360px; background:#fff; padding:28px; border-radius:10px; box-shadow:0 12px 30px rgba(0,0,0,.12); }
        .auth-card h1 { margin:0 0 18px; font-size:24px; }
        .auth-card input { width:100%; padding:12px 14px; margin:8px 0 16px; border:1px solid #ccc; border-radius:6px; }
        .auth-card button { width:100%; padding:12px; border:none; border-radius:6px; background:#007BFF; color:#fff; font-size:16px; cursor:pointer; }
        .auth-card button:hover { background:#0056b3; }
        .auth-card .message { margin-bottom:16px; color:#c00; }
        .auth-card .alt { margin-top:14px; text-align:center; font-size:14px; }
        .auth-card .alt a { color:#007BFF; text-decoration:none; }
    </style>
</head>
<body>
<div class="auth-card">
    <h1>Login</h1>
    <?php if ($error): ?>
        <div class="message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" autocomplete="off">
        <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($username) ?>" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Sign In</button>
    </form>
    <div class="alt">
        Don't have an account? <a href="register.php">Register</a>
    </div>
</div>
</body>
</html>
