@extends('layouts.app')

@section('page-title', 'Imaging Study')

@section('content')
<div class="page-fade-in space-y-8 pb-12">

  <!-- Breadcrumb + Actions -->
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div class="stagger-in">
      <div class="flex items-center space-x-2 text-slate-400 text-sm mb-2">
        <a href="{{ route('clinic.radiology.index') }}" class="hover:text-blue-600">Radiology</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-slate-700 font-bold">Study #{{ $study->id }}</span>
      </div>
      <h1 class="text-3xl font-black text-slate-900">{{ $study->getTypeLabel() }}</h1>
      <p class="text-slate-500 font-medium">{{ $study->patient->name }} · {{ $study->study_date?->format('M d, Y') }}</p>
    </div>
    <div class="flex items-center space-x-3 stagger-in" style="--delay: 1">
      <form action="{{ route('clinic.radiology.findings', $study) }}" method="POST" class="flex items-center space-x-2">
        @csrf @method('PATCH')
        <select name="status" class="bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-600">
          @foreach(['ordered','captured','reported','reviewed'] as $s)
          <option value="{{ $s }}" {{ $study->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
          @endforeach
        </select>
        <button type="submit" class="px-4 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-bold shadow-md shadow-blue-100">Update</button>
      </form>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left: Study Meta -->
    <div class="space-y-6">
      <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 2">
        <div class="flex items-center justify-between mb-6">
          <h3 class="font-bold text-slate-900">Study Details</h3>
          <span class="px-3 py-1 bg-blue-50 text-blue-600 text-xs font-black uppercase rounded-full">{{ $study->getTypeLabel() }}</span>
        </div>
        <div class="space-y-4">
          <div>
            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Patient</span>
            <a href="{{ route('clinic.patients.show', $study->patient) }}" class="font-bold text-blue-600 hover:underline">{{ $study->patient->name }}</a>
          </div>
          <div>
            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Ordered By</span>
            <span class="font-bold text-slate-700">{{ $study->dentist->name }}</span>
          </div>
          <div>
            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tooth / Area</span>
            <span class="font-bold text-slate-700">{{ $study->tooth_area ?: '—' }}</span>
          </div>
          <div>
            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Clinical Indication</span>
            <p class="text-slate-600 text-sm leading-relaxed">{{ $study->clinical_indication ?: 'Not specified.' }}</p>
          </div>
        </div>
      </div>

      <!-- Findings Form -->
      <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 3">
        <h3 class="font-bold text-slate-900 mb-6">Radiologist Report</h3>
        <form action="{{ route('clinic.radiology.findings', $study) }}" method="POST" class="space-y-4">
          @csrf @method('PATCH')
          <input type="hidden" name="status" value="{{ $study->status }}">
          <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Findings</label>
            <textarea name="findings" rows="4" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-600 transition-all">{{ $study->findings }}</textarea>
          </div>
          <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Additional Notes</label>
            <textarea name="radiologist_notes" rows="3" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-600 transition-all">{{ $study->radiologist_notes }}</textarea>
          </div>
          <button type="submit" class="w-full bg-indigo-600 text-white rounded-2xl py-3 font-bold shadow-md shadow-indigo-100 hover:bg-indigo-700 transition-all">Save Report</button>
        </form>
      </div>
    </div>

    <!-- Right: Image Viewer -->
    <div class="lg:col-span-2 stagger-in" style="--delay: 4">
      <div class="bg-slate-900 rounded-[2.5rem] p-2 border border-slate-800 shadow-2xl min-h-[500px] flex flex-col">
        <div class="flex items-center justify-between px-6 py-4">
          <div class="flex items-center space-x-3">
            <div class="w-3 h-3 rounded-full bg-red-500"></div>
            <div class="w-3 h-3 rounded-full bg-amber-500"></div>
            <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
          </div>
          <span class="text-slate-400 text-xs font-bold uppercase tracking-widest">Imaging Viewer</span>
          <span class="text-slate-600 text-xs">{{ $study->files->count() }} image(s)</span>
        </div>

        @if($study->files->count() > 0)
        <div x-data="{ active: 0 }" class="flex-grow p-4">
          <!-- Main Viewer -->
          <div class="relative bg-black rounded-2xl overflow-hidden mb-4" style="min-height: 350px;">
            @foreach($study->files as $i => $file)
            @if($file->isImage())
            <img x-show="active === {{ $i }}" x-cloak
                 src="{{ asset('storage/' . $file->file_path) }}"
                 alt="{{ $file->file_name }}"
                 class="w-full h-full object-contain" style="max-height: 400px;">
            @else
            <div x-show="active === {{ $i }}" x-cloak
                 class="flex flex-col items-center justify-center h-64 text-slate-400">
              <i class="fas fa-file text-6xl mb-4"></i>
              <p class="font-bold">{{ $file->file_name }}</p>
              <p class="text-xs mt-1">{{ $file->getFileSizeHuman() }}</p>
            </div>
            @endif
            @endforeach
          </div>

          <!-- Thumbnails -->
          <div class="flex space-x-2 overflow-x-auto pb-2">
            @foreach($study->files as $i => $file)
            <button @click="active = {{ $i }}"
                    :class="active === {{ $i }} ? 'ring-2 ring-blue-500' : 'ring-1 ring-slate-700'"
                    class="flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden bg-slate-800 flex items-center justify-center">
              @if($file->isImage())
              <img src="{{ asset('storage/' . $file->file_path) }}" class="w-full h-full object-cover">
              @else
              <i class="fas fa-file text-slate-400"></i>
              @endif
            </button>
            @endforeach
          </div>
        </div>
        @else
        <div class="flex-grow flex flex-col items-center justify-center text-slate-600 space-y-4">
          <i class="fas fa-x-ray text-6xl opacity-20"></i>
          <p class="font-bold text-center">No images uploaded for this study.</p>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
