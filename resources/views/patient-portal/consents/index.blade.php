@extends('patient-portal.layout')

@section('title', 'Legal & Compliance')

@section('content')
<div class="max-w-6xl mx-auto space-y-10 page-fade-in pb-20">
    @php
        $patient = Auth::guard('patient')->user();
        $clinic = $patient ? $patient->clinic : null;
        $clinicColor = $clinic && $clinic->primary_color ? $clinic->primary_color : '#0ea5e9';
    @endphp

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="space-y-1">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-4 Outfit">
                <span class="w-2 h-8 rounded-full" style="background-color: {{ $clinicColor }}"></span>
                Clinical Consents
            </h1>
            <p class="text-slate-500 font-medium">Authentication-backed digital authorizations and clinical agreements.</p>
        </div>
        
        <div class="bg-white px-6 py-4 rounded-[1.5rem] border border-slate-200 shadow-sm flex items-center gap-4 w-full md:w-auto">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white shadow-lg shadow-amber-100 bg-amber-500">
                <i class="fas fa-shield-alt text-sm"></i>
            </div>
            <div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Digital Registry</p>
                <p class="text-lg font-black text-slate-900 Outfit">{{ $consents->total() }} Documents</p>
            </div>
        </div>
    </div>

    @if($consents->isEmpty())
        <div class="bg-white rounded-[2.5rem] p-24 text-center border border-slate-200 shadow-sm stagger-in">
            <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-8 text-slate-200">
                <i class="fas fa-file-signature text-3xl"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-900 Outfit">No pending authorizations</h3>
            <p class="text-slate-500 max-w-sm mx-auto mt-2 font-medium">All clinical agreements and diagnostic authorizations are fulfilled for your current account state.</p>
        </div>
    @else
        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden stagger-in">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Document Template</th>
                            <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Originating Node</th>
                            <th class="px-8 py-5 text-center text-xs font-black text-slate-400 uppercase tracking-widest">Authentication State</th>
                            <th class="px-8 py-5 text-right text-xs font-black text-slate-400 uppercase tracking-widest">Operational Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($consents as $consent)
                        <tr class="group hover:bg-slate-50/30 transition-all duration-200">
                            <!-- Template Info -->
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-white group-hover:shadow-md transition-all duration-300">
                                        <i class="fas fa-file-contract text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-900 tracking-tight capitalize">{{ $consent->template->title }}</p>
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Digital Authorization</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Clinic Node -->
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="text-xs text-slate-600 font-bold Outfit flex items-center gap-2">
                                    <i class="fas fa-hospital text-slate-300 text-xs"></i>
                                    {{ $consent->clinic->name }}
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-8 py-6 whitespace-nowrap text-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest border shadow-sm
                                    {{ $consent->status == 'signed' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100' }}">
                                    @if($consent->status == 'signed')
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> Validated Sign
                                    @else
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Signature Required
                                    @endif
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-8 py-6 whitespace-nowrap text-right">
                                @if($consent->status == 'pending')
                                    <a href="{{ route('patient.consents.sign', $consent) }}" 
                                       class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest text-white transition-all transform hover:scale-105 active:scale-95 shadow-lg shadow-slate-200"
                                       style="background-color: {{ $clinicColor }}">
                                        Execute Signature
                                    </a>
                                @else
                                <button onclick="window.print()" class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-slate-900 hover:text-white transition-all duration-300 shadow-sm border border-slate-100">
                                        <i class="fas fa-print text-xs"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($consents->hasPages())
        <div class="mt-8">
            {{ $consents->links() }}
        </div>
        @endif
    @endif

    <!-- Trust Footer -->
    <div class="flex items-center justify-center gap-4 text-slate-400 pt-8 opacity-60">
        <div class="flex items-center gap-1.5">
            <i class="fas fa-fingerprint text-xs"></i>
            <span class="text-xs font-black uppercase tracking-widest">Biometric E-Sign Architecture</span>
        </div>
        <div class="w-1 h-1 rounded-full bg-slate-300"></div>
        <div class="flex items-center gap-1.5">
            <i class="fas fa-gavel text-xs"></i>
            <span class="text-xs font-black uppercase tracking-widest">Legally Binding Authorization</span>
        </div>
    </div>
</div>
@endsection
