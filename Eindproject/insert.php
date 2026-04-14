<?php
include_once 'connect.php';

$dataString = [
    'name','arcana','description','image','weak','resists','reflects','absorbs','nullifies','dlc','query'
];
$dataNumber = [
    'id','level','strength','magic','endurance','agility','luck'
];

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = array_merge($dataString, $dataNumber);
    $insertFields = [];
    $placeholders = [];
    $params = [];

    foreach ($fields as $field) {
        if (isset($_POST[$field]) && $_POST[$field] !== '') {
            $insertFields[] = $field;
            $placeholders[] = ":$field";
            $params[":$field"] = $_POST[$field];
        }
    }

    if (!empty($insertFields)) {
        $sql = "INSERT INTO personas (" . implode(',', $insertFields) . ") VALUES (" . implode(',', $placeholders) . ")";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute($params);
            $message = "Persona added successfully!";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000 && strpos($e->getMessage(), '1062') !== false) {
                $message = "The ID <strong>{$_POST['id']}</strong> is already in use.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body class="login-page">
    <div class="login-wrap card">
        <div class="card-body">
            <h2 class="card-title">Add Persona</h2>
            <?php if (!empty($message)) : ?>
                <div class="form-message"><?php echo $message; ?></div>
            <?php endif; ?>
            <form method="post" action="insert.php">
                <table class="form-table">
                          <?php foreach ($dataNumber as $insert) : ?>
                        <tr>
                            <td><label for="<?php echo htmlspecialchars($insert); ?>"><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $insert))); ?>:</label></td>
                            <td><input class="form-input" type="number" name="<?php echo htmlspecialchars($insert); ?>" id="<?php echo htmlspecialchars($insert); ?>"></td>
                        </tr>
                          <?php endforeach; ?>
                    <?php foreach ($dataString as $insert) : ?>
                        <tr>
                            <td><label for="<?php echo htmlspecialchars($insert); ?>"><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $insert))); ?>:</label></td>
                            <td><input class="form-input" type="text" name="<?php echo htmlspecialchars($insert); ?>" id="<?php echo htmlspecialchars($insert); ?>"></td>
                        </tr>
                    <?php endforeach; ?>    
                </table>
                <div class="form-actions">
                    <button type="submit">Add Persona</button>
                    <a class="back" href="admin.php">Back to admin</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
