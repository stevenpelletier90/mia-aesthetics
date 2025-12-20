/**
 * JavaScript for the careers-locations.php template
 *
 * Handles video playback and interactive elements for the careers page
 */

document.addEventListener('DOMContentLoaded', function () {
  // Video thumbnail click handler
  const videoThumbnails = document.querySelectorAll('.video-thumbnail');

  videoThumbnails.forEach((thumbnail) => {
    thumbnail.addEventListener('click', function () {
      const embedUrl = this.getAttribute('data-embed-url');

      if (embedUrl) {
        // Create iframe element
        const iframe = document.createElement('iframe');
        iframe.src = embedUrl + '?autoplay=1';
        iframe.width = '100%';
        iframe.height = '100%';
        iframe.frameBorder = '0';
        iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
        iframe.allowFullscreen = true;

        // Replace thumbnail with iframe
        this.innerHTML = '';
        this.appendChild(iframe);

        // Remove click handler after replacing
        this.removeEventListener('click', arguments.callee);
      }
    });
  });

  // Smooth scrolling for anchor links
  const anchorLinks = document.querySelectorAll('a[href^="#"]');

  anchorLinks.forEach((link) => {
    link.addEventListener('click', function (e) {
      e.preventDefault();

      const targetId = this.getAttribute('href').substring(1);
      const targetElement = document.getElementById(targetId);

      if (targetElement) {
        targetElement.scrollIntoView({
          behavior: 'smooth',
          block: 'start',
        });
      }
    });
  });

  // Add interactive hover effects for expanded position cards
  const expandedCards = document.querySelectorAll('.position-card-expanded');

  expandedCards.forEach((card) => {
    card.addEventListener('mouseenter', function () {
      // Add subtle animation
      this.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
    });
  });

  // Lazy loading for images
  const images = document.querySelectorAll('img[loading="lazy"]');

  if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const img = entry.target;
          img.classList.add('fade-in');
          observer.unobserve(img);
        }
      });
    });

    images.forEach((img) => {
      imageObserver.observe(img);
    });
  }

  // Add fade-in animation styles
  const style = document.createElement('style');
  style.textContent = `
        .fade-in {
            opacity: 0;
            animation: fadeIn 0.5s ease-in-out forwards;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    `;
  document.head.appendChild(style);

  // Track position card interactions
  const positionCards = document.querySelectorAll('.position-card-expanded');

  positionCards.forEach((card) => {
    card.addEventListener('click', function () {
      const positionTitle = this.querySelector('.position-title-expanded');
      if (positionTitle && 'undefined' !== typeof gtag) {
        gtag('event', 'position_card_click', {
          position_name: positionTitle.textContent.trim(),
          page_location: window.location.pathname,
        });
      }
    });
  });

  // Handle CTA button clicks
  const ctaButtons = document.querySelectorAll('.btn');

  ctaButtons.forEach((button) => {
    button.addEventListener('click', function () {
      const buttonText = this.textContent.trim();

      // Track button clicks
      if ('undefined' !== typeof gtag) {
        gtag('event', 'cta_click', {
          button_text: buttonText,
          page_location: window.location.pathname,
        });
      }

      // Add loading state for external links
      if (this.href && this.href.includes('http') && !this.href.includes(window.location.hostname)) {
        this.innerHTML = this.innerHTML.replace(/(<i[^>]*>.*?<\/i>)/, '<i class="fa-solid fa-spinner fa-spin"></i>');
        this.disabled = true;
      }
    });
  });

  // Accordion tracking for analytics
  const accordionButtons = document.querySelectorAll('.accordion-button');

  accordionButtons.forEach((button) => {
    button.addEventListener('click', function () {
      const isExpanded = 'false' === this.getAttribute('aria-expanded');
      const categoryName = this.textContent.trim();

      if (isExpanded && 'undefined' !== typeof gtag) {
        gtag('event', 'accordion_expand', {
          category_name: categoryName,
          page_location: window.location.pathname,
        });
      }
    });
  });

  // Add smooth animation to accordion content
  const accordionCollapses = document.querySelectorAll('.accordion-collapse');

  accordionCollapses.forEach((collapse) => {
    collapse.addEventListener('show.bs.collapse', function () {
      this.style.overflow = 'hidden';
    });

    collapse.addEventListener('shown.bs.collapse', function () {
      this.style.overflow = 'visible';
    });
  });
});
