# time-task-app

## 概要
**Time Tracker App** は、毎日の **タスク管理 (ToDo)**・**習慣形成 (Habits)**・**集中作業 (Pomodoro)** をサポートするアプリケーションです。  
PHP + MySQL (またはSQLite) を用いて構築されており、タスクや習慣の記録・進捗の可視化を行います。  
学生や社会人が効率的に時間を使い、継続的な成長を実現することを目的としています。

---

## 主な機能
### 1. ToDoリスト
- タスクの追加 / 編集 / 削除
- 締切日の設定
- ステータス管理（未完了・対応中・完了）
- 締切順で自動並び替え

### 2. 習慣トラッカー
- 習慣の追加 / 削除
- 毎日のチェック機能（完了/未完了）
- 週間達成率の集計
- **グラフ表示**
  - 本日の達成率（円グラフ）
  - 日別平均達成率（折れ線グラフ）

### 3. Pomodoro タイマー
- 作業時間 / 休憩時間を自由に設定可能
- カウントダウン表示 & 一時停止
- セッション数の記録
- **直近7日間のセッション数グラフ**

---

## 技術スタック
- **フロントエンド**
  - HTML / CSS / JavaScript
  - [Chart.js](https://www.chartjs.org/) を使用してグラフ描画
- **バックエンド**
  - PHP
  - PDO を用いたデータベース操作
- **データベース**
  - MySQL
- **通知機能**
  - `notify.js` による簡易通知（例：ポモドーロ完了時）

---

## ディレクトリ構成
<pre><code>
time-task-app/
├── public/
│      ├── index.php            # ホーム画面
│      ├── todo.php             # ToDoリスト
│      ├── habits.php           # 習慣トラッカー
│      ├── pomodoro.php         # Pomodoroタイマー
│      ├── db.php
│      ├── info.php
│      ├── notify.js
│      ├── style.css
│      └── function/
│             ├── edit.php     # ToDo編集処理
│             └── delete.php   # ToDo削除処理
├── docker-compose.yml
├── Dockerfile.my_php_apache
└── README.md
</code</pre>