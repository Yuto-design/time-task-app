<?php
    require_once 'db.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $today = date('Y-m-d');

        $checkStmt = $dbh->prepare("SELECT id FROM pomodoro_logs WHERE session_date = ?");
        $checkStmt->execute([$today]);
        $row = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $updateStmt = $dbh->prepare("UPDATE pomodoro_logs SET session_count = session_count + 1 WHERE id = ?");
            $updateStmt->execute([$row['id']]);
        } else {
            $insertStmt = $dbh->prepare("INSERT INTO pomodoro_logs (session_date, session_count) VALUES (?, 1)");
            $insertStmt->execute([$today]);
        }

        header("Location: pomodoro.php");
        exit;
    }

    $stmt = $dbh->query("SELECT session_date, session_count FROM pomodoro_logs ORDER BY session_date DESC LIMIT 7");
    $logs = array_reverse($stmt->fetchAll(PDO::FETCH_ASSOC));
    $labels = array_column($logs, 'session_date');
    $counts = array_column($logs, 'session_count');
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>Pomodoro タイマー</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>

        <h1>Pomodoro タイマー</h1>

        <form id="timeSettings" onsubmit="applyCustomTime(event)">
            <label>
                作業時間（分）:
                <input type="number" id="workMinutes" value="25" min="1" required>
            </label>
            <label>
                休憩時間（分）:
                <input type="number" id="breakMinutes" value="5" min="1" required>
            </label>
            <button type="submit">時間を設定</button>
        </form>

        <div class="timer-container">
            <div class="timer-display" id="timer">25:00</div>
            <button onclick="startTimer()" id="startBtn">開始</button>
            <button onclick="togglePause()" id="pauseBtn" style="display:none;">一時停止</button>
            <form id="logForm" method="POST" style="display: inline;">
                <button type="submit">セッション記録</button>
            </form>
        </div>

        <div class="graph-box">
            <h2>日別セッション数（直近7日）</h2>
            <canvas id="sessionChart"></canvas>
        </div>

        <a href="index.php" class="back">戻る</a>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const graphLabels = <?= json_encode($labels, JSON_UNESCAPED_UNICODE) ?>;
            const graphCounts = <?= json_encode($counts) ?>;
        </script>
        <script>
            renderSessionChart(sessionLabels, sessionCounts);
        </script>

        <script src="notify.js"></script>
    </body>
</html>
