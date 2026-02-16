@extends('layouts.app')

@section('page-title', 'Clinical Case File')

@section('content')
<div class="page-fade-in space-y-8 pb-12">
    <!-- Clinical Navigation & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="stagger-in">
            <div class="flex items-center space-x-3 text-slate-500 text-sm mb-2">
                <a href="{{ route('clinic.patients.index') }}" class="hover:text-blue-600 transition-colors">Patient Registry</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-slate-900 font-medium">Case File: {{ $patient->name }}</span>
            </div>
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-slate-900 rounded-2xl flex items-center justify-center shadow-lg shadow-slate-200">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Clinical Case File</h1>
                    <p class="text-slate-500 font-medium italic">Comprehensive Medical Record System</p>
                </div>
            </div>
        </div>
        
        <div class="flex items-center space-x-3 stagger-in" style="--delay: 1">
            <a href="{{ route('clinic.patients.edit', $patient) }}" class="px-5 py-2.5 bg-white text-slate-700 border border-slate-200 rounded-xl font-bold hover:bg-slate-50 transition-all flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                <span>Edit Profile</span>
            </a>
            <a href="{{ route('clinic.appointments.create') }}?patient_id={{ $patient->id }}" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span>Book Visit</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8" x-data="{ tab: 'timeline' }">
        <!-- Sidebar Health Profile -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Navigation Tabs -->
            <div class="bg-white rounded-[2rem] p-2 border border-slate-100 shadow-sm flex flex-col space-y-1">
                <button @click="tab = 'timeline'" :class="tab === 'timeline' ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-500 hover:bg-slate-50'" class="w-full text-left px-6 py-4 rounded-2xl font-bold transition-all flex items-center justify-between">
                    <span class="flex items-center"><i class="fas fa-stream mr-3"></i> Timeline</span>
                    <i class="fas fa-chevron-right text-[10px] opacity-50"></i>
                </button>
                <button @click="tab = 'findings'" :class="tab === 'findings' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-500 hover:bg-slate-50'" class="w-full text-left px-6 py-4 rounded-2xl font-bold transition-all flex items-center justify-between">
                    <span class="flex items-center"><i class="fas fa-tooth mr-3"></i> Clinical Findings</span>
                    <i class="fas fa-chevron-right text-[10px] opacity-50"></i>
                </button>
                <button @click="tab = 'plans'" :class="tab === 'plans' ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-500 hover:bg-slate-50'" class="w-full text-left px-6 py-4 rounded-2xl font-bold transition-all flex items-center justify-between">
                    <span class="flex items-center"><i class="fas fa-file-medical mr-3"></i> Treatment Plans</span>
                    <i class="fas fa-chevron-right text-[10px] opacity-50"></i>
                </button>
                <button @click="tab = 'legal'" :class="tab === 'legal' ? 'bg-sky-600 text-white shadow-lg' : 'text-slate-500 hover:bg-slate-50'" class="w-full text-left px-6 py-4 rounded-2xl font-bold transition-all flex items-center justify-between">
                    <span class="flex items-center"><i class="fas fa-file-signature mr-3"></i> Documents & Consents</span>
                    <i class="fas fa-chevron-right text-[10px] opacity-50"></i>
                </button>
                <button @click="tab = 'mesh'" :class="tab === 'mesh' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-500 hover:bg-slate-50'" class="w-full text-left px-6 py-4 rounded-2xl font-bold transition-all flex items-center justify-between">
                    <span class="flex items-center"><i class="fas fa-comments mr-3"></i> Internal Mesh</span>
                    <i class="fas fa-chevron-right text-[10px] opacity-50"></i>
                </button>
                <button @click="tab = 'profile'" :class="tab === 'profile' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-500 hover:bg-slate-50'" class="w-full text-left px-6 py-4 rounded-2xl font-bold transition-all flex items-center justify-between">
                    <span class="flex items-center"><i class="fas fa-user-circle mr-3"></i> Patient Profile</span>
                    <i class="fas fa-chevron-right text-[10px] opacity-50"></i>
                </button>
            </div>

            <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm relative overflow-hidden">
                <!-- Profile Accent -->
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-600 to-indigo-600"></div>
                
                <div class="flex flex-col items-center text-center">
                    <div class="w-32 h-32 rounded-[2.5rem] bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center shadow-xl mb-6 relative group">
                        <span class="text-white font-bold text-5xl tracking-tighter">{{ substr($patient->name, 0, 1) }}</span>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight mb-1">{{ $patient->name }}</h2>
                    <p class="text-slate-500 font-medium mb-6">UID: #PAT-{{ str_pad($patient->id, 5, '0', STR_PAD_LEFT) }}</p>
                    
                    <div class="grid grid-cols-3 gap-3 w-full">
                        <div class="bg-slate-50 rounded-2xl p-3">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase mb-0.5 tracking-wider">Age</span>
                            <span class="text-slate-900 font-bold text-sm">{{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->age : 'N/A' }}</span>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-3">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase mb-0.5 tracking-wider">Gender</span>
                            <span class="text-slate-900 font-bold text-sm">{{ $patient->gender ? ucfirst(substr($patient->gender, 0, 1)) : 'N/A' }}</span>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-3">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase mb-0.5 tracking-wider">Blood</span>
                            <span class="text-slate-900 font-bold text-sm">{{ $patient->blood_group ?: '??' }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 space-y-4 pt-8 border-t border-slate-50">
                    <div class="flex items-center space-x-4 text-sm">
                        <i class="fas fa-envelope text-slate-400 w-5"></i>
                        <span class="font-bold text-slate-700">{{ $patient->email ?: 'N/A' }}</span>
                    </div>
                    <div class="flex items-center space-x-4 text-sm">
                        <i class="fas fa-phone text-slate-400 w-5"></i>
                        <span class="font-bold text-slate-700">{{ $patient->phone }}</span>
                    </div>
                </div>
            </div>

            <!-- Health Sensitivities -->
            <div class="bg-blue-50 rounded-[2rem] p-6 border border-blue-100 shadow-sm">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-blue-200 rounded-xl flex items-center justify-center text-blue-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-blue-900 tracking-tight">Clinical Alerts</h3>
                </div>
                <div class="bg-white/50 backdrop-blur-sm rounded-xl p-3 border border-amber-200/50">
                    <span class="block text-[10px] font-bold text-blue-700 uppercase mb-1 tracking-widest">Medical History</span>
                    <p class="text-sm text-blue-800 leading-relaxed italic">
                        {{ $patient->medical_history ?: 'No specific clinical history recorded.' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Case Data -->
        <div class="lg:col-span-8 space-y-8">
            <!-- Timeline Tab -->
            <div x-show="tab === 'timeline'" class="space-y-8">
                <!-- Analytics Summary -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm">
                        <span class="block text-slate-400 text-[10px] font-bold uppercase mb-3 uppercase tracking-widest">Total Visits</span>
                        <span class="text-3xl font-black text-slate-900 leading-none">{{ $patient->appointments->count() }}</span>
                    </div>
                    <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm">
                        <span class="block text-slate-400 text-[10px] font-bold uppercase mb-3 uppercase tracking-widest">Completed</span>
                        <span class="text-3xl font-black text-blue-600 leading-none">{{ $patient->appointments->where('status', 'completed')->count() }}</span>
                    </div>
                </div>

                <!-- Clinical Timeline Content -->
                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                        <h3 class="text-xl font-bold text-slate-900 tracking-tight">Clinical Timeline</h3>
                    </div>
                    <div class="p-8">
                        @forelse($patient->appointments->sortByDesc('appointment_date') as $appointment)
                            <div class="relative pl-8 pb-10 last:pb-0">
                                <div class="absolute left-[7px] top-2 bottom-0 w-[2px] bg-slate-100 last:hidden"></div>
                                <div class="absolute left-0 top-1.5 w-4 h-4 rounded-full border-4 border-white shadow-md {{ $appointment->status == 'completed' ? 'bg-blue-600' : 'bg-sky-500' }}"></div>

                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div>
                                        <h4 class="text-lg font-bold text-slate-900 leading-tight">{{ ucfirst(str_replace('_', ' ', $appointment->type)) }}</h4>
                                        <p class="text-slate-500 font-medium text-sm">
                                            {{ $appointment->appointment_date->format('l, M d, Y') }} at {{ $appointment->appointment_time->format('g:i A') }}
                                        </p>
                                    </div>
                                    <span class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest bg-slate-50 text-slate-500">
                                        {{ $appointment->status }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12 text-gray-400 italic">No appointments recorded.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Clinical Findings Tab (Interactive Tooth Chart) -->
            <div x-show="tab === 'findings'" class="space-y-8" x-cloak>
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900">Clinical Mapping</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Interactive Tooth Anatomy Explorer</p>
                    </div>
                    <button @click="$dispatch('open-note-modal')" class="bg-blue-600 text-white px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all active:scale-95">
                        <i class="fas fa-plus mr-2"></i> Record Finding
                    </button>
                </div>

                <!-- Interactive Chart Component -->
                <x-tooth-chart :patient="$patient" :notes="$toothNotes" />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($patient->clinicalNotes->sortByDesc('created_at') as $note)
                    <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm relative hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-lg font-black text-xs uppercase">
                                {{ $note->tooth_number ? 'Tooth #' . $note->tooth_number : 'General Finding' }}
                            </span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $note->created_at->format('M d, Y') }}</span>
                        </div>
                        <p class="text-slate-700 text-sm leading-relaxed mb-4">{{ $note->note }}</p>
                        <div class="flex items-center text-[10px] font-bold text-slate-400 uppercase tracking-tighter">
                            <i class="fas fa-user-md mr-1.5 text-blue-400"></i> Recorded by {{ $note->dentist->name }}
                        </div>
                    </div>
                    @empty
                    <div class="col-span-2 bg-slate-50 border-2 border-dashed border-slate-200 rounded-[2rem] p-12 text-center">
                        <i class="fas fa-tooth text-4xl text-slate-200 mb-4"></i>
                        <p class="text-slate-500 font-medium">No clinical findings recorded for this patient.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Treatment Plans Tab -->
            <div x-show="tab === 'plans'" class="space-y-8" x-cloak>
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-slate-900">Treatment Plans</h3>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-lg shadow-blue-100">
                        <i class="fas fa-plus mr-2"></i> Propose Plan
                    </button>
                </div>

                @forelse($patient->treatmentPlans->sortByDesc('created_at') as $plan)
                <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h4 class="text-xl font-bold text-slate-900 mb-1">{{ $plan->title }}</h4>
                            <div class="flex items-center space-x-3">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest 
                                    {{ $plan->status == 'approved' ? 'bg-blue-50 text-blue-700' : 'bg-blue-50 text-blue-700' }}">
                                    {{ $plan->status }}
                                </span>
                                <span class="text-xs text-slate-400 font-bold">Priority: {{ ucfirst($plan->priority) }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-black text-slate-900">${{ number_format($plan->estimated_cost, 2) }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Estimated Investment</p>
                        </div>
                    </div>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">{{ $plan->description }}</p>
                    <div class="flex gap-2">
                        <button class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold">Approve Plan</button>
                        <button class="px-4 py-2 bg-slate-50 text-slate-600 rounded-xl text-xs font-bold">Edit Details</button>
                    </div>
                </div>
                @empty
                <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-[2rem] p-12 text-center">
                    <i class="fas fa-clipboard-check text-4xl text-slate-200 mb-4"></i>
                    <p class="text-slate-500 font-medium">No treatment plans proposed yet.</p>
                </div>
                @endforelse
            </div>

            <!-- Documents & Consents -->
            <div x-show="tab === 'legal'" class="space-y-8" x-cloak>
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-slate-900">Legal Agreements</h3>
                    <button class="bg-sky-600 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-lg shadow-sky-100">
                        <i class="fas fa-file-signature mr-2"></i> Request Signature
                    </button>
                </div>

                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50">
                            <tr class="text-slate-400 text-[10px] font-black uppercase tracking-widest">
                                <th class="px-8 py-4">Document Title</th>
                                <th class="px-8 py-4 text-center">Status</th>
                                <th class="px-8 py-4">Signed Date</th>
                                <th class="px-8 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($patient->consents as $consent)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="font-bold text-slate-900">{{ $consent->template->title }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">Legal Agreement</div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-[10px] font-black uppercase border border-blue-100">
                                        {{ $consent->status }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-slate-500 font-medium text-sm">
                                    {{ $consent->signed_at->format('M d, Y') }}
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <button class="p-2 text-slate-400 hover:text-blue-600"><i class="fas fa-eye"></i></button>
                                    <button class="p-2 text-slate-400 hover:text-red-600"><i class="fas fa-print"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-12 text-center text-slate-400 italic">No legal agreements signed yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div x-show="tab === 'mesh'" class="space-y-6" x-cloak
                 x-data="{ 
                    messages: [],
                    newMessage: '',
                    loading: true,
                    fetchMessages() {
                        fetch('{{ route('clinic.patients.messages.index', $patient) }}')
                            .then(r => r.json())
                            .then(d => { this.messages = d; this.loading = false; });
                    },
                    sendMessage() {
                        if (!this.newMessage.trim()) return;
                        fetch('{{ route('clinic.patients.messages.store', $patient) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ body: this.newMessage })
                        })
                        .then(r => r.json())
                        .then(d => {
                            if (d.success) {
                                this.messages.unshift(d.message);
                                this.newMessage = '';
                            }
                        });
                    }
                 }" x-init="fetchMessages()">
                
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900">Internal Mesh</h3>
                        <p class="text-[10px] font-bold text-blue-500 uppercase tracking-widest">Confidential Staff-Only Case Discussion</p>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden flex flex-col h-[500px]">
                    <!-- Chat Header -->
                    <div class="px-8 py-4 border-b border-slate-50 bg-slate-50/50 flex items-center justify-between">
                        <div class="flex -space-x-2">
                            @foreach($patient->clinic->users->take(3) as $user)
                                <div class="w-7 h-7 rounded-full border-2 border-white bg-slate-200 flex items-center justify-center text-[8px] font-bold text-slate-500">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endforeach
                            @if($patient->clinic->users->count() > 3)
                                <div class="w-7 h-7 rounded-full border-2 border-white bg-slate-100 flex items-center justify-center text-[8px] font-black text-slate-400">
                                    +{{ $patient->clinic->users->count() - 3 }}
                                </div>
                            @endif
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Secure Mesh Active</span>
                    </div>

                    <!-- Chat Body -->
                    <div class="flex-1 overflow-y-auto p-8 space-y-4 no-scrollbar bg-slate-50/30">
                        <template x-if="loading">
                            <div class="flex justify-center py-10">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-rose-500"></div>
                            </div>
                        </template>

                        <template x-for="msg in messages" :key="msg.id">
                            <div :class="msg.sender_id === {{ auth()->id() }} ? 'flex-row-reverse' : ''" class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-xl bg-white border border-slate-100 flex-none flex items-center justify-center text-[10px] font-bold text-slate-500 shadow-sm" x-text="msg.sender.name.substring(0,1)"></div>
                                <div :class="msg.sender_id === {{ auth()->id() }} ? 'bg-blue-600 text-white rounded-tr-none' : 'bg-white text-slate-700 border border-slate-100 rounded-tl-none'" class="max-w-[80%] p-4 rounded-2xl shadow-sm">
                                    <div class="flex items-center justify-between mb-1 gap-4">
                                        <span class="text-[9px] font-black uppercase tracking-tighter" :class="msg.sender_id === {{ auth()->id() }} ? 'text-blue-200' : 'text-slate-400'" x-text="msg.sender.name"></span>
                                        <span class="text-[8px] font-bold opacity-60" x-text="new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})"></span>
                                    </div>
                                    <p class="text-xs leading-relaxed font-medium" x-text="msg.body"></p>
                                </div>
                            </div>
                        </template>

                        <template x-if="!loading && messages.length === 0">
                            <div class="py-20 text-center flex flex-col items-center">
                                <div class="w-16 h-16 bg-white rounded-3xl flex items-center justify-center text-slate-200 mb-4 shadow-sm">
                                    <i class="fa-solid fa-comments text-3xl"></i>
                                </div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Initialization Required</p>
                                <p class="text-[9px] text-slate-400 mt-1 max-w-[200px]">Start the discussion regarding this clinical case.</p>
                            </div>
                        </template>
                    </div>

                    <!-- Chat Input -->
                    <div class="p-4 border-t border-slate-100 bg-white">
                        <form @submit.prevent="sendMessage()" class="relative">
                            <input type="text" x-model="newMessage" placeholder="Type clinical observation or staff query..." 
                                   class="w-full bg-slate-50 border border-slate-100 rounded-2xl pl-12 pr-4 py-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all outline-none">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="fa-solid fa-paper-plane"></i>
                            </div>
                            <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-slate-900 text-white p-2.5 rounded-xl hover:bg-slate-800 transition-all">
                                <i class="fa-solid fa-arrow-up"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Patient Profile Tab -->
            <div x-show="tab === 'profile'" class="space-y-8" x-cloak>
                <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm">
                    <h3 class="text-2xl font-bold text-slate-900 mb-6">Patient Master Record</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Full Legal Name</label>
                                <p class="text-slate-900 font-bold text-lg">{{ $patient->name }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Identification Number</label>
                                <p class="text-slate-900 font-bold">PAT-{{ str_pad($patient->id, 5, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Contact Channels</label>
                                <div class="space-y-2">
                                    <div class="flex items-center text-slate-700 text-sm font-medium">
                                        <i class="fas fa-phone w-6 text-blue-500"></i> {{ $patient->phone }}
                                    </div>
                                    <div class="flex items-center text-slate-700 text-sm font-medium">
                                        <i class="fas fa-envelope w-6 text-blue-500"></i> {{ $patient->email ?: 'No email recorded' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-6">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Clinical Details</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-slate-50 p-4 rounded-2xl">
                                        <span class="text-[9px] font-black text-slate-400 block uppercase">Blood Group</span>
                                        <span class="text-slate-900 font-bold">{{ $patient->blood_group ?: 'Not Set' }}</span>
                                    </div>
                                    <div class="bg-slate-50 p-4 rounded-2xl">
                                        <span class="text-[9px] font-black text-slate-400 block uppercase">Date of Birth</span>
                                        <span class="text-slate-900 font-bold">{{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->format('M d, Y') : 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Clinical Narrative History</label>
                                <div class="bg-blue-50/50 p-6 rounded-3xl border border-blue-100/50 italic text-slate-700 leading-relaxed text-sm">
                                    {{ $patient->medical_history ?: 'System default: No pre-existing medical history flagged for this patient.' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Finding Record Modal -->
<div x-data="{ open: false, tooth: null }" 
     @open-note-modal.window="open = true; tooth = $event.detail ? $event.detail.tooth : null" 
     x-show="open" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4" 
     x-cloak>
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="open = false"></div>
    <div class="bg-white rounded-[2.5rem] w-full max-w-lg shadow-2xl relative p-10 overflow-hidden transform transition-all"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">
        
        <div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.2em] mb-1">Clinical Observation</p>
                <h3 class="text-2xl font-black text-slate-900">Record Finding</h3>
            </div>
            <button @click="open = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <form action="{{ route('clinic.clinical-notes.store', $patient) }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest pl-1">Tooth ID</label>
                    <input type="number" name="tooth_number" :value="tooth" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none" placeholder="None">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest pl-1">Condition</label>
                    <input type="text" name="condition" placeholder="e.g. Caries" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none">
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest pl-1">Clinical Narrative</label>
                <textarea name="note" rows="4" placeholder="Enter detailed clinical observation..." class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none resize-none"></textarea>
            </div>
            <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-2xl font-black uppercase tracking-[0.2em] text-xs hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-100 active:scale-[0.98]">
                Seal Observation
            </button>
        </form>
    </div>
</div>
@endsection