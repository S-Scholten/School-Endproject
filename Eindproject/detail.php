<?php
include_once 'connect.php';
session_start();

$id = $_GET['id'] ?? null;
if ($id === null) {
    header('Location: index.php');
    exit;
}

$sql = "SELECT * FROM personas WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$details = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Persona</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">
    <div class="login-wrap card">
        <div class="card-body">
            <h2 class="card-title">Persona details</h2>
        <?php if ($details && is_array($details)) : ?>
            <?php foreach ($details as $key => $value) : ?>
                <p><strong><?php echo htmlspecialchars($key); ?>:</strong> <?php echo htmlspecialchars((string)$value); ?></p>
            <?php endforeach; ?>

            <?php if (isset($_SESSION['loggedInUser'])) : ?>
                <p><a class="page-btn" href="edit.php?id=<?php echo urlencode($id); ?>">Edit persona</a></p>
            <?php endif; ?>

        <?php else : ?>
            <p>No persona found.</p>
        <?php endif; ?>

            <a class="back" href="index.php">Back to list</a>

        </div>
    </div>
</body>
</html>
