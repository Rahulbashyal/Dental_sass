@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Financial Performance</h2>
            <p class="text-sm text-slate-500">Comprehensive overview of clinic revenue, expenses, and net profitability.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <form action="{{ route('financials.dashboard') }}" method="GET" class="flex items-center space-x-2">
                <select name="year" onchange="this.form.submit()" class="rounded-lg border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }} Fiscal Year</option>
                    @endfor
                </select>
            </form>
        </div>
    </div>

    <!-- Key Performance Indicators -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Gross Revenue</p>
                <h4 class="text-3xl font-black text-slate-900">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($totalIncome, 2) }}</h4>
                <div class="mt-4 flex items-center text-xs text-green-600 font-bold">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    Total Collected
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform duration-500">
                <svg class="h-32 w-32 text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Total Operating Costs</p>
                <h4 class="text-3xl font-black text-slate-900">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($totalExpenses, 2) }}</h4>
                <div class="mt-4 flex items-center text-xs text-red-500 font-bold">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                    </svg>
                    Clinic Expenses
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform duration-500">
                <svg class="h-32 w-32 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 13H5v-2h14v2z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-indigo-900 p-8 rounded-3xl shadow-xl shadow-indigo-100 relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-widest mb-2">Net Profitability</p>
                <h4 class="text-3xl font-black text-white">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($netProfit, 2) }}</h4>
                <div class="mt-4 flex items-center text-xs {{ $netProfit >= 0 ? 'text-indigo-300' : 'text-red-300' }} font-bold">
                    @php $margin = $totalIncome > 0 ? ($netProfit / $totalIncome) * 100 : 0; @endphp
                    Profit Margin: {{ number_format($margin, 1) }}%
                </div>
            </div>
            <div class="absolute -right-2 -bottom-2 opacity-10">
                <svg class="h-32 w-32 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M11.5 2C6.81 2 3 5.81 3 10.5S6.81 19 11.5 19 20 15.19 20 10.5 16.19 2 11.5 2zm0 14.5c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Revenue vs Expenses</h3>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-indigo-500 mr-2"></div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Revenue</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-red-400 mr-2"></div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Expenses</span>
                    </div>
                </div>
            </div>
            <div class="h-80">
                <canvas id="pnlChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm flex flex-col">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-8">Financial Health Score</h3>
            <div class="flex-1 flex flex-col items-center justify-center">
                <div class="relative w-48 h-48">
                    <svg class="w-full h-full" viewBox="0 0 36 36">
                        <path class="text-slate-100" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        @php $score = min(100, max(0, $margin * 2)); // Dummy score logic @endphp
                        <path class="text-indigo-600" stroke-width="3" stroke-dasharray="{{ $score }}, 100" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-4xl font-black text-slate-900">{{ number_format($score, 0) }}</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Stability Score</span>
                    </div>
                </div>
                <p class="mt-8 text-xs text-slate-500 text-center max-w-xs">Your clinic's financial stability is based on the ratio of net profit to total operational overhead.</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('pnlChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [
                    {
                        label: 'Revenue',
                        data: {!! json_encode($incomeData) !!},
                        backgroundColor: '#6366f1',
                        borderRadius: 6,
                        barThickness: 12,
                    },
                    {
                        label: 'Expenses',
                        data: {!! json_encode($expenseData) !!},
                        backgroundColor: '#f87171',
                        borderRadius: 6,
                        barThickness: 12,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { display: false },
                        border: { display: false },
                        ticks: {
                            font: { size: 10, weight: 'bold' },
                            color: '#94a3b8'
                        }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: {
                            font: { size: 10, weight: 'bold' },
                            color: '#94a3b8'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
