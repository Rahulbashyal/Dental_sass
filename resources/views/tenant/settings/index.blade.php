@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-slate-800">Clinic Settings</h2>
        <p class="text-sm text-slate-500">Configure your clinic's profile, branding, and operational hours.</p>
    </div>

    @if(session('status'))
        <div class="mb-6 rounded-md bg-green-50 border border-green-100 p-4 text-sm text-green-700 shadow-sm">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile & Branding -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center bg-slate-50/50">
                    <svg class="h-5 w-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <h3 class="font-bold text-slate-900 uppercase tracking-wide text-xs">General Profile</h3>
                </div>
                <form action="{{ route('tenant.settings.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2 flex items-center space-x-6 pb-4 border-b border-slate-50">
                            <div class="shrink-0">
                                @if($clinic->logo)
                                    <img src="{{ Storage::url($clinic->logo) }}" alt="Logo" class="h-24 w-24 object-contain rounded-lg border border-slate-200 p-2">
                                @else
                                    <div class="h-24 w-24 rounded-lg bg-slate-100 border border-dashed border-slate-300 flex items-center justify-center text-slate-400">
                                        No Logo
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-slate-700">Clinic Logo</label>
                                <input type="file" name="logo" class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <p class="mt-1 text-xs text-slate-400">Recommended: Square PNG/SVG, max 2MB.</p>
                            </div>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Clinic Name</label>
                            <input type="text" name="name" value="{{ $clinic->name }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Tagline / Motto</label>
                            <input type="text" name="tagline" value="{{ $clinic->tagline }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Official Email</label>
                            <input type="email" name="email" value="{{ $clinic->email }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Contact Phone</label>
                            <input type="text" name="phone" value="{{ $clinic->phone }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Address</label>
                            <textarea name="address" rows="3" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ $clinic->address }}</textarea>
                        </div>

                        <div class="pt-4 col-span-2 border-t border-slate-50 grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Primary Branding Color</label>
                                <div class="mt-1 flex items-center space-x-2">
                                    <input type="color" name="primary_color" value="{{ $clinic->primary_color ?? '#4f46e5' }}" class="h-8 w-8 rounded border-0 p-0 cursor-pointer">
                                    <input type="text" value="{{ $clinic->primary_color ?? '#4f46e5' }}" readonly class="text-xs font-mono text-slate-500 bg-slate-50 border-0">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Timezone</label>
                                <select name="timezone" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="UTC" {{ $clinic->timezone == 'UTC' ? 'selected' : '' }}>UTC</option>
                                    <option value="Asia/Kathmandu" {{ $clinic->timezone == 'Asia/Kathmandu' ? 'selected' : '' }}>Nepal (GMT+5:45)</option>
                                    <!-- Add more as needed -->
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar Actions/Info -->
        <div class="space-y-8">
            <div class="bg-indigo-600 rounded-xl shadow-lg p-6 text-white overflow-hidden relative">
                <div class="relative z-10">
                    <h4 class="text-lg font-bold mb-2">Operational Hours</h4>
                    <p class="text-indigo-100 text-sm mb-4">Set your clinic's business hours to manage appointment scheduling constraints.</p>
                    <button type="button" onclick="openHoursModal()" class="w-full bg-white text-indigo-600 font-bold py-2 rounded-lg hover:bg-indigo-50 transition-colors">
                        Edit Business Hours
                    </button>
                </div>
                <!-- Decorative SVG -->
                <svg class="absolute -right-4 -bottom-4 h-32 w-32 text-indigo-500 opacity-20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8zm1-13h-2v6l5.25 3.15.75-1.23-4-2.39V7z"/>
                </svg>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h4 class="font-bold text-slate-900 text-xs uppercase tracking-widest mb-4">Subscription Plan</h4>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm text-slate-600">Current Plan</span>
                    <span class="px-2 py-1 bg-amber-100 text-amber-800 text-[10px] font-bold rounded uppercase">{{ $clinic->subscription_plan ?? 'Standard' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600">Status</span>
                    <span class="text-sm font-semibold text-green-600">Active</span>
                </div>
                <hr class="my-4 border-slate-100">
                <a href="{{ route('subscriptions.current') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center">
                    Manage Subscription
                    <svg class="h-3 w-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <!-- Module Hub -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <h4 class="font-bold text-slate-900 text-xs uppercase tracking-widest">Module Hub</h4>
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                </div>
                <div class="p-6 space-y-4">
                    @php
                        $modules = [
                            ['key' => 'has_inventory', 'label' => 'Inventory System', 'icon' => 'fa-boxes-stacked'],
                            ['key' => 'has_accounting', 'label' => 'Advanced Finance', 'icon' => 'fa-file-invoice-dollar'],
                            ['key' => 'has_lab_integration', 'label' => 'Lab Management', 'icon' => 'fa-microscope'],
                            ['key' => 'has_telemedicine', 'label' => 'Tele-Healthcare', 'icon' => 'fa-video'],
                        ];
                    @endphp
                    @foreach($modules as $mod)
                        <div class="flex items-center justify-between p-3 rounded-xl border {{ $clinic->{$mod['key']} ? 'border-blue-100 bg-blue-50/30' : 'border-slate-50 bg-slate-50/10' }}">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $clinic->{$mod['key']} ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-400' }}">
                                    <i class="fas {{ $mod['icon'] }} text-xs"></i>
                                </div>
                                <span class="text-xs font-bold {{ $clinic->{$mod['key']} ? 'text-slate-900' : 'text-slate-400' }}">{{ $mod['label'] }}</span>
                            </div>
                            @if($clinic->{$mod['key']})
                                <span class="text-[9px] font-black text-blue-600 uppercase tracking-tighter">Active</span>
                            @else
                                <button class="text-[9px] font-black text-slate-400 uppercase tracking-tighter hover:text-blue-600 transition-colors">Activate</button>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="px-6 py-4 bg-slate-50/50 text-center border-t border-slate-100">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">Usage-based billing applies</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Business Hours Modal -->
<div id="hours-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeHoursModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full sm:p-6">
            <form action="{{ route('tenant.settings.hours') }}" method="POST">
                @csrf
                @method('PUT')
                <div>
                    <h3 class="text-xl leading-6 font-bold text-slate-900 mb-6">Business Hours</h3>
                    <div class="space-y-3">
                        @php
                            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                            $hours = $clinic->business_hours ?? [];
                        @endphp
                        @foreach($days as $day)
                            <div class="grid grid-cols-4 gap-4 items-center p-3 rounded-lg border border-slate-50 {{ in_array($day, ['saturday', 'sunday']) ? 'bg-slate-50' : '' }}">
                                <div class="text-sm font-bold text-slate-700 capitalize">{{ $day }}</div>
                                <div class="col-span-2 flex items-center space-x-2">
                                    <input type="time" name="business_hours[{{ $day }}][open]" value="{{ $hours[$day]['open'] ?? '09:00' }}" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-xs">
                                    <span class="text-slate-400">-</span>
                                    <input type="time" name="business_hours[{{ $day }}][close]" value="{{ $hours[$day]['close'] ?? '18:00' }}" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-xs">
                                </div>
                                <div class="flex justify-end">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" name="business_hours[{{ $day }}][closed]" value="1" {{ isset($hours[$day]['closed']) && $hours[$day]['closed'] ? 'checked' : '' }} class="rounded border-slate-300 text-red-600 focus:ring-red-500 h-4 w-4">
                                        <span class="ml-2 text-xs text-slate-500">Closed</span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="mt-8 flex space-x-3">
                    <button type="submit" class="flex-1 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                        Save Hours
                    </button>
                    <button type="button" onclick="closeHoursModal()" class="flex-1 inline-flex justify-center rounded-md border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openHoursModal() {
    document.getElementById('hours-modal').classList.remove('hidden');
}
function closeHoursModal() {
    document.getElementById('hours-modal').classList.add('hidden');
}
</script>
@endsection
