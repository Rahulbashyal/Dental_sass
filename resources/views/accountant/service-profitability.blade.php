@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-6 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-800 to-blue-600 bg-clip-text text-transparent">Service Profitability Analysis</h1>
            <p class="text-gray-500">Evaluating revenue performance by treatment type</p>
        </div>
        <div class="bg-white p-1 rounded-xl border border-gray-100 shadow-sm flex gap-1">
            <button class="px-4 py-2 text-sm font-medium bg-blue-50 text-blue-700 rounded-lg">Overall</button>
            <button class="px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 rounded-lg">By Branch</button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Main Data Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50">
                <h3 class="font-bold text-gray-900">Treatment Revenue Breakdown</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50">
                        <tr class="text-gray-400 text-xs uppercase tracking-wider">
                            <th class="px-6 py-3 font-medium">Treatment Type</th>
                            <th class="px-6 py-3 font-medium text-center">Volume</th>
                            <th class="px-6 py-3 font-medium text-right">Total Revenue</th>
                            <th class="px-6 py-3 font-medium text-right">Avg. Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($profitability as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-semibold text-gray-900">{{ ucfirst($item->treatment_type) }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-bold">{{ $item->usage_count }}</span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-blue-600">
                                ${{ number_format($item->total_revenue, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right text-gray-500">
                                ${{ number_format($item->total_revenue / $item->usage_count, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Insights Cards -->
        <div class="space-y-6">
            <div class="bg-gradient-to-br from-indigo-900 to-indigo-700 rounded-2xl p-6 text-white shadow-lg">
                <h3 class="text-indigo-100 text-sm font-medium uppercase tracking-wider mb-2">Highest Value Service</h3>
                @if($profitability->count() > 0)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold">{{ ucfirst($profitability->first()->treatment_type) }}</p>
                            <p class="text-indigo-200 text-sm mt-1">Generating ${{ number_format($profitability->first()->total_revenue, 2) }} this period</p>
                        </div>
                        <div class="h-12 w-12 bg-white/10 rounded-full flex items-center justify-center border border-white/20">
                            <i class="fas fa-crown text-xl"></i>
                        </div>
                    </div>
                @else
                    <p class="text-xl">No data available</p>
                @endif
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4">Strategic Recommendations</h3>
                <ul class="space-y-4">
                    <li class="flex gap-3">
                        <div class="mt-1 h-5 w-5 bg-green-100 text-green-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-check text-[10px]"></i>
                        </div>
                        <p class="text-sm text-gray-600">Focus marketing efforts on high-margin treatments identified above.</p>
                    </li>
                    <li class="flex gap-3">
                        <div class="mt-1 h-5 w-5 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-info text-[10px]"></i>
                        </div>
                        <p class="text-sm text-gray-600">Audit low-volume/low-revenue services for potential price adjustments or removal.</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
