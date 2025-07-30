<?php include 'db.php'; ?>
<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['name'])) {
        $stmt = $dbh->prepare("INSERT INTO habits (name) VALUES (?)");
        $stmt->execute([$_POST['name']]);
    }
    $stmt = $dbh->query("SELECT * FROM habits");
    $habits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h1>Habits</h1>
<form method="POST">
    <input name="name" required>
    <button>追加</button>
</form>
<ul>
    <?php foreach ($habits as $habit): ?>
        <li><?= htmlspecialchars($habit['name']) ?></li>
    <?php endforeach; ?>
</ul>