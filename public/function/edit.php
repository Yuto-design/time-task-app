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

<h1>ToDo 編集</h1>
<form method="POST">
    <input name="content" value="<?= htmlspecialchars($todo['content']) ?>" required>
    <input type="date" name="due_date" value="<?= $todo['due_date'] ?>">
    <select name="status">
        <option value="0" <?= $todo['status'] == 0 ? 'selected' : '' ?>>未完了</option>
        <option value="1" <?= $todo['status'] == 1 ? 'selected' : '' ?>>対応中</option>
        <option value="2" <?= $todo['status'] == 2 ? 'selected' : '' ?>>完了</option>
    </select>
    <button>更新</button>
</form>
<a href="../todo.php">戻る</a>
