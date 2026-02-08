@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Testimonials</h1>
            <p class="text-gray-600">Manage customer testimonials</p>
        </div>
        <button onclick="openTestimonialModal()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
            Add Testimonial
        </button>
    </div>

    <!-- Add Testimonial Modal -->
    <div id="testimonialModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 backdrop-blur-sm">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-lg transform transition-all duration-300 scale-95 hover:scale-100">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Add New Testimonial</h3>
                </div>
                <form method="POST" action="{{ route('superadmin.content.testimonials.store') }}">
                    @csrf
                    <div class="space-y-5">
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Doctor Name
                            </label>
                            <input type="text" name="doctor_name" placeholder="Dr. John Smith" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200" required>
                        </div>
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Clinic Name
                            </label>
                            <input type="text" name="clinic_name" placeholder="Smile Dental Clinic" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200" required>
                        </div>
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                Testimonial
                            </label>
                            <textarea name="testimonial" rows="4" placeholder="Share your experience with our platform..." class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200 resize-none" required></textarea>
                        </div>
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                                Rating
                            </label>
                            <select name="rating" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all duration-200">
                                <option value="5">⭐⭐⭐⭐⭐ 5 Stars - Excellent</option>
                                <option value="4">⭐⭐⭐⭐ 4 Stars - Very Good</option>
                                <option value="3">⭐⭐⭐ 3 Stars - Good</option>
                                <option value="2">⭐⭐ 2 Stars - Fair</option>
                                <option value="1">⭐ 1 Star - Poor</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end space-x-4">
                        <button type="button" onclick="closeTestimonialModal()" class="px-6 py-3 border-2 border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-medium flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-white rounded-xl hover:from-yellow-500 hover:to-orange-600 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Testimonial
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                    DR
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-gray-900">Dr. Rajesh Sharma</h3>
                    <p class="text-xs text-gray-500">Smile Dental Clinic</p>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-4">
                "This software has revolutionized how we manage our dental practice. The appointment system is fantastic!"
            </p>
            <div class="flex justify-between items-center">
                <div class="flex text-yellow-400">
                    ★★★★★
                </div>
                <div class="space-x-2">
                    <button class="text-blue-600 hover:text-blue-900 text-xs">Edit</button>
                    <button class="text-red-600 hover:text-red-900 text-xs">Delete</button>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white font-bold">
                    SP
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-gray-900">Dr. Sita Patel</h3>
                    <p class="text-xs text-gray-500">Care Dental Center</p>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-4">
                "Excellent patient management system. Our efficiency has increased by 40% since using this platform."
            </p>
            <div class="flex justify-between items-center">
                <div class="flex text-yellow-400">
                    ★★★★★
                </div>
                <div class="space-x-2">
                    <button class="text-blue-600 hover:text-blue-900 text-xs">Edit</button>
                    <button class="text-red-600 hover:text-red-900 text-xs">Delete</button>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                    AK
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-gray-900">Dr. Anil KC</h3>
                    <p class="text-xs text-gray-500">Modern Dental Clinic</p>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-4">
                "The billing and invoice features are amazing. Everything is automated and professional."
            </p>
            <div class="flex justify-between items-center">
                <div class="flex text-yellow-400">
                    ★★★★☆
                </div>
                <div class="space-x-2">
                    <button class="text-blue-600 hover:text-blue-900 text-xs">Edit</button>
                    <button class="text-red-600 hover:text-red-900 text-xs">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openTestimonialModal() {
    document.getElementById('testimonialModal').classList.remove('hidden');
}

function closeTestimonialModal() {
    document.getElementById('testimonialModal').classList.add('hidden');
}

function editTestimonial(id) {
    alert('Edit testimonial ' + id + ' - Feature coming soon!');
}

function deleteTestimonial(id) {
    if(confirm('Are you sure you want to delete this testimonial?')) {
        alert('Delete testimonial ' + id + ' - Feature coming soon!');
    }
}
</script>
@endsection