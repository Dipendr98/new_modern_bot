<?php
// dev_login.php
// Use this to log in locally without Telegram

require_once __DIR__ . '/app/Bootstrap.php';
require_once __DIR__ . '/app/Db.php';

$pdo = \App\Db::pdo();

// Find the user (default to admin)
$username = $_GET['user'] ?? 'admin';
$stmt = $pdo->prepare("SELECT id, username, status FROM users WHERE username = :u LIMIT 1");
$stmt->execute([':u' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // 1. Destroy old session completely (if any)
    $_SESSION = [];
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
    
    // 2. Start new clean session
    session_start();
    session_regenerate_id(true);

    // 3. Set new user coords
    $_SESSION['uid']   = (int)$user['id'];
    $_SESSION['uname'] = $user['username'];
    $_SESSION['last_login'] = time();

    // 4. Redirect
    header("Location: /app/dashboard");
    exit;
} else {
    echo "User '{$username}' not found in database. Please run setup_db.php again.";
}
