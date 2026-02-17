@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">Landing Page CMS</h1>
            <p class="text-gray-600 mt-2">Manage all content on the public landing page</p>
        </div>
        <div class="flex items-center space-x-3">
            <button class="btn-secondary">Preview Changes</button>
            <button class="btn-primary">Publish Updates</button>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="border-b border-gray-200 bg-gray-50">
            <nav class="flex space-x-8 px-6">
                <button class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Hero Section</button>
                <button class="border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Statistics</button>
                <button class="border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Pricing</button>
            </nav>
        </div>

        <div class="p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Hero Section Content</h2>
            <div class="grid lg:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Main Headline</label>
                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="Nepal's Most Advanced Dental Platform">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
                        <textarea class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" rows="3">Transform your dental clinic with our comprehensive management solution. Built by ABS Soft specifically for Nepal's healthcare industry.</textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Primary Button</label>
                            <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="🚀 Start Free Trial">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Button</label>
                            <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="📺 Watch Demo">
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                    <h3 class="font-semibold text-gray-900 mb-4">Live Preview</h3>
                    <div class="space-y-4">
                        <h1 class="text-2xl font-bold text-gray-900">Nepal's Most Advanced Dental Platform</h1>
                        <p class="text-gray-600">Transform your dental clinic with our comprehensive management solution.</p>
                        <div class="flex space-x-3">
                            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">🚀 Start Free Trial</button>
                            <button class="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm">📺 Watch Demo</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end space-x-4">
        <button class="btn-secondary">Save as Draft</button>
        <button class="btn-primary">Save & Publish</button>
    </div>
</div>
@endsection