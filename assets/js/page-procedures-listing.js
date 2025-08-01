/**
 * Procedures Listing Template JavaScript
 * Handles analytics tracking for the procedures listing page
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Track procedure card clicks for analytics
    const procedureLinks = document.querySelectorAll('.procedure-card a');
    
    procedureLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const procedureCard = this.closest('.procedure-card');
            const procedureTitle = procedureCard.querySelector('.procedure-title a').textContent.trim();
            
            // Track with Google Analytics if available
            if (typeof gtag !== 'undefined') {
                gtag('event', 'procedure_click', {
                    'procedure_name': procedureTitle,
                    'page_location': window.location.href,
                    'page_template': 'procedures_listing'
                });
            }
        });
    });
    
    // Optional: Simple fade-in animation using Intersection Observer
    const cards = document.querySelectorAll('.procedure-card');
    
    if (cards.length > 0) {
        const animationObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                    animationObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });
        
        cards.forEach(card => {
            card.classList.add('animate-ready');
            animationObserver.observe(card);
        });
    }
});