<?php
    require_once 'db.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['habit_name'])) {
        $stmt = $dbh->prepare("INSERT INTO habits (name) VALUES (?)");
        $stmt->execute([$_POST['habit_name']]);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_id'])) {
        $habit_id = (int) $_POST['toggle_id'];
        $today = date('Y-m-d');

        $check = $dbh->prepare("SELECT COUNT(*) FROM habit_logs WHERE habit_id = ? AND log_date = ?");
        $check->execute([$habit_id, $today]);
        $already_logged = $check->fetchColumn();

        if ($already_logged) {
            $stmt = $dbh->prepare("DELETE FROM habit_logs WHERE habit_id = ? AND log_date = ?");
            $stmt->execute([$habit_id, $today]);
        } else {
            $stmt = $dbh->prepare("INSERT INTO habit_logs (habit_id, log_date) VALUES (?, ?)");
            $stmt->execute([$habit_id, $today]);
        }
    }

    $stmt = $dbh->query("SELECT * FROM habits ORDER BY created_at DESC");
    $habits = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $today = date('Y-m-d');
    $logStmt = $dbh->prepare("SELECT habit_id FROM habit_logs WHERE log_date = ?");
    $logStmt->execute([$today]);
    $checked_ids = array_column($logStmt->fetchAll(PDO::FETCH_ASSOC), 'habit_id');

    $dates = [];
    for ($i = 6; $i >= 0; $i--) {
        $dates[] = date('Y-m-d', strtotime("-$i day"));
    }

    $habitStatuses = [];
    foreach ($habits as $habit) {
        $stmt = $dbh->prepare("SELECT log_date FROM habit_logs WHERE habit_id = ? AND log_date BETWEEN ? AND ?");
        $stmt->execute([$habit['id'], $dates[0], $dates[6]]);
        $logs = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $dailyStatus = [];
        foreach ($dates as $date) {
            $dailyStatus[] = in_array($date, $logs) ? 1 : 0;
        }

        $habitStatuses[] = [
            'name' => $habit['name'],
            'status' => $dailyStatus
        ];
    }
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8" />
        <title>習慣トラッカー</title>
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
        <h1>習慣トラッカー</h1>

        <form action="habits.php" method="POST" class="habit-form">
            <input type="text" name="habit_name" placeholder="例: 毎朝ストレッチ" required />
            <button type="submit">追加</button>
        </form>

        <ul class="habit-list">
            <?php foreach ($habits as $habit): ?>
                <li class="habit-item">
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="toggle_id" value="<?= $habit['id'] ?>">
                        <label>
                            <input type="checkbox" onchange="this.form.submit()" <?= in_array($habit['id'], $checked_ids) ? 'checked' : '' ?>>
                            <?= htmlspecialchars($habit['name']) ?>
                        </label>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="habit-progress">
            <h2>本日の達成</h2>
            <?php
            $total = count($habits);
            $done = count($checked_ids);
            ?>
            <progress value="<?= $done ?>" max="<?= $total ?>"></progress>
            <p><?= $done ?> / <?= $total ?> 達成</p>
        </div>

        <div class="habit-graph">
            <h2>過去7日間の達成状況</h2>
            <canvas id="habitChart"></canvas>
        </div>

        <script>
            window.chartData = <?= json_encode([
                'labels' => $dates,
                'datasets' => array_map(function($habit) {
                    return [
                        'label' => $habit['name'],
                        'data' => $habit['status'],
                        'fill' => false,
                        'borderColor' => '#' . substr(md5($habit['name']), 0, 6),
                        'tension' => 0.3,
                        'pointRadius' => 5,
                        'pointHoverRadius' => 7,
                    ];
                }, $habitStatuses)
            ]); ?>;

            window.chartConfig = {
                type: 'line',
                data: window.chartData,
                options: {
                    scales: {
                        y: {
                            min: 0,
                            max: 1,
                            ticks: {
                                stepSize: 0.2,
                                callback: value => (value * 100) + '%'
                            },
                            title: {
                                display: true,
                                text: '達成率(%)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: '日付'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: context => context.parsed.y ? '達成' : '未達成'
                            }
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                }
            };
        </script>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="notify.js"></script>
    </body>
</html>
