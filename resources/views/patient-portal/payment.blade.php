@extends('patient-portal.layout')

@section('title', 'Payment')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="text-center">
        <h1 class="text-2xl font-bold text-gray-900">Payment</h1>
        <p class="text-gray-600">Pay your bill securely online</p>
    </div>

    <!-- Invoice Details -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Invoice Details</h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-600">Invoice Number:</span>
                <span class="font-medium">{{ $invoice->invoice_number }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Clinic:</span>
                <span class="font-medium">{{ $invoice->clinic->name }}</span>
            </div>
            @if($invoice->description)
            <div class="flex justify-between">
                <span class="text-gray-600">Description:</span>
                <span class="font-medium">{{ $invoice->description }}</span>
            </div>
            @endif
            <div class="flex justify-between">
                <span class="text-gray-600">Amount:</span>
                <span class="font-medium">NPR {{ number_format($invoice->amount, 2) }}</span>
            </div>
            @if($invoice->tax_amount > 0)
            <div class="flex justify-between">
                <span class="text-gray-600">Tax (13%):</span>
                <span class="font-medium">NPR {{ number_format($invoice->tax_amount, 2) }}</span>
            </div>
            @endif
            <div class="border-t pt-3">
                <div class="flex justify-between text-lg font-bold">
                    <span>Total Amount:</span>
                    <span>NPR {{ number_format($invoice->total_amount, 2) }}</span>
                </div>
            </div>
            @if($invoice->total_paid > 0)
            <div class="flex justify-between text-green-600">
                <span>Already Paid:</span>
                <span>NPR {{ number_format($invoice->total_paid, 2) }}</span>
            </div>
            <div class="flex justify-between text-lg font-bold text-red-600">
                <span>Remaining:</span>
                <span>NPR {{ number_format($invoice->remaining_amount, 2) }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Payment Form -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Information</h3>
        
        <form method="POST" action="{{ route('patient.payment.process', $invoice) }}" class="space-y-6">
            @csrf
            
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700">Payment Amount</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">NPR</span>
                    </div>
                    <input type="number" name="amount" id="amount" step="0.01" 
                           min="1" max="{{ $invoice->remaining_amount ?? $invoice->total_amount }}"
                           value="{{ old('amount', $invoice->remaining_amount ?? $invoice->total_amount) }}"
                           class="pl-12 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                           required>
                </div>
                @error('amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                <select name="payment_method" id="payment_method" required 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select payment method</option>
                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Credit/Debit Card</option>
                    <option value="esewa" {{ old('payment_method') == 'esewa' ? 'selected' : '' }}>eSewa</option>
                    <option value="khalti" {{ old('payment_method') == 'khalti' ? 'selected' : '' }}>Khalti</option>
                </select>
                @error('payment_method')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            @if ($errors->any())
                <div class="rounded-md bg-red-50 p-4">
                    <div class="text-sm text-red-700">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Payment Method Specific Info -->
            <div id="payment-info" class="hidden">
                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Payment Instructions</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p id="payment-instructions">Select a payment method to see instructions.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('patient.invoices') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Process Payment
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('payment_method').addEventListener('change', function() {
    const method = this.value;
    const infoDiv = document.getElementById('payment-info');
    const instructionsP = document.getElementById('payment-instructions');
    
    if (method) {
        infoDiv.classList.remove('hidden');
        
        switch(method) {
            case 'cash':
                instructionsP.textContent = 'Please bring cash to the clinic for payment. Show this invoice to the receptionist.';
                break;
            case 'card':
                instructionsP.textContent = 'You will be redirected to a secure payment gateway to complete your card payment.';
                break;
            case 'esewa':
                instructionsP.textContent = 'You will be redirected to eSewa to complete your payment. Make sure you have sufficient balance.';
                break;
            case 'khalti':
                instructionsP.textContent = 'You will be redirected to Khalti to complete your payment. Make sure you have sufficient balance.';
                break;
            default:
                instructionsP.textContent = 'Select a payment method to see instructions.';
        }
    } else {
        infoDiv.classList.add('hidden');
    }
});
</script>
@endsection