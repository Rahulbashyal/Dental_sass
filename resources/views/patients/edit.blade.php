@extends(request()->has('iframe') ? 'layouts.iframe' : 'layouts.app')

@section('title', 'Edit Patient')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Patient</h1>
        <p class="text-gray-600">Update patient information</p>
    </div>

    <div class="card max-w-2xl">
        <form action="{{ route('clinic.patients.update', $patient) }}" method="POST">
    @if(request()->has('iframe'))
        <input type="hidden" name="iframe" value="1">
    @endif

            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" value="{{ $patient->first_name }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" value="{{ $patient->last_name }}" class="form-input" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ $patient->email }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ $patient->phone }}" class="form-input" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-nepali-date-input
                            name="date_of_birth"
                            label="Date of Birth (जन्म मिति)"
                            :value="old('date_of_birth', $patient->date_of_birth?->format('Y-m-d'))"
                            :maxDate="date('Y-m-d')"
                        />
                    </div>
                    <div>
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-input">
                            <option value="">Select Gender</option>
                            <option value="male" {{ $patient->gender === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $patient->gender === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ $patient->gender === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="form-label">Address</label>
                    <textarea name="address" rows="3" class="form-input">{{ $patient->address }}</textarea>
                </div>

                <div>
                    <label class="form-label">Medical History</label>
                    <textarea name="medical_history" rows="4" class="form-input" placeholder="Any allergies, medications, or medical conditions...">{{ $patient->medical_history }}</textarea>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('clinic.patients.show', $patient) }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">Update Patient</button>
                </div>
            </div>
        </form>
    </div>
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
