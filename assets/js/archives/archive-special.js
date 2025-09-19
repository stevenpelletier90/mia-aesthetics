/**
 * Archive Special JavaScript
 * Bootstrap 5 handles tab functionality natively
 */

document.addEventListener("DOMContentLoaded", function () {
  // Image switching for featured specials based on language
  const spanishTab = document.getElementById('spanish-tab');
  const englishTab = document.getElementById('english-tab');
  const jPlasmaImgs = document.querySelectorAll('.special-card-image img[src*="j-plasma-promo.jpg"]');
  const skinnyImgs = document.querySelectorAll('.special-card-image img[src*="Skinny-Shot-1200x630px.jpg"]');

  if (spanishTab && englishTab) {
    // J-Plasma images
    const jPlasmaEnglishSrc = '/wp-content/uploads/2025/09/j-plasma-promo.jpg';
    const jPlasmaSpanishSrc = '/wp-content/uploads/2025/09/SPAN-J-plasma-1200x630px.jpg';

    // Skinny Shot images
    const skinnyEnglishSrc = '/wp-content/uploads/2025/08/Skinny-Shot-1200x630px.jpg';
    const skinnySpanishSrc = '/wp-content/uploads/2025/09/Specials-Skinny-SPAN.jpg';

    spanishTab.addEventListener('shown.bs.tab', function () {
      jPlasmaImgs.forEach(img => img.src = jPlasmaSpanishSrc);
      skinnyImgs.forEach(img => img.src = skinnySpanishSrc);
    });

    englishTab.addEventListener('shown.bs.tab', function () {
      jPlasmaImgs.forEach(img => img.src = jPlasmaEnglishSrc);
      skinnyImgs.forEach(img => img.src = skinnyEnglishSrc);
    });
  }
});
