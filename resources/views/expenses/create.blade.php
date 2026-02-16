@extends('layouts.app')

@section('title', 'Record Expense')
@section('page-title', 'Record Expense')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="card bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
        <div class="p-6 border-b border-gray-50 bg-gray-50/50">
            <h2 class="text-xl font-bold text-gray-900">Record New Expense</h2>
            <p class="text-sm text-gray-500 mt-1">Keep track of your clinic expenditures, bills, and payments.</p>
        </div>
        
        <form action="{{ route('clinic.expenses.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                <div class="space-y-2">
                    <label class="form-label text-gray-700 font-semibold">Category</label>
                    <select name="category" class="form-input block w-full h-12 rounded-xl border-gray-200 focus:border-red-500" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="space-y-2">
                    <label class="form-label text-gray-700 font-semibold">Expense Date</label>
                    <input type="date" name="expense_date" class="form-input block w-full h-12 rounded-xl border-gray-200 focus:border-red-500" required value="{{ date('Y-m-d') }}">
                </div>
            </div>

            <div class="space-y-2 text-left">
                <label class="form-label text-gray-700 font-semibold">Description</label>
                <input type="text" name="description" placeholder="e.g. Monthly Electricity Bill" class="form-input block w-full h-12 rounded-xl border-gray-200 focus:border-red-500" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
                <div class="space-y-2">
                    <label class="form-label text-gray-700 font-semibold">Amount</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 font-bold">Rs.</span>
                        <input type="number" name="amount" step="0.01" class="form-input block w-full pl-10 h-12 rounded-xl border-gray-200 focus:border-red-500" required>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="form-label text-gray-700 font-semibold">Vendor (Optional)</label>
                    <select name="vendor_id" class="form-input block w-full h-12 rounded-xl border-gray-200 focus:border-red-500">
                        <option value="">Select Vendor</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="form-label text-gray-700 font-semibold">Status</label>
                    <select name="status" class="form-input block w-full h-12 rounded-xl border-gray-200 focus:border-red-500" required>
                        <option value="paid" selected>Paid</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
            </div>

            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 space-y-6 text-left">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="form-label text-gray-700 font-semibold text-left">Branch (Optional)</label>
                        <select name="branch_id" class="form-input block w-full h-12 rounded-xl border-white focus:border-red-500">
                            <option value="">Main Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="form-label text-gray-700 font-semibold text-left">Reference #</label>
                        <input type="text" name="reference_number" placeholder="Bill or Receipt ID" class="form-input block w-full h-12 rounded-xl border-white focus:border-red-500">
                    </div>
                </div>

                <div class="space-y-2 text-left">
                    <label class="form-label text-gray-700 font-semibold text-left">Receipt Attachment</label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-200 border-dashed rounded-xl cursor-pointer bg-white hover:bg-gray-50 transition">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-left">
                                <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold text-left">Click to upload</span> or drag and drop</p>
                                <p class="text-xs text-gray-400">PDF, PNG, JPG (Max 2MB)</p>
                            </div>
                            <input type="file" name="receipt" class="hidden" accept="image/*,.pdf" />
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="pt-6 border-t border-gray-100 flex items-center justify-end space-x-4">
                <a href="{{ route('clinic.expenses.index') }}" class="px-6 py-3 rounded-xl border border-gray-200 text-gray-600 font-semibold hover:bg-gray-50 transition duration-150">
                    Cancel
                </a>
                <button type="submit" class="px-10 py-3 bg-red-600 text-white rounded-xl font-bold shadow-lg shadow-red-100 hover:bg-red-700 transform hover:-translate-y-0.5 transition duration-150">
                    Record Expense
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
