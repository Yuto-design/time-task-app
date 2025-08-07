<!DOCTYPE html>
<html>
    <head>
        <title>Time Tracker</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
        <h1>Time Tracker App</h1>
        <nav>
            <a href="todo.php">ToDo</a>
            <a href="habits.php">Habits</a>
            <a href="pomodoro.php">Pomodoro</a>
        </nav>

        <div class="intro">
            <p>このアプリは、毎日のタスク管理・習慣の定着・集中力向上を支援するためのツールです。</p>
        </div>

        <div class="summary-cards">
            <div class="card">
                <h2>ToDo</h2>
                <p>やるべきことを管理し、期限と状態を記録できます。</p>
            </div>
            <div class="card">
                <h2>Habits</h2>
                <p>良い習慣を作り、毎日記録して定着させましょう。</p>
            </div>
            <div class="card">
                <h2>Pomodoro</h2>
                <p>集中と休憩を繰り返し、効率的な作業をサポートします。</p>
            </div>
        </div>

        <div class="today">
            <?php echo "本日の日付: " . date("Y年m月d日 (D)"); ?>
        </div>

        <footer>
            &copy; <?= date("Y") ?> Time Tracker App. All rights reserved.
        </footer>
    </body>
</html>
