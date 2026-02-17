/**
 * Nepali Date Picker - Using Nepali Calendar BS Picker
 * CDN-based solution for pure BS date selection
 */

document.addEventListener('DOMContentLoaded', function () {
    initializeNepaliDateInputs();
});

function initializeNepaliDateInputs() {
    const containers = document.querySelectorAll('[data-nepali-date]');

    containers.forEach(container => {
        const displayInput = container.querySelector('.nepali-date-display');
        const hiddenInput = container.querySelector('.nepali-date-value');

        if (!displayInput || !hiddenInput) return;

        // Initialize flatpickr-based Nepali calendar
        if (typeof nepaliDatePicker !== 'undefined') {
            const picker = nepaliDatePicker(displayInput, {
                dateFormat: "%D, %M %d, %y",
                closeOnDateSelect: true,
                ndpEnglishInput: hiddenInput.id,
                onChange: function () {
                    // The library automatically updates the hidden AD input
                    console.log('Date selected:', hiddenInput.value);
                }
            });

            // Pre-fill if there's an existing value
            if (hiddenInput.value) {
                // Convert AD to BS and display
                const adDate = new Date(hiddenInput.value);
                // The library will handle this
            }
        } else {
            // Fallback to native date picker
            console.warn('Nepali Date Picker library not loaded, falling back to native');
            setupFallbackPicker(displayInput, hiddenInput);
        }
    });
}

function setupFallbackPicker(displayInput, hiddenInput) {
    // Fallback: show native date picker
    displayInput.type = 'date';
    displayInput.value = hiddenInput.value;

    displayInput.addEventListener('change', function () {
        hiddenInput.value = this.value;
    });
}

// Export for dynamic initialization
window.reinitNepaliDateInputs = initializeNepaliDateInputs;
