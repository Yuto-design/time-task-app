<?php include 'db.php'; ?>
<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['habit_name'])) {
        $stmt = $dbh->prepare("INSERT INTO habits (name, frequency) VALUES (?, ?)");
        $stmt->execute([$_POST['habit_name'], $_POST['frequency']]);
    }
    $stmt = $dbh->query("SELECT * FROM habits ORDER BY id DESC");
    $habits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>習慣トラッカー</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>習慣トラッカー</h1>

    <form action="habits.php" method="POST" class="habit-form">
        <input type="text" name="habit_name" placeholder="例: 毎朝ストレッチ" required>
        <select name="frequency">
            <option value="daily">毎日</option>
            <option value="weekly">週3回</option>
            <option value="custom">カスタム</option>
        </select>
        <button type="submit">追加</button>
    </form>

    <ul class="habit-list">
        <?php foreach ($habits as $habit): ?>
            <li class="habit-item">
                <label>
                    <input type="checkbox" <?= $habit['done_today'] ? 'checked' : '' ?>>
                    <span class="habit-name"><?= htmlspecialchars($habit['name']) ?></span>
                    <span class="habit-frequency"><?= htmlspecialchars($habit['frequency']) ?></span>
                </label>
                <div class="habit-actions">
                    <a href="edit_habit.php?id=<?= $habit['id'] ?>">編集</a>
                    <a href="delete_habit.php?id=<?= $habit['id'] ?>" onclick="return confirm('削除しますか？')">削除</a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="habit-progress">
        <h2>今週の進捗</h2>
        <progress value="4" max="7"></progress>
        <p>4 / 7 達成</p>
    </div>
</body>
</html>
