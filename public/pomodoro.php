<?php include 'db.php'; ?>
<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $stmt = $dbh->prepare("INSERT INTO pomodoro_logs (session_date) VALUES (CURDATE())");
        $stmt->execute();
    }
    $stmt = $dbh->query("SELECT session_date, COUNT(*) as count FROM pomodoro_logs GROUP BY session_date");
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h1>Pomodoro</h1>
<form method="POST">
    <button>1セッション記録</button>
</form>
<ul>
    <?php foreach ($logs as $log): ?>
        <li><?= $log['session_date'] ?>: <?= $log['count'] ?>セッション</li>
    <?php endforeach; ?>
</ul>
<script src="notify.js"></script>