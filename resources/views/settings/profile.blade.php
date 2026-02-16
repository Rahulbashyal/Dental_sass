@extends('layouts.app')

@section('title', 'My Profile - ' . config('app.name'))

@section('page-title', 'Profile Settings')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Header Section -->
    <div class="relative rounded-3xl overflow-hidden bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 h-48 sm:h-64 shadow-2xl">
        <div class="absolute inset-0 bg-black/10 backdrop-blur-[2px]"></div>
        <div class="absolute -bottom-1 w-full h-24 bg-gradient-to-t from-gray-50 to-transparent"></div>
    </div>

    <!-- Profile Summary Card -->
    <div class="relative -mt-24 sm:-mt-32 px-4 sm:px-6">
        <div class="bg-white/80 backdrop-blur-xl rounded-4xl shadow-2xl border border-white/50 p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row items-center sm:items-end gap-6">
                <!-- Avatar Section -->
                <div class="relative group">
                    <div class="w-32 h-32 sm:w-40 sm:h-40 rounded-3xl overflow-hidden ring-8 ring-white shadow-xl transition-transform duration-500 group-hover:scale-[1.02]">
                        @if($user->photo)
                            <img id="avatar-preview" src="{{ Storage::url($user->photo) }}" class="w-full h-full object-cover" alt="{{ $user->name }}">
                        @else
                            <div id="avatar-placeholder" class="w-full h-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
                                <span class="text-4xl font-black text-slate-400 opacity-50">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    <label for="photo-upload" class="absolute -bottom-2 -right-2 p-3 bg-blue-600 text-white rounded-2xl shadow-lg cursor-pointer hover:bg-blue-700 hover:scale-110 transition-all duration-300">
                        <i class="fas fa-camera text-sm"></i>
                    </label>
                </div>

                <!-- Basic Info Summary -->
                <div class="flex-1 text-center sm:text-left space-y-1">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3">
                        <h1 class="text-3xl font-black text-slate-900 tracking-tight">{{ $user->name }}</h1>
                        <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-widest rounded-full border border-blue-100">
                            {{ ucfirst($user->getRoleNames()->first() ?? 'Member') }}
                        </span>
                    </div>
                    <p class="text-slate-500 font-medium flex items-center justify-center sm:justify-start gap-2">
                        <i class="fa-solid fa-stethoscope text-blue-400"></i>
                        {{ $user->specialization ?? 'General Dentist' }}
                    </p>
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 pt-2">
                        <span class="text-xs text-slate-400 font-bold uppercase tracking-tighter flex items-center gap-1">
                            <i class="fa-solid fa-envelope opacity-50"></i> {{ $user->email }}
                        </span>
                        <span class="text-xs text-slate-400 font-bold uppercase tracking-tighter flex items-center gap-1">
                            <i class="fa-solid fa-phone opacity-50"></i> {{ $user->phone ?? 'Not set' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="px-6 animate-in slide-in-from-top-4 duration-500">
            <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                    <i class="fas fa-check"></i>
                </div>
                <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Main Content Form -->
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8 px-4 sm:px-6 pb-12">
        @csrf
        @method('PUT')
        
        <input type="file" id="photo-upload" name="photo" class="hidden" accept="image/*" onchange="previewImage(this)">

        <!-- Left Column: Settings -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Basic Information Card -->
            <div class="bg-white rounded-4xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-slate-900 tracking-tight">Basic Information</h2>
                        <p class="text-xs text-slate-400 font-medium">Your personal details and title</p>
                    </div>
                </div>
                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" placeholder="John Doe">
                            @error('name') <p class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Specialization</label>
                            <input type="text" name="specialization" value="{{ old('specialization', $user->specialization) }}" class="form-input" placeholder="e.g. Orthodontist">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" placeholder="john@example.com">
                            @error('email') <p class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input" placeholder="+977-9800000000">
                        </div>
                    </div>
                    <div class="space-y-2 pt-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Short Biography</label>
                        <textarea name="bio" rows="4" class="form-input resize-none" placeholder="Write a few lines about your professional experience...">{{ old('bio', $user->bio) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Professional Details Card -->
            <div class="bg-white rounded-4xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-slate-900 tracking-tight">Work Address</h2>
                        <p class="text-xs text-slate-400 font-medium">Where patients can find you</p>
                    </div>
                </div>
                <div class="p-8 space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Street Address</label>
                        <input type="text" name="address" value="{{ old('address', $user->address) }}" class="form-input" placeholder="123 Dental Lane">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">City</label>
                            <input type="text" name="city" value="{{ old('city', $user->city) }}" class="form-input" placeholder="Kathmandu">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">State / Province</label>
                            <input type="text" name="state" value="{{ old('state', $user->state) }}" class="form-input" placeholder="Bagmati">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Postal Code</label>
                            <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" class="form-input" placeholder="44600">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Country</label>
                            <input type="text" name="country" value="{{ old('country', $user->country ?? 'Nepal') }}" class="form-input">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Personal Website</label>
                            <input type="url" name="website" value="{{ old('website', $user->website) }}" class="form-input" placeholder="https://drjohndoe.com">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Social & Actions -->
        <div class="space-y-8">
            <!-- Social Presence Card -->
            <div class="bg-white rounded-4xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <i class="fas fa-share-alt"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-slate-900 tracking-tight">Social Presence</h2>
                        <p class="text-xs text-slate-400 font-medium">Link your professional profiles</p>
                    </div>
                </div>
                <div class="p-8 space-y-5">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-blue-600 opacity-40 group-focus-within:opacity-100 transition-opacity">
                            <i class="fab fa-facebook-f"></i>
                        </div>
                        <input type="url" name="facebook" value="{{ old('facebook', $user->social_links['facebook'] ?? '') }}" class="form-input pl-10" placeholder="Facebook Profile URL">
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-sky-400 opacity-40 group-focus-within:opacity-100 transition-opacity">
                            <i class="fab fa-twitter"></i>
                        </div>
                        <input type="url" name="twitter" value="{{ old('twitter', $user->social_links['twitter'] ?? '') }}" class="form-input pl-10" placeholder="Twitter Profile URL">
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-blue-800 opacity-40 group-focus-within:opacity-100 transition-opacity">
                            <i class="fab fa-linkedin-in"></i>
                        </div>
                        <input type="url" name="linkedin" value="{{ old('linkedin', $user->social_links['linkedin'] ?? '') }}" class="form-input pl-10" placeholder="LinkedIn Profile URL">
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-rose-500 opacity-40 group-focus-within:opacity-100 transition-opacity">
                            <i class="fab fa-instagram"></i>
                        </div>
                        <input type="url" name="instagram" value="{{ old('instagram', $user->social_links['instagram'] ?? '') }}" class="form-input pl-10" placeholder="Instagram Profile URL">
                    </div>
                </div>
            </div>

            <!-- Action Card -->
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-4xl p-8 text-center shadow-xl">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Security & Account</p>
                <div class="space-y-4">
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-4 bg-white text-slate-900 rounded-2xl font-black uppercase tracking-widest text-[11px] hover:bg-white/90 hover:scale-[1.02] active:scale-95 transition-all duration-200">
                        <i class="fas fa-save text-blue-600"></i>
                        Update My Profile
                    </button>
                    <a href="{{ route('password.edit') }}" class="w-full flex items-center justify-center gap-2 py-4 bg-slate-700/50 text-white rounded-2xl font-black uppercase tracking-widest text-[11px] hover:bg-slate-700 transition-all duration-200">
                        <i class="fas fa-key opacity-50"></i>
                        Change Password
                    </a>
                </div>
                <p class="mt-8 text-[9px] text-slate-500 font-medium">Last updated: {{ $user->updated_at->diffForHumans() }}</p>
            </div>
        </div>
    </form>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatar-preview');
                const placeholder = document.getElementById('avatar-placeholder');
                
                if (preview) {
                    preview.src = e.target.result;
                } else if (placeholder) {
                    const img = document.createElement('img');
                    img.id = 'avatar-preview';
                    img.src = e.target.result;
                    img.className = 'w-full h-full object-cover';
                    placeholder.parentNode.replaceChild(img, placeholder);
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<style>
    .rounded-4xl { border-radius: 2rem; }
    .form-input {
        @apply w-full rounded-2xl border-2 border-slate-100 bg-slate-50 px-5 py-4 text-sm text-slate-700 placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none transition-all duration-200;
    }
</style>
@endsection
