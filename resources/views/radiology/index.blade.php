@extends('layouts.app')

@section('page-title', 'Radiology & Imaging')

@section('content')
<div class="page-fade-in space-y-8 pb-12">

  <!-- Header -->
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div class="stagger-in">
      <h1 class="text-3xl font-black text-slate-900 tracking-tight">Radiology & Imaging</h1>
      <p class="text-slate-500 font-medium">Diagnostic imaging studies and X-ray archive.</p>
    </div>
    <a href="{{ route('clinic.radiology.create') }}" class="stagger-in flex items-center space-x-2 px-6 py-3 bg-blue-600 text-white rounded-2xl font-bold shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all" style="--delay: 1">
      <i class="fas fa-radiation mr-2"></i> New Imaging Study
    </a>
  </div>

  <!-- Stats Bar -->
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 stagger-in" style="--delay: 2">
    @foreach([
      ['label' => 'Total Studies', 'value' => $studies->total(), 'color' => 'blue', 'icon' => 'fa-film'],
      ['label' => 'Ordered', 'value' => $studies->where('status','ordered')->count(), 'color' => 'amber', 'icon' => 'fa-clock'],
      ['label' => 'Reported', 'value' => $studies->where('status','reported')->count(), 'color' => 'indigo', 'icon' => 'fa-file-medical-alt'],
      ['label' => 'Reviewed', 'value' => $studies->where('status','reviewed')->count(), 'color' => 'emerald', 'icon' => 'fa-check-circle'],
    ] as $stat)
    <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm flex items-center justify-between">
      <div>
        <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $stat['label'] }}</span>
        <span class="text-3xl font-black text-slate-900">{{ $stat['value'] }}</span>
      </div>
      <div class="w-12 h-12 bg-{{ $stat['color'] }}-50 rounded-2xl flex items-center justify-center text-{{ $stat['color'] }}-600">
        <i class="fas {{ $stat['icon'] }} text-lg"></i>
      </div>
    </div>
    @endforeach
  </div>

  <!-- Studies Table -->
  <div class="stagger-in bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden" style="--delay: 3">
    <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/30">
      <h3 class="text-lg font-bold text-slate-900">All Imaging Studies</h3>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead class="bg-slate-50/50">
          <tr class="text-slate-400 text-[10px] font-black uppercase tracking-widest">
            <th class="px-8 py-4">Patient</th>
            <th class="px-8 py-4">Study Type</th>
            <th class="px-8 py-4">Area</th>
            <th class="px-8 py-4">Study Date</th>
            <th class="px-8 py-4">Ordered By</th>
            <th class="px-8 py-4 text-center">Status</th>
            <th class="px-8 py-4 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
          @forelse($studies as $study)
          <tr class="hover:bg-slate-50/50 transition-colors">
            <td class="px-8 py-5">
              <div class="font-bold text-slate-900">{{ $study->patient->name }}</div>
              <div class="text-[10px] text-slate-400 font-bold uppercase">#PAT-{{ str_pad($study->patient_id, 5, '0', STR_PAD_LEFT) }}</div>
            </td>
            <td class="px-8 py-5">
              <span class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-xl text-xs font-black uppercase">{{ $study->getTypeLabel() }}</span>
            </td>
            <td class="px-8 py-5 text-slate-500 font-medium text-sm">{{ $study->tooth_area ?: '—' }}</td>
            <td class="px-8 py-5 text-slate-700 font-bold text-sm">{{ $study->study_date?->format('M d, Y') ?: '—' }}</td>
            <td class="px-8 py-5 text-slate-500 font-medium text-sm">{{ $study->dentist->name }}</td>
            <td class="px-8 py-5 text-center">
              <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase border
                @if($study->status === 'reviewed') bg-emerald-50 text-emerald-700 border-emerald-100
                @elseif($study->status === 'reported') bg-indigo-50 text-indigo-700 border-indigo-100
                @elseif($study->status === 'captured') bg-blue-50 text-blue-700 border-blue-100
                @else bg-amber-50 text-amber-700 border-amber-100 @endif">
                {{ $study->status }}
              </span>
            </td>
            <td class="px-8 py-5 text-right">
              <a href="{{ route('clinic.radiology.show', $study) }}" class="inline-flex items-center px-4 py-2 bg-slate-50 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-100 transition-colors">
                <i class="fas fa-eye mr-2"></i> View
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="px-8 py-16 text-center">
              <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-4 text-slate-200">
                <i class="fas fa-x-ray text-4xl"></i>
              </div>
              <p class="font-bold text-slate-900 mb-1">No imaging studies yet</p>
              <p class="text-slate-400 text-sm">Create the first imaging study to get started.</p>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($studies->hasPages())
    <div class="px-8 py-6 border-t border-slate-50">{{ $studies->links() }}</div>
    @endif
  </div>
</div>
@endsection
