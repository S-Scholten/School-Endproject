<?php
include_once 'connect.php';
session_start();

// restrict access: only logged-in users may access this page
if (!isset($_SESSION['loggedInUser'])) {
    header('Location: login.php');
    exit;
}

$persona = null;
$message = '';

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM personas WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $persona = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$persona) {
        $message = "No persona found with ID {$_GET['id']}.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $fields = ['name','arcana','level','description','image','strength','magic','endurance','agility','luck','weak','resists','reflects','absorbs','nullifies','dlc','query'];
    $updates = [];
    $params = [':id' => $id];

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $updates[] = "$field = :$field";
            $params[":$field"] = $_POST[$field];
        }
    }

    if (!empty($updates)) {
        $sql = "UPDATE personas SET " . implode(', ', $updates) . " WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $message = "Persona updated!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Persona</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">
    <div class="login-wrap card">
        <div class="card-body">
            <h2 class="card-title">Edit Persona</h2>

            <?php if (!empty($message)) : ?>
                <div class="form-message"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <?php if (!is_array($persona)) : ?>
                <form method="get" action="edit.php">
                    <table class="form-table">
                        <tr>
                            <td><label for="id">Enter Persona ID:</label></td>
                            <td><input class="form-input" type="text" name="id" id="id" required></td>
                        </tr>
                    </table>
                    <div class="form-actions">
                        <button type="submit">Load Persona</button>
                        <a class="back" href="admin.php">Back to admin</a>
                    </div>
                </form>
            <?php endif; ?>

            <?php if (is_array($persona)) : ?>
                <form method="post" action="edit.php">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($persona['id']); ?>">
                    <table class="form-table">
                        <?php foreach ($persona as $field => $value) :
                            if ($field === 'id') {
                                continue;
                            } ?>
                            <tr>
                                <td><label for="<?php echo htmlspecialchars($field); ?>"><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $field))); ?>:</label></td>
                                <td><input class="form-input" type="text" name="<?php echo htmlspecialchars($field); ?>" id="<?php echo htmlspecialchars($field); ?>" value="<?php echo htmlspecialchars($value); ?>"></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                    <div class="form-actions">
                        <button type="submit" name="update">Update Persona</button>
                        <a class="back" href="admin.php">Back to admin</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
