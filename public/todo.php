<?php
    include 'db.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['content'])) {
        $stmt = $dbh->prepare("INSERT INTO todos (content, due_date, status) VALUES (?, ?, ?)");
        $stmt->execute([
            $_POST['content'],
            $_POST['due_date'] ?: null,
            $_POST['status'] ?? 0
        ]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    $stmt = $dbh->query("SELECT * FROM todos ORDER BY due_date ASC, created_at DESC");
    $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    function statusLabel($status) {
        return [
            0 => '未完了',
            1 => '対応中',
            2 => '完了',
        ][$status] ?? '不明';
    }
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8" />
        <title>ToDoリスト</title>
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
        <h1>ToDoリスト</h1>

        <div class="todo-add">
            <form method="POST">
                <input name="content" class="content"  placeholder="タスク内容"/>
                <input type="date" name="due_date" class="date" />
                <select name="status" class="status">
                    <option value="0">未完了</option>
                    <option value="1">対応中</option>
                    <option value="2">完了</option>
                </select>
                <button>追加</button>
            </form>
        </div>

        <div class="todo-list">
            <ul>
                <?php foreach ($todos as $todo): ?>
                    <?php $status = $todo['status'] ?? 0; ?>
                    <li>
                        <div class="task-info">
                            <div class="task-content"><?= htmlspecialchars($todo['content']) ?></div>
                            <div class="task-due"><?= htmlspecialchars($todo['due_date']) ?: '期限なし' ?></div>
                        </div>
                        <div class="status status-<?= $status ?>"><?= statusLabel($status) ?></div>
                        <div class="actions">
                            <div class="edit-button"><a href="./function/edit.php?id=<?= $todo['id'] ?>">編集</a></div>
                            <div class="delete-button"><a href="./function/delete.php?id=<?= $todo['id'] ?>" onclick="return confirm('削除しますか？')">削除</a></div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <a href="./index.php" class="back">戻る</a>
    </body>
</html>
