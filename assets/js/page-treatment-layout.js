/* Treatment Layout Template JavaScript */
// Template: page-treatment-layout.php  
// Treatment layout functionality for procedure, non-surgical, and fat-transfer post types

// Dropdown icon rotation for consultation card selects
document.addEventListener('DOMContentLoaded', function() {
    const consultationSelects = document.querySelectorAll('.consultation-card .gform_wrapper select');
    
    consultationSelects.forEach(select => {
        let isOpen = false;
        
        select.addEventListener('mousedown', function(e) {
            isOpen = !isOpen;
            const container = this.closest('.ginput_container_select');
            if (container) {
                if (isOpen) {
                    container.classList.add('dropdown-open');
                } else {
                    container.classList.remove('dropdown-open');
                }
            }
        });
        
        select.addEventListener('blur', function() {
            isOpen = false;
            const container = this.closest('.ginput_container_select');
            if (container) {
                container.classList.remove('dropdown-open');
            }
        });
        
        select.addEventListener('change', function() {
            isOpen = false;
            const container = this.closest('.ginput_container_select');
            if (container) {
                container.classList.remove('dropdown-open');
            }
        });
    });
});