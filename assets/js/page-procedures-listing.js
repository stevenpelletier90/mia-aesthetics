/**
 * Procedures Listing Template JavaScript
 * Handles interactions and enhancements for the procedures listing page
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize procedures listing functionality
    initProceduresListing();
    
    /**
     * Initialize procedures listing interactions
     */
    function initProceduresListing() {
        // Add fade-in animation for procedure cards
        addCardAnimations();
        
        // Handle procedure card clicks for analytics
        trackProcedureClicks();
        
        // Lazy load images if needed
        lazyLoadImages();
    }
    
    /**
     * Add fade-in animations to procedure cards
     */
    function addCardAnimations() {
        const cards = document.querySelectorAll('.procedure-card');
        
        // Create intersection observer for animation on scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 100); // Stagger animation
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });
        
        // Set initial state and observe cards
        cards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    }
    
    /**
     * Track procedure card clicks for analytics
     */
    function trackProcedureClicks() {
        const procedureLinks = document.querySelectorAll('.procedure-card a');
        
        procedureLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const procedureTitle = this.closest('.procedure-card').querySelector('.procedure-title a').textContent.trim();
                
                // Track with Google Analytics if available
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'procedure_click', {
                        'procedure_name': procedureTitle,
                        'page_location': window.location.href
                    });
                }
                
                // Track with other analytics if needed
                console.log('Procedure clicked:', procedureTitle);
            });
        });
    }
    
    /**
     * Lazy load procedure images for better performance
     */
    function lazyLoadImages() {
        const images = document.querySelectorAll('.procedure-image img');
        
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    
                    // Add loading class
                    img.classList.add('loading');
                    
                    // Handle image load
                    img.addEventListener('load', function() {
                        this.classList.remove('loading');
                        this.classList.add('loaded');
                    });
                    
                    imageObserver.unobserve(img);
                }
            });
        });
        
        images.forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    /**
     * Handle responsive layout adjustments
     */
    function handleResponsiveLayout() {
        const cards = document.querySelectorAll('.procedure-card');
        
        function adjustCardHeights() {
            // Reset heights
            cards.forEach(card => {
                card.style.height = 'auto';
            });
            
            // Only adjust on desktop
            if (window.innerWidth >= 992) {
                const rows = [];
                let currentRow = [];
                let currentTop = null;
                
                cards.forEach(card => {
                    const rect = card.getBoundingClientRect();
                    if (currentTop === null || Math.abs(rect.top - currentTop) < 10) {
                        currentRow.push(card);
                        currentTop = rect.top;
                    } else {
                        if (currentRow.length > 0) {
                            rows.push(currentRow);
                        }
                        currentRow = [card];
                        currentTop = rect.top;
                    }
                });
                
                if (currentRow.length > 0) {
                    rows.push(currentRow);
                }
                
                // Set equal heights for each row
                rows.forEach(row => {
                    const maxHeight = Math.max(...row.map(card => card.offsetHeight));
                    row.forEach(card => {
                        card.style.height = maxHeight + 'px';
                    });
                });
            }
        }
        
        // Run on load and resize
        window.addEventListener('resize', debounce(adjustCardHeights, 250));
        adjustCardHeights();
    }
    
    /**
     * Debounce function for performance
     */
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Initialize responsive layout handling
    handleResponsiveLayout();
});