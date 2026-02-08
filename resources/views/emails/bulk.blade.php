@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Send Bulk Email</h1>
                <a href="{{ route('emails.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                    Back to Emails
                </a>
            </div>

            <form action="{{ route('emails.send-bulk') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Recipients</label>
                    <select name="recipient_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select recipient type</option>
                        <option value="users">All Staff Members</option>
                        <option value="patients">All Patients</option>
                        <option value="all">All Users & Patients</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <input type="text" name="subject" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea name="body" rows="10" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required></textarea>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('emails.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Send Bulk Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection