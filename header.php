<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <?php wp_head(); ?>
  <!-- Bootstrap JS is already enqueued in functions.php -->
</head>
<body <?php body_class(); ?><?php if (!is_singular('surgeon')): ?> data-bs-spy="scroll" data-bs-target="#surgeon-tabs" data-bs-offset="120"<?php endif; ?>>
<?php wp_body_open(); ?>

<header class="position-sticky site-header top-0">
  <nav class="navbar navbar-expand-xl navbar-dark" id="primary-navigation" aria-label="Main navigation">
    <div class="container-fluid">
      <div class="d-flex flex-wrap align-items-center w-100">
        <!-- Logo -->
        <?php mia_the_logo(['fetchpriority' => true, 'link_class' => 'navbar-brand me-xl-3', 'aria_label' => 'Homepage – Main Navigation']); ?>
        <!-- Medium Mobile CTA - Only visible on medium mobile devices -->
        <div class="d-none d-sm-block d-xl-none mx-auto">
          <a href="<?php echo esc_url(home_url('/free-plastic-surgery-consultation/')); ?>" class="header-btn" aria-label="Schedule free virtual consultation">
            Free Virtual Consultation <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
          </a>
        </div>
        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarOffcanvas" aria-controls="navbarOffcanvas" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Offcanvas Container -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="navbarOffcanvas" aria-labelledby="navbarOffcanvasLabel">
          <div class="offcanvas-header border-bottom border-secondary">
            <div class="offcanvas-title" id="navbarOffcanvasLabel">
              <?php mia_the_logo(['height' => '30', 'width' => '120', 'loading' => 'lazy', 'aria_label' => 'Homepage – Offcanvas Navigation']); ?>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="offcanvas-body">
            <div class="d-flex flex-column flex-xl-row align-items-start align-items-xl-center w-100">
              <!-- Main Navigation -->
              <ul class="navbar-nav me-xl-auto mb-2 mb-xl-0">
                <li class="nav-item">
                  <a class="nav-link" href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                </li>
                <!-- Procedures Menu - Desktop (using refactored functions) -->
                <?php 
                $menu_structure = get_mia_menu_structure();
                render_procedures_menu($menu_structure['procedures'], false); 
                ?>
                <!-- Procedures Menu - Mobile (using refactored functions) -->
                <?php render_procedures_menu($menu_structure['procedures'], true); ?>
                <!-- Non-Surgical Menu (using refactored functions) -->
                <?php 
                  render_non_surgical_menu(false); // desktop
                  render_non_surgical_menu(true);  // mobile
                ?>
                <!-- Locations Menu (using refactored functions) -->
                <?php 
                  render_locations_menu(false); // desktop
                  render_locations_menu(true);  // mobile
                ?>
                <!-- Surgeons Menu (using refactored functions) -->
                <?php 
                  render_surgeons_menu(false); // desktop
                  render_surgeons_menu(true);  // mobile
                ?>
                <!-- Before & After Menu (using refactored functions) -->
                <?php 
                  render_before_after_menu(false); // desktop
                  render_before_after_menu(true);  // mobile
                ?>
                <li class="nav-item">
                  <a class="nav-link" href="<?php echo esc_url(home_url('/financing/')); ?>">Financing</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="<?php echo esc_url(home_url('/specials/')); ?>">Specials</a>
                </li>
                <!-- Patient Portal Dropdown -->
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true" id="patient-portal-dropdown" aria-describedby="patient-portal-menu">
                    Patient Portal
                  </a>
                  <ul class="dropdown-menu" aria-labelledby="patient-portal-dropdown" id="patient-portal-menu">
                    <li>
                      <a class="dropdown-item" href="https://patient.miaaesthetics.com/s/login?ec=302&startURL=%2Fs%2Fhome" target="_blank" rel="noopener">
                        Patient Portal Login
                      </a>
                    </li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/patient-portal-guide/')); ?>">Patient Portal Guide</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/web-to-case/')); ?>">Portal Support</a></li>
                  </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://shop.miaaesthetics.com/" target="_blank" rel="noopener">Shop</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <!-- Right Side Items -->
        <div class="d-none d-xl-block ms-auto">
          <a href="<?php echo esc_url(home_url('/free-plastic-surgery-consultation/')); ?>" class="header-btn desktop-cta" aria-label="Schedule free virtual consultation">
            Free Virtual Consultation <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
          </a>
        </div>
      </div>
    </div>
  </nav>
</header>
<!-- Mobile Floating CTA Button - Only visible on mobile -->
<div id="mobileCta" class="mobile-cta-container d-flex d-sm-none justify-content-center align-items-center position-fixed bottom-0 start-0 w-100 p-3">
  <a href="<?php echo esc_url(home_url('/free-plastic-surgery-consultation/')); ?>" class="mobile-consultation-btn d-inline-block text-center text-decoration-none" aria-label="Schedule free virtual consultation">
    Free Virtual Consultation <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
  </a>
</div>
