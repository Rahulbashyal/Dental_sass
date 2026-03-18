@extends(request()->has('iframe') ? 'layouts.iframe' : 'layouts.app')

@section('page-title', 'Clinical Intelligence: Imaging')

@section('content')
<div class="page-fade-in max-w-4xl mx-auto pb-12">
    <!-- Premium Header -->
    <div class="stagger-in mb-8">
        <div class="flex items-center space-x-3 text-slate-500 text-sm mb-2">
            <a href="{{ route('clinic.radiology.index') }}" class="hover:text-blue-600 transition-colors">Radiology Ledger</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-slate-900 font-medium tracking-tight">New Diagnostic Study</span>
        </div>
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-100">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Image Acquisition</h1>
                <p class="text-slate-500 font-medium italic">Commissioning a new Diagnostic Imaging entry</p>
            </div>
        </div>
    </div>

    <form action="{{ route('clinic.radiology.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @if(request()->has('iframe'))
            <input type="hidden" name="iframe" value="1">
        @endif
        @csrf
        
        <!-- Clinical Context Section -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 1">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Acquisition Context</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                <div class="space-y-2 md:col-span-2">
                    <label for="patient_id" class="block font-bold text-slate-700 tracking-tight">Clinical Subject <span class="text-blue-500">*</span></label>
                    <select name="patient_id" id="patient_id" required 
                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700 appearance-none">
                        <option value="">Select Target Subject</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                {{ $patient->name }} (#PAT-{{ str_pad($patient->id, 5, '0', STR_PAD_LEFT) }})
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="type" class="block font-bold text-slate-700 tracking-tight">Modality Protocol <span class="text-blue-500">*</span></label>
                    <select name="type" id="type" required 
                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700 appearance-none">
                        <option value="">Choose Modality...</option>
                        @foreach(['x_ray' => 'X-Ray (Standard)', 'cbct' => 'CBCT Scan (3D)', 'panoramic' => 'Panoramic OPG', 'periapical' => 'Periapical', 'bitewing' => 'Bitewing', 'cephalometric' => 'Cephalometric', 'intraoral' => 'Intraoral Camera'] as $val => $label)
                            <option value="{{ $val }}" {{ old('type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="study_date" class="block font-bold text-slate-700 tracking-tight">Acquisition Date <span class="text-blue-500">*</span></label>
                    <input type="date" name="study_date" id="study_date" value="{{ old('study_date', date('Y-m-d')) }}" required
                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700">
                </div>
            </div>
        </div>

        <!-- Localization & Indication -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 2">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Localization Intel</h2>
            </div>
            
            <div class="space-y-6 text-sm">
                <div class="space-y-2">
                    <label for="tooth_area" class="block font-bold text-slate-700 tracking-tight">Anatomical Target</label>
                    <input type="text" name="tooth_area" id="tooth_area" value="{{ old('tooth_area') }}" placeholder="e.g. Upper Left Quadrant | Tooth #18"
                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700 placeholder-slate-400">
                </div>

                <div class="space-y-2">
                    <label for="clinical_indication" class="block font-bold text-slate-700 tracking-tight">Diagnostic Indication</label>
                    <textarea name="clinical_indication" id="clinical_indication" rows="3" placeholder="Reason for imaging intervention..."
                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700 placeholder-slate-400 resize-none">{{ old('clinical_indication') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Material Asset Hub -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 3">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Digital Asset Hub</h2>
            </div>
            
            <div class="border-2 border-dashed border-slate-200 rounded-[2rem] p-10 text-center hover:border-blue-300 transition-all bg-slate-50/50 group">
                <input type="file" name="images[]" multiple accept="image/*,.dcm" class="hidden" id="imageUpload" onchange="document.getElementById('fileStatus').textContent = this.files.length + ' assets selected for upload'">
                <label for="imageUpload" class="cursor-pointer">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-sm mx-auto mb-4 group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    </div>
                    <p class="text-slate-900 font-black text-lg">Inject Diagnostic Imagery</p>
                    <p class="text-slate-400 text-sm font-medium mt-1">DICOM, PNG, or high-fidelity JPG supported</p>
                    <div id="fileStatus" class="mt-4 px-6 py-2 bg-blue-600 inline-block text-white rounded-xl text-xs font-black uppercase tracking-widest empty:hidden"></div>
                </label>
            </div>
        </div>

        <!-- Logistics Fulfillment -->
        <div class="stagger-in flex flex-col md:flex-row md:items-center justify-end gap-3 pt-4" style="--delay: 4">
            <a href="{{ route('clinic.radiology.index') }}" class="px-8 py-4 bg-slate-50 text-slate-500 rounded-2xl font-bold hover:bg-slate-100 transition-all text-center">
                Abort Acquisition
            </a>
            <button type="submit" class="px-10 py-4 bg-blue-600 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 active:scale-95 flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                <span>Commit Diagnostic Study</span>
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
