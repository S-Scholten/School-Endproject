<?php
include_once 'connect.php';
session_start();
if (isset($_SESSION['loggedInUser'])) {
    header('Location: admin.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT * FROM gebruikers WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['loggedInUser'] = $user['id'];
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Ongeldige gebruikersnaam of wachtwoord.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">
    <div class="login-wrap card">
        <?php if (!empty($error)) : ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <h2 class="card-title">Login</h2>
        <div class="card-body">
            <form method="post" action="login.php">
                <label for="username">Username</label>
                <input id="username" name="username" type="text" autocomplete="username">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" autocomplete="current-password">
                <button type="submit">Sign in</button>
            </form>
            <a class="back" href="index.php">← Back to personas</a>
        </div>
    </div>
</body>
</html>
