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
    $dailyRates = array_fill_keys($dates, 0);
    foreach ($habits as $habit) {
        $stmt = $dbh->prepare("SELECT log_date FROM habit_logs WHERE habit_id = ? AND log_date BETWEEN ? AND ?");
        $stmt->execute([$habit['id'], $dates[0], $dates[6]]);
        $logs = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $dailyStatus = [];
        foreach ($dates as $date) {
            $hit = in_array($date, $logs) ? 1 : 0;
            $dailyStatus[] = $hit;
            $dailyRates[$date] += $hit;
        }

        $habitStatuses[] = [
            'name' => $habit['name'],
            'status' => $dailyStatus
        ];
    }

    $habitRates = [];
    foreach ($habitStatuses as $habit) {
        $totalDays = count($habit['status']);
        $completedDays = array_sum($habit['status']);
        $rate = round(($completedDays / $totalDays) * 100, 1);
        $habitRates[] = [
            'name' => $habit['name'],
            'rate' => $rate
        ];
    }

    $averageRate = count($habitRates) > 0 ? round(array_sum(array_column($habitRates, 'rate')) / count($habitRates), 1) : 0;
    $dailyAverageRates = [];
    $totalHabits = count($habits);
    foreach ($dates as $date) {
        $dailyAverageRates[] = $totalHabits > 0 ? round(($dailyRates[$date] / $totalHabits) * 100, 1) : 0;
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

        <div class="habit-summary-chart">
            <h2>全体の達成率</h2>
            <canvas id="summaryChart"></canvas>
        </div>

        <div class="habit-daily-chart">
            <h2>日別平均達成率</h2>
            <canvas id="dailyChart"></canvas>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctxSummary = document.getElementById('summaryChart').getContext('2d');
            new Chart(ctxSummary, {
                type: 'doughnut',
                data: {
                    labels: ['達成率', '未達成率'],
                    datasets: [{
                        data: [<?= $averageRate ?>, <?= 100 - $averageRate ?>],
                        backgroundColor: ['#2ecc71', '#ecf0f1'],
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: ctx => `${ctx.label}: ${ctx.raw}%`
                            }
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });

            const ctxDaily = document.getElementById('dailyChart').getContext('2d');
            new Chart(ctxDaily, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($dates) ?>,
                    datasets: [{
                        label: '日別平均達成率',
                        data: <?= json_encode($dailyAverageRates) ?>,
                        backgroundColor: '#9b59b6'
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: value => value + '%'
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
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => `${ctx.raw}% 達成`
                            }
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });
        </script>

        <a href="./index.php" class="back">戻る</a>
    </body>
</html>
