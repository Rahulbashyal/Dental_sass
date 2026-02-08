import './bootstrap';
import './nepali-datepicker';

// Add any custom JavaScript for the dental care platform
document.addEventListener('DOMContentLoaded', function () {
    // Initialize any interactive components
    console.log('Dental Care SaaS Platform loaded');

    // Add smooth scrolling for anchor links with XSS protection
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            // Validate href to prevent XSS
            if (href && /^#[a-zA-Z0-9_-]+$/.test(href)) {
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }
        });
    });

    //Nepali datepicker is auto-initialized via nepali-datepicker.js
    console.log('Nepali Calendar System loaded');
});