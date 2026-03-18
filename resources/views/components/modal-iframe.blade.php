<div x-data="{
    open: false,
    url: '',
    title: '',
    openModal(event) {
        this.url = event.detail.url;
        this.title = event.detail.title || 'Create';
        this.open = true;
    },
    closeModal() {
        this.open = false;
        this.url = '';
    }
}"
@open-modal.window="openModal($event)"
x-show="open"
x-cloak
class="fixed inset-0 z-50">

    <!-- Backdrop -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="closeModal()"
         class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm">
    </div>

    <!-- Slide-in Panel -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="absolute inset-y-0 right-0 w-full max-w-4xl shadow-2xl flex flex-col bg-white">
        
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-dental-blue-500 to-dental-blue-600 flex items-center justify-center text-white shadow-lg">
                    <i class="fas fa-plus text-sm"></i>
                </div>
                <h2 x-text="title" class="text-lg font-bold text-slate-900"></h2>
            </div>
            <button @click="closeModal()" 
                    class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <!-- Content - Iframe -->
        <iframe x-show="url"
                :src="url"
                class="w-full h-full border-0"
                frameborder="0">
        </iframe>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('modalIframe', (url = '', title = 'Create') => ({
        open: false,
        url: url,
        title: title,
        
        openModal() {
            this.open = true;
        },
        
        closeModal() {
            this.open = false;
        }
    }));
});
</script>
