<?php include 'db.php'; ?>
<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['content'])) {
        $stmt = $dbh->prepare("INSERT INTO todos (content, due_date) VALUES (?, ?)");
        $stmt->execute([$_POST['content'], $_POST['due_date']]);
    }
    $stmt = $dbh->query("SELECT * FROM todos ORDER BY created_at DESC");
    $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h1>ToDo</h1>
<form method="POST">
    <input name="content" required>
    <input type="date" name="due_date">
    <button>追加</button>
</form>
<ul>
    <?php foreach ($todos as $todo): ?>
        <li><?= htmlspecialchars($todo['content']) ?> - <?= $todo['due_date'] ?> [<?= $todo['is_done'] ? '完了' : '未完了' ?>]</li>
    <?php endforeach; ?>
</ul>