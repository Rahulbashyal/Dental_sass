@extends('patient-portal.layout')

@section('title', 'Finalize Payment')

@section('content')
<div class="max-w-3xl mx-auto space-y-10 page-fade-in pb-20">
    @php
        $patient = Auth::guard('patient')->user();
        $clinic = $patient ? $patient->clinic : null;
        $clinicColor = $clinic && $clinic->primary_color ? $clinic->primary_color : '#0ea5e9';
    @endphp

    <!-- Header -->
    <div class="text-center space-y-6">
        <div class="w-20 h-20 bg-emerald-50 text-emerald-600 rounded-[2.5rem] flex items-center justify-center mx-auto shadow-inner border border-emerald-100 mb-8 transition-transform duration-700 hover:rotate-12">
            <i class="fas fa-shield-alt text-3xl"></i>
        </div>
        <div class="space-y-2">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight Outfit">Procurement Gateway</h1>
            <p class="text-slate-500 font-black uppercase tracking-widest text-xs opacity-60">Secure Financial Node: DCMS-V3-PRO</p>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-2xl overflow-hidden relative group">
        <div class="h-2" style="background-color: {{ $clinicColor }}"></div>
        <div class="p-10 space-y-10">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                <div class="space-y-2">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Target Invoice</p>
                    <h3 class="text-2xl font-black text-slate-900 Outfit tracking-tight">{{ $invoice->invoice_number }}</h3>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-tighter opacity-70">{{ $invoice->clinic->name }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Total Due</p>
                    <p class="text-3xl font-black text-slate-900 tracking-tight Outfit" style="color: {{ $clinicColor }}">NPR {{ number_format($invoice->remaining_amount ?? $invoice->total_amount, 2) }}</p>
                </div>
            </div>

            <div class="bg-slate-50 rounded-[2rem] p-8 border border-slate-100 space-y-4">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500 font-bold uppercase tracking-widest text-xs">Net Procedural Value</span>
                    <span class="text-slate-800 font-black Outfit">NPR {{ number_format($invoice->total_amount, 2) }}</span>
                </div>
                @if($invoice->total_paid > 0)
                <div class="flex justify-between items-center text-sm">
                    <span class="text-emerald-500 font-bold uppercase tracking-widest text-xs">Previously Synchronized</span>
                    <span class="text-emerald-600 font-black Outfit">- NPR {{ number_format($invoice->total_paid, 2) }}</span>
                </div>
                @endif
                <div class="h-px bg-slate-200 w-full opacity-50"></div>
                <div class="flex justify-between items-center pt-2">
                    <span class="text-xs font-black text-slate-900 uppercase tracking-[0.2em]">Fulfillment Target</span>
                    <span class="text-2xl font-black text-slate-900 Outfit">NPR {{ number_format($invoice->remaining_amount ?? $invoice->total_amount, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Fulfillment Form -->
    <div class="bg-white rounded-[3rem] p-10 border border-slate-200 shadow-xl stagger-in hover:border-slate-300 transition-all duration-300">
        <form method="POST" action="{{ route('patient.payment.process', $invoice) }}" class="space-y-12">
            @csrf
            
            <div class="space-y-4">
                <label for="amount" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-2 flex justify-between items-center">
                    Authorized Amount
                    <span class="text-xs text-slate-400 font-bold">Min: NPR 1.00</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-12 flex items-center pointer-events-none z-10">
                        <span class="text-2xl font-black text-slate-400 group-focus-within:text-slate-900 transition-colors Outfit">NPR</span>
                    </div>
                    <input type="number" name="amount" id="amount" step="0.01" 
                           min="1" max="{{ $invoice->remaining_amount ?? $invoice->total_amount }}"
                           value="{{ old('amount', $invoice->remaining_amount ?? $invoice->total_amount) }}"
                           style="padding-left: 11rem !important;"
                           class="w-full pr-8 py-10 bg-white border-2 border-slate-100 rounded-[2.5rem] focus:outline-none focus:ring-8 focus:ring-slate-50 focus:border-slate-300 transition-all text-4xl font-black text-slate-900 tracking-tight Outfit shadow-inner"
                           required>
                </div>
                @error('amount')
                    <p class="mt-2 text-xs uppercase tracking-widest ml-6" style="color: #f43f5e; font-weight: 700;">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-8">
                <label class="text-xs font-black text-slate-500 uppercase tracking-widest ml-2">Payment Vector</label>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6" id="payment-vectors">
                    @php
                        $vectors = [
                            'cash'   => ['icon' => 'fa-money-bill-wave', 'label' => 'Cash',   'color' => '#2563eb'],
                            'card'   => ['icon' => 'fa-credit-card',     'label' => 'Card',   'color' => '#eab308'],
                            'esewa'  => ['icon' => 'fa-wallet',          'label' => 'eSewa',  'color' => '#16a34a'],
                            'khalti' => ['icon' => 'fa-mobile-alt',      'label' => 'Khalti', 'color' => '#7c3aed'],
                        ];
                    @endphp
                    @foreach($vectors as $value => $meta)
                        <label class="relative cursor-pointer h-full" data-vector="{{ $value }}" data-color="{{ $meta['color'] }}">
                            <input type="radio" name="payment_method" value="{{ $value }}" class="sr-only vector-radio" {{ old('payment_method') == $value ? 'checked' : '' }}>
                            <div class="vector-card h-full flex flex-col items-center justify-center p-8 rounded-[2.5rem] border border-slate-200 bg-white transition-all duration-300 hover:shadow-lg">
                                <div class="vector-icon w-20 h-20 rounded-[1.8rem] flex items-center justify-center mb-6 transition-all duration-300 bg-slate-50 text-slate-400" style="border: 1px solid #e2e8f0;">
                                    <i class="fas {{ $meta['icon'] }} text-3xl"></i>
                                </div>
                                <span class="vector-label text-xs font-black uppercase tracking-widest text-slate-500 transition-colors duration-300">{{ $meta['label'] }}</span>
                            </div>
                            <div class="vector-check absolute top-8 right-8 opacity-0 transition-all duration-300" style="transform: scale(0);">
                                <i class="fas fa-check-circle text-white text-lg"></i>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('payment_method')
                    <p class="mt-2 text-xs font-bold text-rose-500 uppercase tracking-widest ml-6">{{ $message }}</p>
                @enderror
            </div>

            <!-- Dynamic Protocol Info -->
            <div id="payment-info" class="hidden animate-fade-in">
                <div class="bg-slate-900 text-white rounded-[2.5rem] p-10 relative overflow-hidden shadow-2xl shadow-slate-900/40">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full blur-3xl -mr-20 -mt-20"></div>
                    <div class="flex gap-8 relative z-10">
                        <div class="w-14 h-14 bg-white/10 backdrop-blur-md rounded-[1.2rem] flex items-center justify-center text-xl flex-shrink-0" style="color: {{ $clinicColor }}">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="space-y-2">
                            <h4 class="text-[10px] font-black uppercase tracking-widest text-white/40">Protocol Instructions</h4>
                            <p id="payment-instructions" class="text-sm font-bold leading-relaxed Outfit text-white/90">Initializing fulfillment vector...</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-6 pt-10">
                <a href="{{ route('patient.invoices') }}" 
                   class="flex-1 py-6 bg-white border border-slate-200 text-slate-400 rounded-[2rem] text-xs font-black uppercase tracking-widest hover:bg-slate-50 hover:text-slate-600 transition-all text-center flex items-center justify-center Outfit">
                    Abort Protocol
                </a>
                <button type="submit" 
                        class="flex-[2] py-6 text-white rounded-[2rem] text-xs font-black uppercase tracking-widest shadow-2xl transform active:scale-95 transition-all shadow-slate-100 Outfit"
                        style="background-color: {{ $clinicColor }}">
                    Execute Fulfillment
                </button>
            </div>
        </form>
    </div>

    <div class="text-center opacity-30 pt-10">
        <p class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-500">End-to-End Encryption • PCI-DSS Compliant • Secure Tokenization</p>
    </div>
</div>

<script>
// Payment Vector Color Engine
function updateVectorCards() {
    var labels = document.querySelectorAll('[data-vector]');
    labels.forEach(function(label) {
        var radio = label.querySelector('.vector-radio');
        var card = label.querySelector('.vector-card');
        var icon = label.querySelector('.vector-icon');
        var labelText = label.querySelector('.vector-label');
        var check = label.querySelector('.vector-check');
        var color = label.getAttribute('data-color');

        if (radio.checked) {
            card.style.backgroundColor = color;
            card.style.borderColor = 'transparent';
            card.style.transform = 'scale(1.05)';
            card.style.boxShadow = '0 20px 40px ' + color + '40';
            icon.style.backgroundColor = 'rgba(255,255,255,0.2)';
            icon.style.color = 'white';
            icon.style.border = 'none';
            labelText.style.color = 'white';
            check.style.opacity = '1';
            check.style.transform = 'scale(1)';
        } else {
            card.style.backgroundColor = 'white';
            card.style.borderColor = '#e2e8f0';
            card.style.transform = 'scale(1)';
            card.style.boxShadow = 'none';
            icon.style.backgroundColor = '#f8fafc';
            icon.style.color = '#94a3b8';
            icon.style.border = '1px solid #e2e8f0';
            labelText.style.color = '#64748b';
            check.style.opacity = '0';
            check.style.transform = 'scale(0)';
        }
    });
}

// Hover Color Engine
document.querySelectorAll('[data-vector]').forEach(function(label) {
    var card = label.querySelector('.vector-card');
    var icon = label.querySelector('.vector-icon');
    var labelText = label.querySelector('.vector-label');
    var color = label.getAttribute('data-color');
    var radio = label.querySelector('.vector-radio');

    label.addEventListener('mouseenter', function() {
        if (!radio.checked) {
            card.style.backgroundColor = color + '12';
            card.style.borderColor = color + '40';
            card.style.boxShadow = '0 10px 30px ' + color + '20';
            card.style.transform = 'scale(1.02)';
            icon.style.color = color;
            icon.style.backgroundColor = color + '15';
            icon.style.border = '1px solid ' + color + '30';
            labelText.style.color = color;
        }
    });

    label.addEventListener('mouseleave', function() {
        if (!radio.checked) {
            card.style.backgroundColor = 'white';
            card.style.borderColor = '#e2e8f0';
            card.style.boxShadow = 'none';
            card.style.transform = 'scale(1)';
            icon.style.color = '#94a3b8';
            icon.style.backgroundColor = '#f8fafc';
            icon.style.border = '1px solid #e2e8f0';
            labelText.style.color = '#64748b';
        }
    });
});

document.querySelectorAll('.vector-radio').forEach(function(radio) {
    radio.addEventListener('change', function() {
        updateVectorCards();
    });
});

// Run on load for pre-checked state
document.addEventListener('DOMContentLoaded', updateVectorCards);

// Payment Info Panel
document.querySelectorAll('input[name="payment_method"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        var method = this.value;
        var infoDiv = document.getElementById('payment-info');
        var instructionsP = document.getElementById('payment-instructions');
        
        if (method) {
            infoDiv.classList.remove('hidden');
            
            switch(method) {
                case 'cash':
                    instructionsP.textContent = 'Please present the generated reference at the clinic front-desk. A receipt node will be issued upon physical synchronization.';
                    break;
                case 'card':
                    instructionsP.textContent = 'Redirecting to global card network. Ensure 3D-Secure authentication is enabled on your merchant node.';
                    break;
                case 'esewa':
                    instructionsP.textContent = 'Initializing eSewa API tunnel. Verification of wallet balance and authorized transaction PIN is required.';
                    break;
                case 'khalti':
                    instructionsP.textContent = 'Opening Khalti secure socket. Secure OTP verification will be required to finalize procurement.';
                    break;
                default:
                    instructionsP.textContent = 'Awaiting vector selection...';
            }
        } else {
            infoDiv.classList.add('hidden');
        }
    });
});
</script>
@endsection
