<?php
/**
 * Menu Helper Functions for Mia Aesthetics theme.
 *
 * Provides rendering logic and caching for navigation menus (procedures, locations, surgeons, before/after).
 * All menu display logic is centralized here for maintainability.
 *
 * @package Mia_Aesthetics
 */

// Prevent direct access to this file.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get all menu data with a single optimized query
 * Returns cached results for locations, surgeons, and non-surgical procedures
 *
 * @return array Associative array with 'locations', 'surgeons', 'non_surgical' keys
 */
function mia_get_all_menu_data() {
    $cache_key = 'mia_all_menu_data';
    $all_data = get_transient($cache_key);
    
    if (false === $all_data) {
        // Single query for all menu post types
        $args = [
            'post_type' => ['location', 'surgeon', 'non-surgical'],
            'posts_per_page' => -1,
            'orderby' => 'post_type title',
            'order' => 'ASC',
            'fields' => 'ids',
            'no_found_rows' => true,
            'update_post_meta_cache' => true, // Only for locations that need ACF state field
            'update_post_term_cache' => false,
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key' => 'post_type',
                    'compare' => 'NOT EXISTS', // Default case
                ],
                [
                    'key' => 'post_parent',
                    'value' => 0,
                    'compare' => '=',
                    'type' => 'NUMERIC'
                ]
            ]
        ];
        
        $post_ids = get_posts($args);
        $all_data = [
            'locations' => [],
            'surgeons' => [],
            'non_surgical' => []
        ];
        
        if (!empty($post_ids)) {
            foreach ($post_ids as $post_id) {
                $post_type = get_post_type($post_id);
                
                switch ($post_type) {
                    case 'location':
                        // Only include parent locations (post_parent = 0)
                        if (wp_get_post_parent_id($post_id) === 0) {
                            $all_data['locations'][] = [
                                'id' => $post_id,
                                'title' => get_the_title($post_id),
                                'url' => get_permalink($post_id),
                                'state' => get_field('state', $post_id)
                            ];
                        }
                        break;
                        
                    case 'surgeon':
                        $surgeon_name = get_the_title($post_id);
                        $name_parts = explode(' ', $surgeon_name);
                        $last_name = isset($name_parts[1]) ? $name_parts[1] : $surgeon_name;
                        
                        $all_data['surgeons'][] = [
                            'id' => $post_id,
                            'name' => $surgeon_name,
                            'url' => get_permalink($post_id),
                            'last_name' => $last_name
                        ];
                        break;
                        
                    case 'non-surgical':
                        $all_data['non_surgical'][] = [
                            'id' => $post_id,
                            'title' => get_the_title($post_id),
                            'url' => get_permalink($post_id)
                        ];
                        break;
                }
            }
            
            // Sort surgeons by last name
            if (!empty($all_data['surgeons'])) {
                usort($all_data['surgeons'], function($a, $b) {
                    return strcasecmp($a['last_name'], $b['last_name']);
                });
            }
        }
        
        set_transient($cache_key, $all_data, DAY_IN_SECONDS);
    }
    
    return $all_data;
}

/**
 * Get locations with caching (optimized to use consolidated data)
 */
function get_locations_direct() {
    $all_data = mia_get_all_menu_data();
    return $all_data['locations'];
}

/**
 * Get non-surgical procedures with caching (optimized to use consolidated data)
 */
function get_non_surgical_direct() {
    $all_data = mia_get_all_menu_data();
    return $all_data['non_surgical'];
}

/**
 * Get surgeons with caching (optimized to use consolidated data)
 */
function get_surgeons_direct() {
    $all_data = mia_get_all_menu_data();
    return $all_data['surgeons'];
}

/**
 * Clear menu data cache when relevant posts are updated
 */
function mia_clear_menu_cache($post_id) {
    $post_type = get_post_type($post_id);
    if (in_array($post_type, ['location', 'surgeon', 'non-surgical'])) {
        delete_transient('mia_all_menu_data');
        delete_transient('mia_locations_menu');
        delete_transient('mia_surgeons_menu');
        delete_transient('mia_non_surgical_menu');
    }
}
add_action('save_post', 'mia_clear_menu_cache');
add_action('delete_post', 'mia_clear_menu_cache');
add_action('trash_post', 'mia_clear_menu_cache');
add_action('untrash_post', 'mia_clear_menu_cache');

/**
 * Get footer locations with surgeons data (optimized for footer display)
 * Solves N+1 query problem by fetching all surgeons in a single query
 * 
 * @return array Array of locations with associated surgeons
 */
function mia_get_footer_locations() {
    $cache_key = 'mia_footer_locations_with_surgeons';
    $locations_data = get_transient($cache_key);
    
    if (false === $locations_data) {
        // Step 1: Get all locations
        $locations_query = new WP_Query([
            'post_type' => 'location',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'post_parent' => 0,
            'fields' => 'ids',
            'no_found_rows' => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false
        ]);
        
        if (empty($locations_query->posts)) {
            return [];
        }
        
        $location_ids = $locations_query->posts;
        
        // Step 2: Get ALL surgeons for ALL locations in one query (prevents N+1)
        $all_surgeons_query = new WP_Query([
            'post_type' => 'surgeon',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => 'surgeon_location',
                    'value' => $location_ids,
                    'compare' => 'IN'
                ]
            ],
            'fields' => 'ids',
            'no_found_rows' => true,
            'update_post_meta_cache' => true, // Need meta for surgeon_location field
            'update_post_term_cache' => false
        ]);
        
        // Step 3: Group surgeons by location
        $surgeons_by_location = [];
        if (!empty($all_surgeons_query->posts)) {
            foreach ($all_surgeons_query->posts as $surgeon_id) {
                $surgeon_location = get_field('surgeon_location', $surgeon_id);
                if ($surgeon_location && in_array($surgeon_location, $location_ids)) {
                    if (!isset($surgeons_by_location[$surgeon_location])) {
                        $surgeons_by_location[$surgeon_location] = [];
                    }
                    $surgeons_by_location[$surgeon_location][] = [
                        'id' => $surgeon_id,
                        'title' => get_the_title($surgeon_id),
                        'url' => get_permalink($surgeon_id)
                    ];
                }
            }
        }
        
        // Step 4: Build final locations data structure
        $locations_data = [];
        foreach ($location_ids as $location_id) {
            $locations_data[] = [
                'id' => $location_id,
                'title' => get_the_title($location_id),
                'url' => get_permalink($location_id),
                'surgeons' => $surgeons_by_location[$location_id] ?? []
            ];
        }
        
        // Cache for 6 hours (locations and surgeons don't change frequently)
        set_transient($cache_key, $locations_data, 6 * HOUR_IN_SECONDS);
    }
    
    return $locations_data;
}

/**
 * Clear footer locations cache when relevant posts are updated
 */
function mia_clear_footer_locations_cache($post_id) {
    $post_type = get_post_type($post_id);
    if (in_array($post_type, ['location', 'surgeon'])) {
        delete_transient('mia_footer_locations_with_surgeons');
    }
}
add_action('save_post', 'mia_clear_footer_locations_cache');
add_action('delete_post', 'mia_clear_footer_locations_cache');
add_action('trash_post', 'mia_clear_footer_locations_cache');
add_action('untrash_post', 'mia_clear_footer_locations_cache');

/**
 * Render procedures dropdown
 */
function render_procedures_menu($procedures, $is_mobile = false) {
    $dropdown_class = $is_mobile ? 'd-xl-none' : 'position-static d-none d-xl-block';
    ?>
    <li class="nav-item dropdown <?php echo $dropdown_class; ?>">
        <a class="nav-link dropdown-toggle" href="<?php echo esc_url($procedures['url']); ?>" 
           role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
            <?php echo esc_html($procedures['title']); ?>
        </a>
        <?php if ($is_mobile): ?>
            <?php render_mobile_procedures_menu($procedures); ?>
        <?php else: ?>
            <?php render_desktop_procedures_menu($procedures); ?>
        <?php endif; ?>
    </li>
    <?php
}

/**
 * Render desktop procedures mega menu
 */
function render_desktop_procedures_menu($procedures) {
    ?>
    <div class="dropdown-menu mega-menu w-100 p-3 rounded-0 mt-0">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-3">
                    <a class="mega-menu-title" href="<?php echo esc_url($procedures['url']); ?>">View All Procedures <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="row">
                <?php foreach ($procedures['sections'] as $section_key => $section): ?>
                    <div class="col-md-3 mb-3">
                        <div class="dropdown-header">
                            <a href="<?php echo esc_url($section['url']); ?>" class="text-dark fw-bold text-decoration-none"><?php echo esc_html($section['title']); ?></a>
                        </div>
                        <ul class="list-unstyled">
                            <?php foreach ($section['items'] as $item): ?>
                                <?php 
                                $parent_path = isset($item['parent']) ? $procedures['sections'][$item['parent']]['url'] : $section['url'];
                                $item_url = rtrim($parent_path, '/') . '/' . $item['slug'] . '/';
                                ?>
                                <li><a class="dropdown-item py-1" href="<?php echo esc_url($item_url); ?>"><?php echo esc_html($item['title']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render mobile procedures dropdown menu
 */
function render_mobile_procedures_menu($procedures) {
    ?>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="<?php echo esc_url($procedures['url']); ?>">View All Procedures</a></li>
        <?php foreach ($procedures['sections'] as $section_key => $section): ?>
            <!-- Section Header -->
            <li><a class="dropdown-item fw-bold" href="<?php echo esc_url($section['url']); ?>"><?php echo esc_html($section['title']); ?></a></li>
            <!-- Section Items -->
            <?php foreach ($section['items'] as $item): ?>
                <?php 
                $parent_path = isset($item['parent']) ? $procedures['sections'][$item['parent']]['url'] : $section['url'];
                $item_url = rtrim($parent_path, '/') . '/' . $item['slug'] . '/';
                ?>
                <li><a class="dropdown-item" href="<?php echo esc_url($item_url); ?>"><?php echo esc_html($item['title']); ?></a></li>
            <?php endforeach; ?>
            <?php if ($section_key !== array_key_last($procedures['sections'])): ?>
                <li><hr class="dropdown-divider"></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
    <?php
}

/**
 * Render locations menu for both desktop and mobile
 */
function render_locations_menu($is_mobile = false) {
    $locations = get_locations_direct();
    $dropdown_class = $is_mobile ? 'd-xl-none' : 'position-static d-none d-xl-block';
    ?>
    <li class="nav-item dropdown <?php echo $dropdown_class; ?>">
        <a class="nav-link dropdown-toggle" href="<?php echo esc_url(home_url('/locations/')); ?>" 
           role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
            Locations
        </a>
        <?php if ($is_mobile): ?>
            <?php render_mobile_locations_menu($locations); ?>
        <?php else: ?>
            <?php render_desktop_locations_menu($locations); ?>
        <?php endif; ?>
    </li>
    <?php
}

/**
 * Render desktop locations mega menu
 */
function render_desktop_locations_menu($locations) {
    ?>
    <div class="dropdown-menu mega-menu w-100 p-3 rounded-0 mt-0">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-3">
                    <a class="mega-menu-title" href="<?php echo esc_url(home_url('/locations/')); ?>">View All Locations <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="row">
                <?php
                if (!empty($locations)) :
                    $total_locations = count($locations);
                    $locations_per_column = ceil($total_locations / 4);
                    $location_count = 0;
                    $column_count = 0;
                    
                    echo '<div class="col-md-3 mb-3"><ul class="list-unstyled">';
                    foreach ($locations as $location) :
                        $display_city = trim(str_ireplace('Mia Aesthetics', '', $location['title']));
                        $abbr = mia_get_state_abbr($location['state']);
                        $menu_label = $location['state'] ? $display_city . ', ' . $abbr : $display_city;
                        
                        echo '<li><a class="dropdown-item py-1" href="' . esc_url($location['url']) . '">' . esc_html($menu_label) . '</a></li>';
                        $location_count++;
                        
                        if ($location_count % $locations_per_column === 0 && $location_count < $total_locations) {
                            $column_count++;
                            echo '</ul></div><div class="col-md-3 mb-3"><ul class="list-unstyled">';
                        }
                    endforeach;
                    echo '</ul></div>';
                    
                    while ($column_count < 3) {
                        $column_count++;
                        echo '<div class="col-md-3 mb-3"></div>';
                    }
                else:
                    echo '<div class="col-12"><p>No locations found. <a href="' . esc_url(home_url('/locations/')) . '">View our locations page</a> for more information.</p></div>';
                endif;
                ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render mobile locations dropdown menu
 */
function render_mobile_locations_menu($locations) {
    ?>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/locations/')); ?>">View All Locations</a></li>
        <?php
        if (!empty($locations)) :
            foreach ($locations as $location) :
                $display_city = trim(str_ireplace('Mia Aesthetics', '', $location['title']));
                $abbr = mia_get_state_abbr($location['state']);
                $menu_label = $location['state'] ? $display_city . ', ' . $abbr : $display_city;
                ?>
                <li><a class="dropdown-item" href="<?php echo esc_url($location['url']); ?>"><?php echo esc_html($menu_label); ?></a></li>
                <?php
            endforeach;
        else:
            ?>
            <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/locations/')); ?>">View Our Locations</a></li>
            <?php
        endif;
        ?>
    </ul>
    <?php
}

/**
 * Render surgeons menu for both desktop and mobile
 */
function render_surgeons_menu($is_mobile = false) {
    $surgeons = get_surgeons_direct();
    $dropdown_class = $is_mobile ? 'd-xl-none' : 'position-static d-none d-xl-block';
    ?>
    <li class="nav-item dropdown <?php echo $dropdown_class; ?>">
        <a class="nav-link dropdown-toggle" href="<?php echo esc_url(home_url('/plastic-surgeons/')); ?>" 
           role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
            Surgeons
        </a>
        <?php if ($is_mobile): ?>
            <?php render_mobile_surgeons_menu($surgeons); ?>
        <?php else: ?>
            <?php render_desktop_surgeons_menu($surgeons); ?>
        <?php endif; ?>
    </li>
    <?php
}

/**
 * Render desktop surgeons mega menu
 */
function render_desktop_surgeons_menu($surgeons) {
    ?>
    <div class="dropdown-menu mega-menu w-100 p-3 rounded-0 mt-0">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-3">
                    <a class="mega-menu-title" href="<?php echo esc_url(home_url('/plastic-surgeons/')); ?>">View All Surgeons <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="row">
                <?php
                if (!empty($surgeons)) :
                    $total_surgeons = count($surgeons);
                    $surgeons_per_column = ceil($total_surgeons / 4);
                    $surgeon_count = 0;
                    $column_count = 0;
                    
                    echo '<div class="col-md-3 mb-3"><ul class="list-unstyled">';
                    foreach ($surgeons as $surgeon) :
                        echo '<li><a class="dropdown-item py-1" href="' . esc_url($surgeon['url']) . '">' . esc_html($surgeon['name']) . '</a></li>';
                        $surgeon_count++;
                        
                        if ($surgeon_count % $surgeons_per_column === 0 && $surgeon_count < $total_surgeons) {
                            $column_count++;
                            echo '</ul></div><div class="col-md-3 mb-3"><ul class="list-unstyled">';
                        }
                    endforeach;
                    echo '</ul></div>';
                    
                    while ($column_count < 3) {
                        $column_count++;
                        echo '<div class="col-md-3 mb-3"></div>';
                    }
                else:
                    echo '<div class="col-12"><p>No surgeons found. <a href="' . esc_url(home_url('/plastic-surgeons/')) . '">View our surgeons page</a> for more information.</p></div>';
                endif;
                ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render mobile surgeons dropdown menu
 */
function render_mobile_surgeons_menu($surgeons) {
    ?>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/plastic-surgeons/')); ?>">View All Surgeons</a></li>
        <?php
        if (!empty($surgeons)) :
            foreach ($surgeons as $surgeon) :
                ?>
                <li><a class="dropdown-item" href="<?php echo esc_url($surgeon['url']); ?>"><?php echo esc_html($surgeon['name']); ?></a></li>
                <?php
            endforeach;
        else:
            ?>
            <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/plastic-surgeons/')); ?>">View Our Surgeons</a></li>
            <?php
        endif;
        ?>
    </ul>
    <?php
}

/**
 * Render Before & After menu for both desktop and mobile
 */
function render_before_after_menu($is_mobile = false) {
    $dropdown_class = $is_mobile ? 'd-xl-none' : 'position-static d-none d-xl-block';
    ?>
    <li class="nav-item dropdown <?php echo $dropdown_class; ?>">
        <a class="nav-link dropdown-toggle" href="<?php echo esc_url(home_url('/before-after/')); ?>" 
           role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
            Before & After
        </a>
        <?php if ($is_mobile): ?>
            <?php render_mobile_before_after_menu(); ?>
        <?php else: ?>
            <?php render_desktop_before_after_menu(); ?>
        <?php endif; ?>
    </li>
    <?php
}

/**
 * Render desktop Before & After mega menu
 */
function render_desktop_before_after_menu() {
    ?>
    <div class="dropdown-menu mega-menu w-100 p-3 rounded-0 mt-0">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-3">
                    <a class="mega-menu-title" href="<?php echo esc_url(home_url('/before-after/')); ?>">View All Before & After <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="dropdown-header">
                        <span class="text-dark fw-bold">By Procedure</span>
                    </div>
                    <ul class="list-unstyled">
                        <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/before-after/arm/')); ?>">Arm Lift</a></li>
                        <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/before-after/bbl/')); ?>">Brazilian Butt Lift (BBL)</a></li>
                        <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/before-after/breast-augmentation/')); ?>">Breast Augmentation</a></li>
                        <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/before-after/breast-lift/')); ?>">Breast Lift</a></li>
                        <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/before-after/breast-reduction/')); ?>">Breast Reduction</a></li>
                        <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/before-after/lipo-360/')); ?>">Lipo 360</a></li>
                        <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/before-after/mommy-makeover/')); ?>">Mommy Makeover</a></li>
                        <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/before-after/tummy-tuck/')); ?>">Tummy Tuck</a></li>
                    </ul>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="dropdown-header">
                        <span class="text-dark fw-bold">By Category</span>
                    </div>
                    <ul class="list-unstyled">
                        <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/before-after/before-after-by-doctor/')); ?>">Results by Surgeon</a></li>
                        <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/before-after/patient-journeys/')); ?>">Patient Videos</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render mobile Before & After dropdown menu
 */
function render_mobile_before_after_menu() {
    ?>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/before-after/')); ?>">View All Before & After</a></li>
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/before-after/arm/')); ?>">Arm Lift</a></li>
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/before-after/bbl/')); ?>">Brazilian Butt Lift (BBL)</a></li>
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/before-after/breast-augmentation/')); ?>">Breast Augmentation</a></li>
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/before-after/breast-lift/')); ?>">Breast Lift</a></li>
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/before-after/breast-reduction/')); ?>">Breast Reduction</a></li>
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/before-after/lipo-360/')); ?>">Lipo 360</a></li>
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/before-after/mommy-makeover/')); ?>">Mommy Makeover</a></li>
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/before-after/tummy-tuck/')); ?>">Tummy Tuck</a></li>
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/before-after/patient-journeys/')); ?>">Patient Videos</a></li>
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/before-after/before-after-by-doctor/')); ?>">Results by Surgeon</a></li>
    </ul>
    <?php
}

/**
 * Render non-surgical menu for both desktop and mobile
 */
function render_non_surgical_menu($is_mobile = false) {
    $procedures = get_non_surgical_direct();
    $dropdown_class = $is_mobile ? 'd-xl-none' : 'position-static d-none d-xl-block';
    ?>
    <li class="nav-item dropdown <?php echo $dropdown_class; ?>">
        <a class="nav-link dropdown-toggle" href="<?php echo esc_url(home_url('/non-surgical/')); ?>" 
           role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
            Non-Surgical
        </a>
        <?php if ($is_mobile): ?>
            <?php render_mobile_non_surgical_menu($procedures); ?>
        <?php else: ?>
            <?php render_desktop_non_surgical_menu($procedures); ?>
        <?php endif; ?>
    </li>
    <?php
}

/**
 * Render desktop non-surgical mega menu
 */
function render_desktop_non_surgical_menu($procedures) {
    ?>
    <div class="dropdown-menu mega-menu w-100 p-3 rounded-0 mt-0">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-3">
                    <a class="mega-menu-title" href="<?php echo esc_url(home_url('/non-surgical/')); ?>">View All Non-Surgical Procedures <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <ul class="list-unstyled">
                        <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/non-surgical/j-plasma-skin-tightening/')); ?>">J-Plasma</a></li>
                        <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/weight-loss/')); ?>">Weight Loss</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-3"></div>
                <div class="col-md-3 mb-3"></div>
                <div class="col-md-3 mb-3"></div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render mobile non-surgical dropdown menu
 */
function render_mobile_non_surgical_menu($procedures) {
    ?>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/non-surgical/')); ?>">View All Non-Surgical</a></li>
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/non-surgical/j-plasma-skin-tightening/')); ?>">J-Plasma</a></li>
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/weight-loss/')); ?>">Weight Loss</a></li>
    </ul>
    <?php
}