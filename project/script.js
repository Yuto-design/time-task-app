document.getElementById('todo-form').addEventListener('submit', function (e) {
    e.preventDefault();
    const input = document.getElementById('todo-input');
    const text = input.value.trim();
    if (text === '') return;

    const li = document.createElement('li');
    li.textContent = `☐ ${text}`;
    li.addEventListener('click', function () {
        li.classList.toggle('done');
        if (li.textContent.startsWith('☐')) {
        li.textContent = li.textContent.replace('☐', '☑');
        } else {
        li.textContent = li.textContent.replace('☑', '☐');
        }
    });

    document.getElementById('todo-list').appendChild(li);
    input.value = '';
});

let time = 25 * 60;
let timerInterval = null;
const timerDisplay = document.getElementById('timer');
const startBtn = document.getElementById('start-btn');
const resetBtn = document.getElementById('reset-btn');

function updateTimerDisplay() {
    const minutes = String(Math.floor(time / 60)).padStart(2, '0');
    const seconds = String(time % 60).padStart(2, '0');
    timerDisplay.textContent = `${minutes}:${seconds}`;
}

startBtn.addEventListener('click', function () {
    if (timerInterval) return;

    timerInterval = setInterval(() => {
        time--;
        updateTimerDisplay();

    if (time <= 0) {
        clearInterval(timerInterval);
        timerInterval = null;
        alert('Pomodoroセッション終了！お疲れさま！');
        time = 5 * 60;
        updateTimerDisplay();
        }
    }, 1000);
});

resetBtn.addEventListener('click', function () {
    clearInterval(timerInterval);
    timerInterval = null;
    time = 25 * 60;
    updateTimerDisplay();
});

// 初期表示
updateTimerDisplay();
