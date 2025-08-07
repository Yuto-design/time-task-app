<?php
    include __DIR__ . '/../db.php';

    $id = $_GET['id'] ?? null;

    if (!$id) {
        header("Location: ../todo.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $stmt = $dbh->prepare("UPDATE todos SET content = ?, due_date = ?, status = ? WHERE id = ?");
        $stmt->execute([
            $_POST['content'],
            $_POST['due_date'],
            $_POST['status'],
            $id
        ]);
        header("Location: ../todo.php");
        exit;
    }

    $stmt = $dbh->prepare("SELECT * FROM todos WHERE id = ?");
    $stmt->execute([$id]);
    $todo = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8" />
        <title>ToDo 編集</title>
        <link rel="stylesheet" href="../style.css" />
    </head>
    <body>
        <h1>ToDo 編集</h1>
        <div class="todo-edit">
            <form method="POST">
                <input name="content" class="content" value="<?= htmlspecialchars($todo['content']) ?>" required>
                <input type="date" name="due_date" class="date" value="<?= $todo['due_date'] ?>">
                <select name="status" class="status">
                    <option value="0" <?= $todo['status'] == 0 ? 'selected' : '' ?>>未完了</option>
                    <option value="1" <?= $todo['status'] == 1 ? 'selected' : '' ?>>対応中</option>
                    <option value="2" <?= $todo['status'] == 2 ? 'selected' : '' ?>>完了</option>
                </select>
                <button>更新</button>
            </form>
        </div>
        <a href="../todo.php" class="back">戻る</a>
    </body>
</html>