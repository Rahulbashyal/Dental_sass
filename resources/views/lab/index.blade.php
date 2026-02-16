@extends('layouts.app')

@section('page-title', 'Lab Orders')

@section('content')
<div class="page-fade-in space-y-8 pb-12" x-data="{ showForm: false }">

  <!-- Header -->
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div class="stagger-in">
      <h1 class="text-3xl font-black text-slate-900 tracking-tight">Lab Orders</h1>
      <p class="text-slate-500 font-medium">Track dental lab work — crowns, dentures, impressions.</p>
    </div>
    <button @click="showForm = !showForm" class="stagger-in flex items-center space-x-2 px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all" style="--delay: 1">
      <i class="fas fa-plus mr-2"></i> New Lab Order
    </button>
  </div>

    @if($overdueCount > 0)
    <div class="flex items-center space-x-4 bg-red-50 border border-red-100 rounded-2xl px-6 py-4 stagger-in" style="--delay: 2">
      <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center text-red-600">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
      <p class="text-red-800 font-bold">{{ $overdueCount }} lab order(s) are overdue and have not been received.</p>
    </div>
    @endif

  <!-- New Order Form (collapsible) -->
  <div x-show="showForm" x-cloak x-transition class="stagger-in bg-white rounded-[2.5rem] p-10 border-2 border-indigo-100 shadow-sm">
    <h3 class="text-xl font-bold text-slate-900 mb-6">New Lab Order</h3>
    <form action="{{ route('clinic.lab-orders.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
      @csrf

      <div>
        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 pl-1">Patient</label>
        <select name="patient_id" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 font-medium focus:outline-none focus:ring-2 focus:ring-indigo-600 transition-all">
          <option value="">— Select Patient —</option>
          @foreach(\App\Models\Patient::where('clinic_id', auth()->user()->clinic_id)->orderBy('first_name')->get() as $p)
          <option value="{{ $p->id }}">{{ $p->name }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 pl-1">Lab Name</label>
        <input type="text" name="lab_name" placeholder="e.g., Kathmandu Dental Lab"
          class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 font-medium focus:outline-none focus:ring-2 focus:ring-indigo-600 transition-all">
      </div>

      <div>
        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 pl-1">Category</label>
        <select name="category" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 font-medium focus:outline-none focus:ring-2 focus:ring-indigo-600 transition-all">
          @foreach(['impression' => 'Impression', 'crown' => 'Crown', 'bridge' => 'Bridge', 'denture' => 'Denture', 'bleaching_tray' => 'Bleaching Tray', 'night_guard' => 'Night Guard', 'orthodontic' => 'Orthodontic', 'other' => 'Other'] as $v => $l)
          <option value="{{ $v }}">{{ $l }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 pl-1">Shade</label>
        <input type="text" name="shade" placeholder="e.g., A2, BL1"
          class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 font-medium focus:outline-none focus:ring-2 focus:ring-indigo-600 transition-all">
      </div>

      <div>
        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 pl-1">Expected Return</label>
        <input type="date" name="expected_return_date"
          class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 font-medium focus:outline-none focus:ring-2 focus:ring-indigo-600 transition-all">
      </div>

      <div>
        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 pl-1">Lab Cost</label>
        <input type="number" name="lab_cost" step="0.01" placeholder="0.00"
          class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 font-medium focus:outline-none focus:ring-2 focus:ring-indigo-600 transition-all">
      </div>

      <div class="md:col-span-2">
        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 pl-1">Instructions to Lab</label>
        <textarea name="instructions" rows="3" required
          class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 font-medium focus:outline-none focus:ring-2 focus:ring-indigo-600 transition-all"></textarea>
      </div>

      <div class="md:col-span-2 flex space-x-4">
        <button type="submit" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all">
          <i class="fas fa-paper-plane mr-2"></i> Send to Lab
        </button>
        <button type="button" @click="showForm = false" class="px-8 py-4 bg-slate-50 text-slate-600 rounded-2xl font-bold hover:bg-slate-100 transition-all">Cancel</button>
      </div>
    </form>
  </div>

  <!-- Orders Table -->
  <div class="stagger-in bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden" style="--delay: 3">
    <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead class="bg-slate-50/50">
          <tr class="text-slate-400 text-[10px] font-black uppercase tracking-widest">
            <th class="px-8 py-4">Order #</th>
            <th class="px-8 py-4">Patient</th>
            <th class="px-8 py-4">Category</th>
            <th class="px-8 py-4">Lab</th>
            <th class="px-8 py-4">Expected</th>
            <th class="px-8 py-4 text-center">Status</th>
            <th class="px-8 py-4 text-right">Update</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
          @forelse($orders as $order)
          <tr class="hover:bg-slate-50/50 transition-colors {{ $order->isOverdue() ? 'bg-red-50/40' : '' }}">
            <td class="px-8 py-5">
              <span class="font-black text-slate-700 tracking-tight text-sm">{{ $order->order_number }}</span>
              @if($order->isOverdue())
              <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-700 rounded-full text-[9px] font-black uppercase">OVERDUE</span>
              @endif
            </td>
            <td class="px-8 py-5 font-bold text-slate-900 text-sm">{{ $order->patient->name }}</td>
            <td class="px-8 py-5">
              <span class="px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-xl text-xs font-black uppercase">{{ ucfirst(str_replace('_', ' ', $order->category)) }}</span>
            </td>
            <td class="px-8 py-5 text-slate-500 font-medium text-sm">{{ $order->lab_name ?: '—' }}</td>
            <td class="px-8 py-5 text-slate-500 font-medium text-sm">{{ $order->expected_return_date?->format('M d, Y') ?: '—' }}</td>
            <td class="px-8 py-5 text-center">
              <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase border
                @if($order->status === 'fitted') bg-emerald-50 text-emerald-700 border-emerald-100
                @elseif($order->status === 'received') bg-blue-50 text-blue-700 border-blue-100
                @elseif($order->status === 'in_progress') bg-amber-50 text-amber-700 border-amber-100
                @elseif($order->status === 'sent') bg-indigo-50 text-indigo-700 border-indigo-100
                @else bg-slate-50 text-slate-500 border-slate-100 @endif">
                {{ str_replace('_', ' ', $order->status) }}
              </span>
            </td>
            <td class="px-8 py-5 text-right">
              <form action="{{ route('clinic.lab-orders.status', $order) }}" method="POST" class="inline-flex items-center space-x-2">
                @csrf @method('PATCH')
                <select name="status" class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-700 focus:outline-none">
                  @foreach(['draft','sent','in_progress','received','fitted'] as $s)
                  <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                  @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold">Save</button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="px-8 py-16 text-center text-slate-400 italic">No lab orders yet.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
