<?php 

include_once 'connect.php';
session_start();

$q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 12;
$offset = ($page - 1) * $perPage;

if ($q === '') {
    $sql = "SELECT * FROM personas ORDER BY id ASC LIMIT ? OFFSET ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$perPage + 1, $offset]);
} else {
    $sql = "SELECT * FROM personas WHERE (name LIKE ? OR `query` LIKE ?) ORDER BY id ASC LIMIT ? OFFSET ?";
    $stmt = $pdo->prepare($sql);
    $like = '%' . $q . '%';
    $stmt->execute([$like, $like, $perPage + 1, $offset]);
}

$fetched = $stmt->fetchAll(PDO::FETCH_ASSOC);
$hasNext = count($fetched) > $perPage;
$rows = array_slice($fetched, 0, $perPage);

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="utf-8">
    <title>Personas List</title>
</head>
<body>
    <header class="topbar">
        <form method="get" action="index.php" style="display:inline-block;">
            <input id="search" name="q" class="search-input" type="search" placeholder="Search" aria-label="Search personas" value="<?php echo htmlspecialchars($q); ?>">
            <input type="hidden" name="page" value="1">
        </form>
        <a class="login-link" href="login.php">Adminpannel</a>
    </header>
    <h1>Personas</h1>

    <nav class="pagination-wrap" aria-label="Pagination">
        <ul class="pagination">
            <?php
            $base = 'index.php?' . ($q !== '' ? 'q=' . urlencode($q) . '&' : '');
            if ($page > 1) {
                echo '<li class="prev"><a href="' . htmlspecialchars($base . 'page=' . ($page - 1)) . '">‹ Prev</a></li>';
            }
            echo '<li class="current">Page ' . (int)$page . '</li>';

            if (!empty($hasNext)) {
                echo '<li class="next"><a href="' . htmlspecialchars($base . 'page=' . ($page + 1)) . '">Next ›</a></li>';
            }
            ?>
        </ul>
    </nav>

    <div class="table-wrap">
        <div class="grid">
            <?php if ($rows) : ?>
                <?php foreach ($rows as $row) : 
                    $id = $row['id'];
                    $name = htmlspecialchars($row['name']);
                    $arcana = htmlspecialchars($row['arcana']);
                    $level = htmlspecialchars((string)($row['level']));
                    $desc = htmlspecialchars($row['description']);
                    $img = $row['image'];
                    $dlc = array_key_exists('dlc', $row);
                    $query = htmlspecialchars($row['query']);
                    ?>
                    <a href="detail.php?id=<?=$id?>">
                        <article class="card">
                            <div class="card-media">
                                <?php if (!empty($img)) : ?>
                                    <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo $name; ?>">
                                <?php else : ?> 
                                    <div class="card-noimg">No image</div>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <h2 class="card-title"><?php echo $name; ?></h2>
                                <div class="card-meta">
                                    <span class="chip"><?php echo $arcana; ?></span>
                                    <span class="chip">Lvl <?php echo $level; ?></span>
                                    <span class="chip dlc">dlc: <?php echo htmlspecialchars((string)$dlc); ?></span>
                                </div>
                                <p class="card-desc"><?php echo $desc; ?></p>
                                <ul class="stat-list">
                                    <li><strong>STR:</strong> <?php echo htmlspecialchars((string)($row['strength'])); ?></li>
                                    <li><strong>MAG:</strong> <?php echo htmlspecialchars((string)($row['magic'])); ?></li>
                                    <li><strong>END:</strong> <?php echo htmlspecialchars((string)($row['endurance'])); ?></li>
                                    <li><strong>AGI:</strong> <?php echo htmlspecialchars((string)($row['agility'])); ?></li>
                                    <li><strong>LCK:</strong> <?php echo htmlspecialchars((string)($row['luck'])); ?></li>
                                </ul>

                                <div class="card-footer">
                                    <small class="muted">Query: <?php echo $query; ?></small>
                                </div>
                            </div>
                        </article>
                    </a>
                    
                <?php endforeach; ?>
            <?php else : ?>
                <p>No personas found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
