document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('habitChart');
    if (ctx) {
        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: window.chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const summaryRate = parseFloat(document.querySelector('#summaryChart').dataset.rate);
    const ctxSummary = document.getElementById('summaryChart').getContext('2d');
    new Chart(ctxSummary, {
        type: 'doughnut',
        data: {
            labels: ['達成率', '未達成率'],
            datasets: [{
                data: [summaryRate, 100 - summaryRate],
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

    const dailyLabels = JSON.parse(document.querySelector('#dailyChart').dataset.labels);
    const dailyData = JSON.parse(document.querySelector('#dailyChart').dataset.data);
    const ctxDaily = document.getElementById('dailyChart').getContext('2d');
    new Chart(ctxDaily, {
        type: 'bar',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: '日別平均達成率',
                data: dailyData,
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
});

let isRunning = false;
let isPaused = false;
let seconds = 25 * 60;
let phase = 'work';
let timerInterval;

let customWorkMinutes = 25;
let customBreakMinutes = 5;

function updateDisplay() {
    const min = Math.floor(seconds / 60);
    const sec = seconds % 60;
    document.getElementById('timer').textContent = `${String(min).padStart(2, '0')}:${String(sec).padStart(2, '0')}`;
}

function startTimer() {
    if (isRunning) return;

    isRunning = true;
    isPaused = false;
    document.getElementById('pauseBtn').style.display = 'inline-block';
    document.getElementById('startBtn').disabled = true;

    timerInterval = setInterval(() => {
        if (!isPaused) {
            seconds--;
            updateDisplay();

            if (seconds <= 0) {
                clearInterval(timerInterval);
                isRunning = false;
                document.getElementById('startBtn').disabled = false;
                document.getElementById('pauseBtn').style.display = 'none';

                if (phase === 'work') {
                    phase = 'break';
                    seconds = customBreakMinutes * 60;
                    document.getElementById('phase-label').textContent = '休憩フェーズ';
                    alert("作業終了！休憩しましょう。");
                } else {
                    phase = 'work';
                    seconds = customWorkMinutes * 60;
                    document.getElementById('phase-label').textContent = '作業フェーズ';
                    alert("休憩終了！再開しましょう。");
                }
                updateDisplay();
            }
        }
    }, 1000);
}

function togglePause() {
    isPaused = !isPaused;
    const pauseBtn = document.getElementById('pauseBtn');
    pauseBtn.textContent = isPaused ? '再開' : '一時停止';
}

function applyCustomTime(event) {
    event.preventDefault();
    const workInput = document.getElementById('workMinutes').value;
    const breakInput = document.getElementById('breakMinutes').value;

    customWorkMinutes = parseInt(workInput);
    customBreakMinutes = parseInt(breakInput);

    phase = 'work';
    seconds = customWorkMinutes * 60;
    isRunning = false;
    isPaused = false;
    clearInterval(timerInterval);
    document.getElementById('startBtn').disabled = false;
    document.getElementById('pauseBtn').style.display = 'none';
    document.getElementById('pauseBtn').textContent = '一時停止';
    document.getElementById('phase-label').textContent = '作業フェーズ';
    updateDisplay();
}

document.addEventListener("DOMContentLoaded", () => {
    const ctx = document.getElementById('sessionChart')?.getContext('2d');
    if (!ctx || typeof graphLabels === 'undefined' || typeof graphCounts === 'undefined') return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: graphLabels,
            datasets: [{
                label: 'セッション数',
                data: graphCounts,
                backgroundColor: '#3498db'
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => `${ctx.raw} セッション`
                    }
                }
            },
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
