// Global Slide-in Modal System
document.addEventListener('DOMContentLoaded', function () {
    // Create modal container
    const modalContainer = document.createElement('div');
    modalContainer.id = 'global-slide-modal';
    modalContainer.innerHTML = `
        <div id="modal-backdrop" 
             class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-sm hidden"
             onclick="closeSlideModal()">
        </div>
        <div id="modal-panel" 
             class="fixed inset-y-0 right-0 z-50 w-full max-w-4xl shadow-2xl transform translate-x-full transition-transform duration-300 bg-white">
            <div class="flex flex-col h-full">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-plus text-sm"></i>
                        </div>
                        <h2 id="modal-title" class="text-lg font-bold text-slate-900">Create</h2>
                    </div>
                    <button onclick="closeSlideModal()" 
                            class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                <iframe id="modal-iframe" class="w-full h-full border-0" frameborder="0"
                allow="fullscreen; clipboard-write; web-share"
                referrerpolicy="same-origin"></iframe>
            </div>
        </div>
    `;
    document.body.appendChild(modalContainer);

    // Add click handlers to all links with data attribute AND automatic CRUD routes
    document.addEventListener('click', function (e) {
        const link = e.target.closest('a');
        if (link) {
            // First check if it has explicit modal attributes
            if (link.hasAttribute('data-modal-url')) {
                e.preventDefault();
                const url = link.getAttribute('data-modal-url');
                const title = link.getAttribute('data-modal-title') || 'Create';
                openSlideModal(url, title);
                return;
            }

            // Otherwise, automatically intercept generic create/edit RESTful paths
            const href = link.getAttribute('href');
            if (href && (href.match(/\/create(\?|$)/) || href.match(/\/edit(\?|$|\/)/) || href.match(/\/compose(\?|$)/))) {
                // Ignore standard external or self linking behavior rules
                if (link.getAttribute('target') === '_blank' || href.startsWith('#')) return;

                e.preventDefault();

                // Construct URL with iframe parameter so backend knows to use iframe layout
                let url = link.href;
                if (!url.includes('iframe=')) {
                    url += url.includes('?') ? '&iframe=1' : '?iframe=1';
                }

                // Attempt to smartly infer the panel title from link text
                let titleStr = link.textContent.trim().replace(/\s+/g, ' ');
                let title = titleStr && titleStr.length > 0 && titleStr.length < 40
                    ? titleStr
                    : (href.includes('edit') ? 'Edit Record' : 'Create Record');

                openSlideModal(url, title);
            }
        }
    });
});

function openSlideModal(url, title) {
    const backdrop = document.getElementById('modal-backdrop');
    const panel = document.getElementById('modal-panel');
    const iframe = document.getElementById('modal-iframe');
    const modalTitle = document.getElementById('modal-title');

    modalTitle.textContent = title;
    iframe.src = url;

    backdrop.classList.remove('hidden');
    panel.classList.remove('translate-x-full');

    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

function closeSlideModal() {
    const backdrop = document.getElementById('modal-backdrop');
    const panel = document.getElementById('modal-panel');
    const iframe = document.getElementById('modal-iframe');

    panel.classList.add('translate-x-full');
    backdrop.classList.add('hidden');

    // Clear iframe after transition
    setTimeout(() => {
        iframe.src = '';
    }, 300);

    // Restore body scroll
    document.body.style.overflow = '';
}

// Expose functions globally for onclick handlers
window.openSlideModal = openSlideModal;
window.closeSlideModal = closeSlideModal;

// Close on escape key
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        const backdrop = document.getElementById('modal-backdrop');
        if (backdrop && !backdrop.classList.contains('hidden')) {
            closeSlideModal();
        }
    }
});
