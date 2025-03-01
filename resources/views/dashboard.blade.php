<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 text-center leading-tight">
            Welcome, {{ Auth::user()->familyDetails?->family_name ?? 'Guest' }}
        </h2>
    </x-slot>

    <div class="py-12">
        @php
            $monthName = date('F'); // Gets the current month name, e.g., "February"
        @endphp

        <div class="text-center text-xl font-bold mb-4">
            {{ $monthName }} Overview
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 text-center">

                    <!-- Expenses Card -->
                    <div class="bg-red-600 text-white p-6 rounded-lg shadow-md animate__animated animate__fadeInUp animate__slow">
                        <h3 class="text-xl font-bold">Expenses ({{ $monthName }})</h3>
                        <p class="text-2xl font-semibold">₹{{ number_format($expenses, 2) }}</p>
                        <i class="fa-solid fa-arrow-down text-3xl"></i>
                    </div>

                    <!-- Income Card -->
                    <div class="bg-emerald-600 text-white p-6 rounded-lg shadow-md animate__animated animate__fadeInUp animate__slow animate__delay-1s">
                        <h3 class="text-xl font-bold">Income ({{ $monthName }})</h3>
                        <p class="text-2xl font-semibold">₹{{ number_format($income, 2) }}</p>
                        <i class="fa-solid fa-arrow-up text-3xl"></i>
                    </div>

                    <!-- Balance Card (Dynamic Background Color) -->
                    @php
                        $remainingBalance = $income - $expenses;
                        $balanceBg = $remainingBalance < 0 ? 'bg-orange-500' : 'bg-blue-600';
                    @endphp

                    <div class="{{ $balanceBg }} text-white p-6 rounded-lg shadow-md animate__animated animate__fadeInUp animate__slow animate__delay-2s">
                        <h3 class="text-xl font-bold">Remaining Balance ({{ $monthName }})</h3>

                        @if($remainingBalance < 0)
                            <p class="text-2xl font-semibold">
                                Over Budget: -₹{{ number_format(abs($remainingBalance), 2) }}
                            </p>
                            <i class="fa-solid fa-triangle-exclamation text-3xl"></i>
                        @else
                            <p class="text-2xl font-semibold">
                                ₹{{ number_format($remainingBalance, 2) }}
                            </p>
                            <i class="fa-solid fa-check-circle text-3xl"></i>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        <!-- Display No Data Message if Expenses and Income are 0 -->
        @if($income == 0 && $expenses == 0)
            <div class="max-w-4xl mx-auto text-center bg-gray-100 p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-bold text-gray-800">No Data Available</h3>
                <p class="text-gray-600">You haven’t added any income or expenses yet.</p>
                <p class="text-gray-600 mt-2">To get started:</p>

                <div class="flex justify-center gap-4 mt-4">
                    <!-- Button to Personal Details -->
                    <a href="{{route('family-details.show', ['id' => auth()->user()->id])}}" class="bg-sky-600 text-white px-4 py-2 rounded-md shadow hover:bg-blue-700 transition">
                       Add Expenses and incomes
                    </a>
                </div>
                <i class="fa-solid fa-exclamation-circle text-4xl text-gray-500 mt-4"></i>
            </div>
        @elseif(Auth::user()->familyDetails == null)
            <!-- Display Prompt to Add Family Details -->
            <div class="max-w-4xl mx-auto text-center bg-gray-100 p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-bold text-gray-800">Add Family Details</h3>
                <p class="text-gray-600">You haven’t added your family details yet.</p>
                <p class="text-gray-600 mt-2">Please complete your personal information to proceed:</p>

                <div class="flex justify-center gap-4 mt-4">
                    <a href="{{ route('family-details.create') }}" class="bg-sky-600 text-white px-4 py-2 rounded-md shadow hover:bg-blue-700 transition">
                        Add Personal Details
                    </a>
                </div>
                <i class="fa-solid fa-exclamation-circle text-4xl text-gray-500 mt-4"></i>
            </div>
        @else
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pie Chart (Income vs Expenses) -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-bold mb-4 text-center">Income vs Expenses</h3>
                    <canvas id="incomeExpenseChart"></canvas>
                </div>

                <!-- Yearly Chart -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold text-gray-800 text-center mb-4">Income vs. Expenses ({{ now()->year }})</h3>
                    <canvas id="yearlyChart"></canvas>
                </div>
            </div>
        </div>


        @endif
    </div>
</x-app-layout>

<script>
    var income = Number(@json($income ?? 0));
    var expenses = Number(@json($expenses ?? 0));
    var monthlyIncome = @json($monthlyIncome);
    var monthlyExpenses = @json($monthlyExpenses);

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
</script>
