
document.addEventListener("livewire:load", function () {
    console.log("Livewire Loaded - Initializing Charts");
    initializeCharts();
});

document.addEventListener("livewire:navigated", function () {
    console.log("Livewire Navigated - Reinitializing Charts");
    initializeCharts();
});

function initializeCharts() {
    // PIE CHART (Income vs Expenses)
    if (document.getElementById('incomeExpenseChart')) {
        const ctx = document.getElementById('incomeExpenseChart').getContext('2d');

        if (window.incomeExpenseChart instanceof Chart) {
            window.incomeExpenseChart.destroy(); // Destroy old chart if exists
        }

        const total = (income || 0) + (expenses || 0);
        const incomePercentage = total > 0 ? (income / total) * 100 : 0;
        const expensePercentage = total > 0 ? (expenses / total) * 100 : 0;

        window.incomeExpenseChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Income', 'Expenses'],
                datasets: [{
                    data: [incomePercentage, expensePercentage],
                    backgroundColor: ['#16A34A', '#DC2626'],
                    hoverBackgroundColor: ['#15803D', '#B91C1C'],
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#333',
                            font: { size: 14 }
                        }
                    }
                }
            }
        });
    }

    // BAR/LINE CHART (Yearly Chart)
    if (document.getElementById('yearlyChart')) {
        const yearlyCtx = document.getElementById('yearlyChart').getContext('2d');

        if (window.yearlyChart instanceof Chart) {
            window.yearlyChart.destroy(); // Destroy old chart if exists
        }

        window.yearlyChart = new Chart(yearlyCtx, {
            type: 'bar', // Change to 'line' if it's a line chart
            data: {
                labels: Object.keys(monthlyIncome), // Months
                datasets: [
                    {
                        label: 'Income',
                        data: Object.values(monthlyIncome),
                        backgroundColor: 'rgba(22, 163, 74, 0.7)', // Green
                        borderColor: '#15803D',
                        borderWidth: 1
                    },
                    {
                        label: 'Expenses',
                        data: Object.values(monthlyExpenses),
                        backgroundColor: 'rgba(220, 38, 38, 0.7)', // Red
                        borderColor: '#B91C1C',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
}
