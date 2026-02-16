@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold bg-gradient-to-r from-green-800 to-green-600 bg-clip-text text-transparent">Branch Performance Comparison</h1>
        <p class="text-gray-500">Cross-branch financial analytics for {{ Auth::user()->clinic->name }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        @foreach($comparison as $branch)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-900 text-lg">{{ $branch['name'] }}</h3>
                <span class="p-2 bg-green-50 text-green-600 rounded-lg">
                    <i class="fas fa-warehouse text-sm"></i>
                </span>
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Revenue</span>
                    <span class="font-semibold text-green-600">${{ number_format($branch['revenue'], 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Expenses</span>
                    <span class="font-semibold text-red-500">${{ number_format($branch['expenses'], 2) }}</span>
                </div>
                <div class="pt-4 border-t border-gray-50 flex justify-between items-center">
                    <span class="text-gray-900 font-medium">Net Profit</span>
                    <span class="font-bold text-lg {{ ($branch['revenue'] - $branch['expenses']) >= 0 ? 'text-green-700' : 'text-red-700' }}">
                        ${{ number_format($branch['revenue'] - $branch['expenses'], 2) }}
                    </span>
                </div>
            </div>
            
            <div class="mt-4 bg-gray-50 p-3 rounded-xl flex items-center justify-between text-sm">
                <span class="text-gray-500"><i class="far fa-calendar-check mr-2"></i>Appointments</span>
                <span class="font-bold text-gray-900">{{ $branch['appointments'] }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Chart Placeholder or Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-900 mb-6">Detailed Comparison Table</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-gray-400 text-sm uppercase tracking-wider">
                        <th class="pb-4 font-medium">Branch Name</th>
                        <th class="pb-4 font-medium text-right">Revenue</th>
                        <th class="pb-4 font-medium text-right">Expenses</th>
                        <th class="pb-4 font-medium text-right">Net Profit</th>
                        <th class="pb-4 font-medium text-center">Profit Margin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($comparison as $branch)
                    <tr>
                        <td class="py-4 font-medium text-gray-900">{{ $branch['name'] }}</td>
                        <td class="py-4 text-right text-green-600 font-medium">${{ number_format($branch['revenue'], 2) }}</td>
                        <td class="py-4 text-right text-red-500">${{ number_format($branch['expenses'], 2) }}</td>
                        <td class="py-4 text-right font-bold text-gray-900">${{ number_format($branch['revenue'] - $branch['expenses'], 2) }}</td>
                        <td class="py-4 text-center">
                            @if($branch['revenue'] > 0)
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-bold">
                                    {{ round((($branch['revenue'] - $branch['expenses']) / $branch['revenue']) * 100, 1) }}%
                                </span>
                            @else
                                <span class="text-gray-400 text-xs">N/A</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
