# Time Task App

## 概要

**Time Task App** は、毎日の **タスク管理 (ToDo)**・**習慣形成 (Habits)**・**集中作業 (Pomodoro)** をサポートするアプリケーションです。  
PHP + MySQLを用いて構築されており、タスクや習慣の記録・進捗の可視化を行います。  
学生が効率的に時間を使い、継続的な成長を実現することを目的としています。

---



---

## 使用技術
- **フロントエンド**
  - HTML / CSS / JavaScript
  - [Chart.js](https://www.chartjs.org/) を使用してグラフ描画
- **バックエンド**
  - PHP
  - PDO を用いたデータベース操作
- **データベース**
  - MySQL

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
│             ├── edit.php      # ToDo編集処理
│             └── delete.php    # ToDo削除処理
├── docker-compose.yml
├── Dockerfile.my_php_apache
└── README.md
</code></pre>

---

## セットアップ手順

**１．リポジトリをクローン**
<pre><code>
git clone https://github.com/your-username/time-tracker-app.git
cd time-tracker-app
</code></pre>

**２．開発環境の準備**  
*Docker + docker-compose*
- Docker Desktop をインストール
- 以下のコマンドで環境を構築・起動
<pre><code>
docker compose up -d
</code></pre>
- phpMyAdmin（DB管理画面）にアクセス：http://localhost:8085  
　ログイン情報：`docker-compose.yml` に記載

**３．動作確認**
- ブラウザで次のURLにアクセスして表示確認：http://loclhost:8084

---

## ページ一覧
- `/index.php`  
  トップページ
- `/todo.php`  
  ToDoリスト管理（追加・表示・編集・削除）
- `/habits.php`  
  習慣トラッカー（習慣登録・進捗確認・達成率表示）
- `/pomodoro.php`  
  ポモドーロタイマー（集中タイマー、過去の記録）
