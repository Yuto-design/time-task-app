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
        <style>
            .timer-container {
                text-align: center;
                margin: 40px auto;
            }

            .timer-display {
                font-size: 48px;
                font-weight: bold;
                margin: 20px 0;
            }

            .graph-box {
                max-width: 700px;
                margin: 50px auto;
                background: #f9f9f9;
                padding: 20px;
                border-radius: 12px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            }

            canvas {
                width: 100% !important;
                height: 400px !important;
            }

            button {
                padding: 10px 20px;
                font-size: 16px;
                margin: 10px;
            }

            .back {
                display: inline-block;
                margin-top: 40px;
                background-color: #ddd;
                padding: 8px 16px;
                border-radius: 8px;
                text-decoration: none;
            }
        </style>
    </head>
    <body>

        <h1>Pomodoro タイマー</h1>

        <div class="timer-container">
            <div class="timer-display" id="timer">25:00</div>
            <button onclick="startTimer()" id="startBtn">開始</button>
            <button onclick="togglePause()" id="pauseBtn" style="display:none;">一時停止</button>
            <form id="logForm" method="POST" style="display: inline;">
                <button type="submit">セッション記録</button>
            </form>
            <div id="phase-label">作業フェーズ</div>
        </div>

        <div class="graph-box">
            <h2>日別セッション数（直近7日）</h2>
            <canvas id="sessionChart"></canvas>
        </div>

        <a href="index.php" class="back">戻る</a>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const sessionLabels = <?= json_encode($labels) ?>;
            const sessionCounts = <?= json_encode($counts) ?>;
        </script>
        <script src="notify.js"></script>
        <script>
            renderSessionChart(sessionLabels, sessionCounts);
        </script>

    </body>
</html>
