@props([
    'title' => '',
    'size' => 'md', // sm, md, lg, xl
    'id' => 'slide-modal'
])

@php
    $sizeClasses = [
        'sm' => 'max-w-md',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
    ];
    $widthClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<div x-data="{ 
    open: false,
    modalTitle: '{{ $title }}'
}"
     @if(isset($id) && $id)
     id="{{ $id }}-controller"
     @endif
     x-on:open-modal.window="if ($event.detail.title === modalTitle || !$event.detail.title) { open = true }"
     x-on:close-modal.window="open = false"
     x-on:keydown.escape.window="open = false">

    <!-- Backdrop -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="open = false"
         class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-sm"
         x-cloak>
    </div>

    <!-- Slide-in Panel -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed inset-y-0 right-0 z-50 w-full {{ $widthClass }} shadow-2xl"
         x-cloak>
        
        <div class="h-full flex flex-col bg-white/95 backdrop-blur-xl border-l border-slate-200 shadow-2xl">
            <!-- Header -->
            <div class="flex items-center justify-between px-8 py-6 border-b border-slate-100 bg-white/50">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-dental-blue-500 to-dental-blue-600 flex items-center justify-center text-white shadow-lg shadow-dental-blue-500/30">
                        <i class="fas fa-plus text-sm"></i>
                    </div>
                    <h2 x-text="modalTitle" class="text-xl font-bold text-slate-900">{{ $title }}</h2>
                </div>
                <button @click="open = false" 
                        class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-8">
                {{ $slot }}
            </div>

            <!-- Footer -->
            @if(isset($footer))
            <div class="px-8 py-6 border-t border-slate-100 bg-slate-50/50">
                {{ $footer }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('slideModal', (title = '') => ({
        open: false,
        modalTitle: title,
        
        openModal() {
            this.open = true;
        },
        
        closeModal() {
            this.open = false;
        }
    }));
});
</script>
