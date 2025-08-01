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
