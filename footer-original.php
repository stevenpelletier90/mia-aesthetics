<?php
/**
 * The template for displaying the footer.
 *
 * Contains the body & html closing tags.
 *
 * @package HelloElementor
 */

if (!defined('ABSPATH')) {
   exit;
}
?>

<footer id="site-footer" class="site-footer mia-footer" role="contentinfo">
   <div class="cta-section">
       <div class="cta-overlay"></div>
       <div class="cta-content">
           <p>BEGIN YOUR TRANSFORMATION</p>
           <h2>GET YOUR DREAM BODY TODAY</h2>
           <a href="/free-plastic-surgery-consultation/" class="cta-button">START YOUR JOURNEY</a>
       </div>
   </div>
   <div class="footer-content">
       <div class="footer-column">
           <ul>
               <li><a href="/about-us/">Our Story</a></li>
               <li><a href="/mia-foundation/">Mia Foundation</a></li>
               <li><a href="/locations/">Locations</a></li>
               <li><a href="/plastic-surgeons/">Surgeons</a></li>
               <li><a href="/careers/">Careers</a></li>
               <li><a href="https://patient.miaaesthetics.com/s/login?ec=302&startURL=/s/home" target="_blank">Patient Portal</a></li>
           </ul>
       </div>
       <div class="footer-column">
           <ul>
               <li><a href="/faqs/">FAQs</a></li>
               <li><a href="/conditions/">Conditions We Treat</a></li>
               <li><a href="/calculate-your-bmi/">Calculate Your BMI</a></li>
               <li><a href="/patient-resources/">Patient Resources</a></li>
               <li><a href="/patient-resources/surgical-journey/">Surgical Journey</a></li>
               <li><a href="/out-of-town-patients/">Out of Town Patients</a></li>
           </ul>
       </div>
       <div class="footer-column social-media">
           <ul>
               <li><a href="https://www.facebook.com/miaaestheticssurgery/" target="_blank" aria-label="Follow us on Facebook"><i class="fab fa-facebook-f"></i> <span>Facebook</span></a></li>
               <li><a href="https://www.instagram.com/mia_aesthetics/" target="_blank" aria-label="Follow us on Instagram"><i class="fab fa-instagram"></i> <span>Instagram</span></a></li>
               <li><a href="https://www.tiktok.com/@mia_aesthetics/" target="_blank" aria-label="Follow us on TikTok"><i class="fab fa-tiktok"></i> <span>TikTok</span></a></li>
               <li><a href="https://x.com/mia_aesthetics/" target="_blank" aria-label="Follow us on X"><i class="fab fa-twitter"></i> <span>Twitter</span></a></li>
               <li><a href="https://www.snapchat.com/add/mia_aesthetics" target="_blank" aria-label="Follow us on SnapChat"><i class="fab fa-snapchat-ghost"></i> <span>SnapChat</span></a></li>
               <li><a href="https://www.youtube.com/mia_aesthetics/" target="_blank" aria-label="Follow us on YouTube"><i class="fab fa-youtube"></i> <span>YouTube</span></a></li>
           </ul>
       </div>
       <div class="footer-column footer-logo-copyright">
           <a href="/">
               <img src="/wp-content/uploads/2023/05/Gold.png" alt="Mia Aesthetics Logo" class="footer-logo">
           </a>
           <p>© <?php echo date('Y'); ?> Mia Aesthetics. All rights reserved. The pictures on this website consist of both models and actual patients.</p>
       </div>
   </div>
   <div class="footer-legal">
       <a href="/website-privacy-policy/">Privacy Policy</a> |
       <a href="/patient-privacy-practices/">Patient Privacy Practices</a> |
       <a href="/terms-and-conditions/">Terms & Conditions</a> |
       <a href="/terms-of-use/">Terms of Use</a> |
       <a href="/website-sms-terms-and-conditions/">SMS Terms & Conditions</a> |
       <a href="#" onclick="ketch('showExperience')">Your Privacy Choices <img src="/wp-content/uploads/2026/01/privacyoptions.svg" alt="" style="height: 14px; vertical-align: middle; margin-left: 4px;"></a>
   </div>
   <div class="sticky-footer">
       <a href="/free-plastic-surgery-consultation/" class="sticky-button footer-button">Free Virtual Consultation</a>
   </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>