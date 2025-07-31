<?php
    include __DIR__ . '/../db.php';

    if (isset($_GET['id'])) {
        $stmt = $dbh->prepare("DELETE FROM todos WHERE id = ?");
        $stmt->execute([$_GET['id']]);
    }

    header("Location: ../todo.php");
    exit();
?>