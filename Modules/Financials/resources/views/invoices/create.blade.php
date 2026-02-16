@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Generate New Invoice</h2>
            <p class="text-sm text-slate-500">Create professional billing for treatments and consultations.</p>
        </div>
        <a href="{{ route('clinic.invoices.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to List
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-xl bg-red-50 border border-red-100 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-red-800">Invoicing errors:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('clinic.invoices.store') }}" method="POST" class="p-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Logistics -->
                <div class="space-y-6">
                    <div>
                        <label for="patient_id" class="block text-sm font-bold text-slate-700 mb-1">Patient *</label>
                        <select name="patient_id" id="patient_id" required class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" onchange="window.location.href='?patient_id=' + this.value">
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->full_name }} ({{ $patient->patient_id }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="appointment_id" class="block text-sm font-bold text-slate-700 mb-1">Link to Appointment</label>
                        <select name="appointment_id" id="appointment_id" class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">No specific appointment</option>
                            @foreach($appointments as $appointment)
                                <option value="{{ $appointment->id }}">
                                    {{ $appointment->appointment_date->format('M d, Y') }} - {{ $appointment->type }} (Dr. {{ $appointment->dentist->name }})
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-[10px] text-slate-400 font-medium">Linking an appointment automatically sets the treatment type.</p>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-bold text-slate-700 mb-1">Treatment Description *</label>
                        <input type="text" name="description" id="description" required placeholder="e.g. Tooth Filling, Consultation" class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                </div>

                <!-- Financials -->
                <div class="space-y-6">
                    <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Pricing Breakdown</h4>
                        <div class="space-y-4">
                            <div>
                                <label for="amount" class="block text-xs font-bold text-slate-600 mb-1 uppercase tracking-tight">Base Amount ({{ tenant()->clinic->currency ?? '$' }}) *</label>
                                <input type="number" name="amount" id="amount" step="0.01" required value="{{ old('amount', 0) }}" class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold text-slate-900">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="tax_amount" class="block text-xs font-bold text-slate-600 mb-1 uppercase tracking-tight">Tax</label>
                                    <input type="number" name="tax_amount" id="tax_amount" step="0.01" value="{{ old('tax_amount', 0) }}" class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                                <div>
                                    <label for="discount_amount" class="block text-xs font-bold text-slate-600 mb-1 uppercase tracking-tight">Discount</label>
                                    <input type="number" name="discount_amount" id="discount_amount" step="0.01" value="{{ old('discount_amount', 0) }}" class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="due_date" class="block text-sm font-bold text-slate-700 mb-1">Payment Due Date *</label>
                        <input type="date" name="due_date" id="due_date" required value="{{ date('Y-m-d', strtotime('+3 days')) }}" class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <label for="notes" class="block text-sm font-bold text-slate-700 mb-1">Internal Notes</label>
                <textarea name="notes" id="notes" rows="2" placeholder="Any specific payment terms or patient requests..." class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
            </div>

            <div class="mt-12 pt-8 border-t border-slate-100 flex justify-end space-x-3">
                <button type="reset" class="px-6 py-2 border border-slate-200 text-sm font-medium rounded-lg text-slate-600 hover:bg-slate-50 transition-colors">
                    Reset
                </button>
                <button type="submit" class="px-8 py-2 bg-indigo-600 shadow-lg shadow-indigo-100 text-white text-sm font-bold rounded-lg hover:bg-indigo-700 transition-all">
                    Generate Invoice
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
