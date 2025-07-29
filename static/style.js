async function loadTodos() {
    const res = await fetch('/todos');
    const todos = await res.json();
    const list = document.getElementById('todo-list');
    list.innerHTML = '';
    todos.forEach(todo => {
        const li = document.createElement('li');
        li.textContent = todo.task;
        list.appendChild(li);
    });
}

document.getElementById('todo-form').addEventListener('submit', async e => {
    e.preventDefault();
    const input = document.getElementById('todo-input');
    const task = input.value.trim();
    if (!task) return;
    await fetch('/todos', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({task})
    });
    input.value = '';
    loadTodos();
});

let time = 25 * 60;
let timerId = null;

function updateTimer() {
    const min = Math.floor(time / 60).toString().padStart(2, '0');
    const sec = (time % 60).toString().padStart(2, '0');
    document.getElementById('timer').textContent = `${min}:${sec}`;
}

function startTimer() {
    if (timerId) return;
    timerId = setInterval(() => {
        if (time <= 0) {
        clearInterval(timerId);
        timerId = null;
        alert('Pomodoro終了！');
        time = 25 * 60;
        } else {
        time--;
        updateTimer();
        }
    }, 1000);
}

function resetTimer() {
    clearInterval(timerId);
    timerId = null;
    time = 25 * 60;
    updateTimer();
}

document.getElementById('start-btn').addEventListener('click', startTimer);
document.getElementById('reset-btn').addEventListener('click', resetTimer);

updateTimer();
loadTodos();
