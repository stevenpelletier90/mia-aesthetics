<!-- footer.php -->
</div><!-- .site-content -->
<footer class="site-footer">
    <div class="container-fluid wide-container">
        <div class="footer-columns">     
            <div class="footer-column">
                <h2 class="footer-heading">Follow Us</h2>
                <div class="social-icons d-flex flex-wrap gap-3 mb-3">
                    <a href="https://www.facebook.com/miaaestheticssurgery/" target="_blank" rel="noopener" aria-label="Facebook" class="social-icon">
                        <i class="fab fa-facebook-f" aria-hidden="true"></i>
                    </a>
                    <a href="https://www.instagram.com/mia_aesthetics/?hl=en" target="_blank" rel="noopener" aria-label="Instagram" class="social-icon">
                        <i class="fab fa-instagram" aria-hidden="true"></i>
                    </a>
                    <a href="https://www.tiktok.com/@mia_aesthetics?lang=en" target="_blank" rel="noopener" aria-label="TikTok" class="social-icon">
                        <i class="fab fa-tiktok" aria-hidden="true"></i>
                    </a>
                    <a href="https://x.com/mia_aesthetics?lang=en" target="_blank" rel="noopener" aria-label="Twitter" class="social-icon">
                        <i class="fab fa-twitter" aria-hidden="true"></i>
                    </a>
                    <a href="https://www.snapchat.com/add/miaaesthetics" target="_blank" rel="noopener" aria-label="SnapChat" class="social-icon">
                        <i class="fab fa-snapchat-ghost" aria-hidden="true"></i>
                    </a>
                    <a href="https://www.youtube.com/mia_aesthetics" target="_blank" rel="noopener" aria-label="YouTube" class="social-icon">
                        <i class="fab fa-youtube" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            <!-- About Column -->
            <div class="footer-column">
                <h2 class="footer-heading">About</h2>
                <nav aria-label="About navigation">
                    <ul class="footer-menu">
                        <li><a href="<?php echo esc_url(home_url('/about-us/')); ?>">Our Story</a></li>
                        <li><a href="<?php echo esc_url(home_url('/mia-foundation/')); ?>">Mia Foundation</a></li>
                        <li><a href="<?php echo esc_url(home_url('/locations/')); ?>">Locations</a></li>
                        <li><a href="<?php echo esc_url(home_url('/plastic-surgeons/')); ?>">Surgeons</a></li>
                        <li><a href="<?php echo esc_url(home_url('/careers/')); ?>">Careers</a></li>
                        <li><a href="https://patient.miaaesthetics.com/s/login?ec=302&startURL=/s/home" target="_blank" rel="noopener">Patient Portal</a></li>
                    </ul>
                </nav>
            </div>

            <!-- Resources Column -->
            <div class="footer-column">
                <h2 class="footer-heading">Resources</h2>
                <nav aria-label="Resources navigation">
                    <ul class="footer-menu">
                        <li><a href="<?php echo esc_url(home_url('/faqs/')); ?>">FAQs</a></li>
                        <li><a href="<?php echo esc_url(home_url('/conditions/')); ?>">Conditions We Treat</a></li>
                        <li><a href="<?php echo esc_url(home_url('/calculate-your-bmi/')); ?>">Calculate Your BMI</a></li>
                        <li><a href="<?php echo esc_url(home_url('/patient-resources/')); ?>">Patient Resources</a></li>
                        <li><a href="<?php echo esc_url(home_url('/surgical-journey/')); ?>">Surgical Journey</a></li>
                        <li><a href="<?php echo esc_url(home_url('/out-of-town-patients/')); ?>">Out of Town Patients</a></li>
                    </ul>
                </nav>
            </div>
            
            <!-- Procedures Column -->
            <div class="footer-column">
                <h2 class="footer-heading">Procedures</h2>
                <nav aria-label="Procedures navigation">
                    <ul class="footer-menu">
                        <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/')); ?>">Face Procedures</a></li>
                        <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/')); ?>">Body Procedures</a></li>
                        <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/breast/')); ?>">Breast Procedures</a></li>                  
                        <li><a href="<?php echo esc_url(home_url('/non-surgical/')); ?>">Non-Surgical Options</a></li>
                        <li><a href="<?php echo esc_url(home_url('/before-after/')); ?>">Before & After Gallery</a></li>
                    </ul>
                </nav>
            </div>
        </div>
        
        <div class="footer-divider-container">
            <hr class="footer-divider">
        </div>
        
        <!-- Locations & Surgeons Section with Accordion -->
        <div class="locations-section mt-4">
            <h2 class="footer-heading mb-3">Locations & Surgeons</h2>
            <div class="accordion" id="locationsAccordion">
                <?php
                // Direct query for locations
                $locations_query = new WP_Query([
                    'post_type' => 'location',
                    'posts_per_page' => -1,
                    'orderby' => 'title',
                    'order' => 'ASC',
                    'post_parent' => 0
                ]);
                
                if ($locations_query->have_posts()) :
                    $location_index = 0;
                    while ($locations_query->have_posts()) :
                        $locations_query->the_post();
                        $location_id = get_the_ID();
                        $location_title = get_the_title();
                        $location_url = get_permalink();
                        $location_index++;
                ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="location-heading-<?php echo $location_id; ?>">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#location-collapse-<?php echo $location_id; ?>" aria-expanded="false" aria-controls="location-collapse-<?php echo $location_id; ?>" aria-describedby="location-description-<?php echo $location_id; ?>">
                            <?php echo esc_html($location_title); ?>
                        </button>
                    </h2>
                    <div id="location-collapse-<?php echo $location_id; ?>" class="accordion-collapse collapse" aria-describedby="location-description-<?php echo $location_id; ?>">
                        <div class="accordion-body" id="location-description-<?php echo $location_id; ?>">
                            <!-- Location Link -->
                            <div class="location-link mb-3">
                                <a href="<?php echo esc_url($location_url); ?>" class="surgeon-link">
                                    <span>View <?php echo esc_html($location_title); ?> Location</span>
                                    <i class="fas fa-arrow-right surgeon-arrow" aria-hidden="true"></i>
                                </a>
                            </div>
                            
                            <?php
                            // Direct query for surgeons at this location
                            $surgeons_query = new WP_Query([
                                'post_type' => 'surgeon',
                                'posts_per_page' => -1,
                                'meta_query' => [
                                    [
                                        'key' => 'surgeon_location',
                                        'value' => $location_id,
                                        'compare' => '='
                                    ]
                                ]
                            ]);
                            
                            if ($surgeons_query->have_posts()) :
                            ?>
                                <div class="surgeons-list">
                                    <ul class="list-unstyled">
                                        <?php while ($surgeons_query->have_posts()) : $surgeons_query->the_post(); ?>
                                            <li class="mb-2">
                                                <a href="<?php echo esc_url(get_permalink()); ?>" class="surgeon-link">
                                                    <span><?php echo esc_html(get_the_title()); ?></span>
                                                    <i class="fas fa-arrow-right surgeon-arrow" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                </div>
                            <?php else : ?>
                                <p class="mb-0">No surgeons currently listed for this location.</p>
                            <?php 
                            endif;
                            wp_reset_postdata();
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                ?>
                    <p>No locations found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="footer-divider-container">
        <div class="container-fluid wide-container">
            <hr class="footer-divider">
        </div>
    </div>
    
    <!-- Bottom Footer - Simplified for better responsive behavior -->
    <div class="footer-bottom">
        <div class="container-fluid wide-container">
            <!-- Copyright Section -->
            <div class="text-center mb-4">
                <p class="copyright mb-1">© <?php echo date('Y'); ?> Mia Aesthetics. All rights reserved.</p>
                <p class="disclaimer">The pictures on this website consist of both models and actual patients.</p>
            </div>
            
            <!-- Links Section - Centered -->
            <div class="text-center">
                <nav aria-label="Legal and policy navigation">
                    <ul class="footer-links">
                        <li><a href="<?php echo esc_url(home_url('/website-privacy-policy/')); ?>">Privacy Policy</a></li>
                        <li><a href="<?php echo esc_url(home_url('/patient-privacy-practices/')); ?>">Patient Privacy Practices</a></li>
                        <li><a href="<?php echo esc_url(home_url('/terms-and-conditions/')); ?>">Terms & Conditions</a></li>
                        <li><a href="<?php echo esc_url(home_url('/terms-of-use/')); ?>">Terms of Use</a></li>
                        <li><a href="<?php echo esc_url(home_url('/website-sms-terms-and-conditions/')); ?>">SMS Terms & Conditions</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</html>