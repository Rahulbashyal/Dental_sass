@extends('layouts.app')

@section('page-title', 'New Imaging Study')

@section('content')
<div class="max-w-3xl mx-auto space-y-8 pb-12">
  <div class="stagger-in">
    <div class="flex items-center space-x-3 text-slate-500 text-sm mb-4">
      <a href="{{ route('clinic.radiology.index') }}" class="hover:text-blue-600">Radiology</a>
      <i class="fas fa-chevron-right text-xs"></i>
      <span class="text-slate-900 font-medium">New Study</span>
    </div>
    <h1 class="text-3xl font-black text-slate-900">Order Imaging Study</h1>
    <p class="text-slate-500">Record a new dental imaging or radiology order.</p>
  </div>

  <div class="stagger-in bg-white rounded-[2.5rem] p-10 border border-slate-100 shadow-sm" style="--delay: 1">
    <form action="{{ route('clinic.radiology.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
      @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Patient -->
        <div class="md:col-span-2">
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 pl-1">Patient</label>
          <select name="patient_id" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all">
            <option value="">— Select Patient —</option>
            @foreach($patients as $patient)
            <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
              {{ $patient->name }} (#PAT-{{ str_pad($patient->id, 5, '0', STR_PAD_LEFT) }})
            </option>
            @endforeach
          </select>
        </div>

        <!-- Type -->
        <div>
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 pl-1">Study Type</label>
          <select name="type" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all">
            <option value="">— Choose Type —</option>
            @foreach(['x_ray' => 'X-Ray', 'cbct' => 'CBCT Scan', 'panoramic' => 'Panoramic OPG', 'periapical' => 'Periapical', 'bitewing' => 'Bitewing', 'cephalometric' => 'Cephalometric', 'intraoral' => 'Intraoral Camera'] as $val => $label)
            <option value="{{ $val }}" {{ old('type') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </div>

        <!-- Study Date -->
        <div>
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 pl-1">Study Date</label>
          <input type="date" name="study_date" value="{{ old('study_date', date('Y-m-d')) }}" required
            class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all">
        </div>

        <!-- Tooth Area -->
        <div class="md:col-span-2">
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 pl-1">Tooth / Area</label>
          <input type="text" name="tooth_area" value="{{ old('tooth_area') }}" placeholder="e.g., Upper Left Quadrant, Tooth #18"
            class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all">
        </div>

        <!-- Clinical Indication -->
        <div class="md:col-span-2">
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 pl-1">Clinical Indication</label>
          <textarea name="clinical_indication" rows="3" placeholder="Reason for imaging..."
            class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all">{{ old('clinical_indication') }}</textarea>
        </div>

        <!-- Image Upload -->
        <div class="md:col-span-2">
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 pl-1">Upload Images</label>
          <div class="border-2 border-dashed border-slate-200 rounded-2xl p-8 text-center hover:border-blue-300 transition-colors bg-slate-50/50">
            <i class="fas fa-cloud-upload-alt text-4xl text-slate-300 mb-3"></i>
            <p class="font-bold text-slate-600 mb-1">Drop imaging files here or click to upload</p>
            <p class="text-xs text-slate-400">JPG, PNG, DICOM supported · Max 20MB each</p>
            <input type="file" name="images[]" multiple accept="image/*,.dcm" class="hidden" id="imageUpload"
              onchange="document.getElementById('filePreview').textContent = this.files.length + ' file(s) selected'">
            <label for="imageUpload" class="inline-block mt-4 px-6 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl font-bold text-sm cursor-pointer hover:bg-slate-50 transition-all">Browse Files</label>
            <p id="filePreview" class="text-xs text-blue-600 font-bold mt-2"></p>
          </div>
        </div>
      </div>

      <div class="pt-4 flex items-center space-x-4">
        <button type="submit" class="px-8 py-4 bg-blue-600 text-white rounded-2xl font-bold shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all">
          <i class="fas fa-save mr-2"></i> Save Imaging Study
        </button>
        <a href="{{ route('clinic.radiology.index') }}" class="px-8 py-4 bg-slate-50 text-slate-600 rounded-2xl font-bold hover:bg-slate-100 transition-all">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
