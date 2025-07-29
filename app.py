from flask import Flask, render_template, request, jsonify
import sqlite3

app = Flask(__name__)

def get_db_connection():
    conn = sqlite3.connect('todo.db')
    conn.row_factory = sqlite3.Row
    return conn

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/todos', methods=['GET'])
def get_todos():
    conn = get_db_connection()
    todos = conn.execute('SELECT * FROM todos').fetchall()
    conn.close()
    return jsonify([dict(todo) for todo in todos])

@app.route('/todos', methods=['POST'])
def add_todo():
    new_task = request.json.get('task')
    conn = get_db_connection()
    conn.execute('INSERT INTO todos (task, done) VALUES (?, ?)', (new_task, False))
    conn.commit()
    conn.close()
    return jsonify({'status': 'success'})

if __name__ == '__main__':
    conn = sqlite3.connect('todo.db')
    conn.execute('CREATE TABLE IF NOT EXISTS todos (id INTEGER PRIMARY KEY AUTOINCREMENT, task TEXT NOT NULL, done BOOLEAN NOT NULL)')
    conn.close()
    app.run(debug=True)
