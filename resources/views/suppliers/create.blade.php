@extends(request()->has('iframe') ? 'layouts.iframe' : 'layouts.app')

@section('page-title', 'Supply Chain: Partner Initialization')

@section('content')
<div class="page-fade-in max-w-4xl mx-auto pb-12">
    <!-- Premium Header -->
    <div class="stagger-in mb-8">
        <div class="flex items-center space-x-3 text-slate-500 text-sm mb-2">
            <a href="{{ route('clinic.suppliers.index') }}" class="hover:text-blue-600 transition-colors">Supplier Portfolio</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-slate-900 font-medium tracking-tight">New Strategic Partner Initialization</span>
        </div>
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-100">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Initialize Partner</h1>
                <p class="text-slate-500 font-medium italic">Establishing a new Logistics Node entry</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('clinic.suppliers.store') }}" class="space-y-6">
    @if(request()->has('iframe'))
        <input type="hidden" name="iframe" value="1">
    @endif

        @csrf
        
        <!-- Partner Identification Section -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 1">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Entity Identification</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                <div class="space-y-2 md:col-span-2">
                    <label for="name" class="block font-bold text-slate-700 tracking-tight">Corporate/Brand Identifier <span class="text-blue-500">*</span></label>
                    <input type="text" name="name" id="name" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                        placeholder="e.g. Global Medical Solutions" value="{{ old('name') }}" required>
                    @error('name') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="contact_person" class="block font-bold text-slate-700 tracking-tight">Liaison Name / POC</label>
                    <input type="text" name="contact_person" id="contact_person" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                        placeholder="e.g. John Doe" value="{{ old('contact_person') }}">
                    @error('contact_person') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="space-y-2">
                     <label for="phone" class="block font-bold text-slate-700 tracking-tight">Telephonic Line</label>
                    <input type="text" name="phone" id="phone" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                        placeholder="+977-XXXXXXXXXX" value="{{ old('phone') }}">
                    @error('phone') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Communication Intel Section -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 2">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Logistics Intel</h2>
            </div>
            
            <div class="grid grid-cols-1 gap-8 text-sm">
                <div class="space-y-2">
                    <label for="email" class="block font-bold text-slate-700 tracking-tight">Electronic Mail Address</label>
                    <input type="email" name="email" id="email" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                        placeholder="orders@partner-network.com" value="{{ old('email', ['iframe' => 1]) }}" data-modal-url="{{ route('clinic.suppliers.index') }}" class="hover:text-blue-600 transition-colors">Supplier Portfolio</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-slate-900 font-medium tracking-tight">New Strategic Partner Initialization</span>
        </div>
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-100">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Initialize Partner</h1>
                <p class="text-slate-500 font-medium italic">Establishing a new Logistics Node entry</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('clinic.suppliers.store') }}" class="space-y-6">
    @if(request()->has('iframe'))
        <input type="hidden" name="iframe" value="1">
    @endif

        @csrf
        
        <!-- Partner Identification Section -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 1">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Entity Identification</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                <div class="space-y-2 md:col-span-2">
                    <label for="name" class="block font-bold text-slate-700 tracking-tight">Corporate/Brand Identifier <span class="text-blue-500">*</span></label>
                    <input type="text" name="name" id="name" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                        placeholder="e.g. Global Medical Solutions" value="{{ old('name') }}" required>
                    @error('name') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="contact_person" class="block font-bold text-slate-700 tracking-tight">Liaison Name / POC</label>
                    <input type="text" name="contact_person" id="contact_person" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                        placeholder="e.g. John Doe" value="{{ old('contact_person') }}">
                    @error('contact_person') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="space-y-2">
                     <label for="phone" class="block font-bold text-slate-700 tracking-tight">Telephonic Line</label>
                    <input type="text" name="phone" id="phone" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                        placeholder="+977-XXXXXXXXXX" value="{{ old('phone') }}">
                    @error('phone') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Communication Intel Section -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 2">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Logistics Intel</h2>
            </div>
            
            <div class="grid grid-cols-1 gap-8 text-sm">
                <div class="space-y-2">
                    <label for="email" class="block font-bold text-slate-700 tracking-tight">Electronic Mail Address</label>
                    <input type="email" name="email" id="email" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                        placeholder="orders@partner-network.com" value="{{ old('email', ['iframe' => 1]) }}" data-modal-title="@error('email') {{ $message }} @enderror
                
                
                
                    Geospatial Headquarters / physical Location
                    {{ old('address') }}
                    @error('address') {{ $message }} @enderror
                
            
        

        
        
            
                Abort Protocol">
                    @error('email') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="space-y-2">
                    <label for="address" class="block font-bold text-slate-700 tracking-tight">Geospatial Headquarters / physical Location</label>
                    <textarea name="address" id="address" rows="3"
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                        placeholder="Complete headquarters address for transit logistics...">{{ old('address') }}</textarea>
                    @error('address') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Action Hub -->
        <div class="stagger-in flex flex-col md:flex-row md:items-center justify-end gap-3 pt-4" style="--delay: 3">
            <a href="{{ route('clinic.suppliers.index') }}" class="px-8 py-4 bg-slate-50 text-slate-500 rounded-2xl font-bold hover:bg-slate-100 transition-all text-center">
                Abort Protocol
            </a>
            <button type="submit" class="px-10 py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 active:scale-95 flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Validate & Register Partner</span>
            </button>
        </div>
    </form>
</div>
@endsection


{{-- Auto-close modal script on success --}}
@if(session('success') && request()->has('iframe'))
    <script>
        setTimeout(() => {
            window.parent.location.reload();
        }, 1500);
    </script>
@endif
