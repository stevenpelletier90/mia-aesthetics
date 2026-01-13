<?php
/**
 * Theme Header
 *
 * Accessible, SEO‑friendly header with mobile offcanvas navigation.
 * Preserves existing dynamic menu logic and CTAs.
 *
 * @package Mia_Aesthetics
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<script>!function(){window.semaphore=window.semaphore||[],window.ketch=function(){window.semaphore.push(arguments)};var e=new URLSearchParams(document.location.search),n=document.createElement("script");n.type="text/javascript", n.src="https://global.ketchcdn.com/web/v3/config/mia_aesthetics_services/website_smart_tag/boot.js", n.defer=n.async=!0,document.getElementsByTagName("head")[0].appendChild(n)}();</script>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>
<?php
// Optional body data attributes (e.g., scroll‑spy on surgeon pages).
$body_data_attrs = '';
if ( is_singular( 'surgeon' ) ) {
	$body_data_attrs = ' data-bs-spy="scroll" data-bs-target="#surgeon-tabs" data-bs-offset="120"';
}
?>
<body <?php body_class(); ?><?php echo is_singular( 'surgeon' ) ? ' data-bs-spy="scroll" data-bs-target="#surgeon-tabs" data-bs-offset="120"' : ''; ?>>
<?php wp_body_open(); ?>

<header class="site-header position-sticky top-0">
	<nav class="navbar navbar-expand-xl navbar-dark" id="site-navigation" aria-label="Primary navigation">
		<div class="container-fluid">
			<div class="d-flex flex-nowrap align-items-center w-100">
				<!-- Logo -->
				<?php
				mia_aesthetics_the_logo(
					array(
						'fetchpriority' => 'high',
						'link_class'    => 'navbar-brand me-xl-3',
						'aria_label'    => 'Homepage – Primary Navigation',
					)
				);
				?>
				
				<!-- Tablet/Mobile CTA - Only visible on medium mobile devices -->
				<div class="d-none d-sm-block d-xl-none mx-auto">
					<a href="<?php echo esc_url( home_url( '/free-plastic-surgery-consultation/' ) ); ?>" class="header-btn" aria-label="Schedule free virtual consultation">
						<?php esc_html_e( 'Free Virtual Consultation', 'mia-aesthetics' ); ?>
					</a>
				</div>
				
				<!-- Mobile Toggle Button -->
				<button class="navbar-toggler ms-auto" type="button" data-bs-toggle="offcanvas" data-bs-target="#siteMenu" aria-controls="siteMenu" aria-expanded="false" aria-label="Open menu">
					<span class="navbar-toggler-icon"></span>
				</button>
				
				<!-- Offcanvas Container (contains both desktop and mobile navigation) -->
				<div class="offcanvas offcanvas-end" tabindex="-1" id="siteMenu" aria-labelledby="siteMenuLabel">
					<div class="offcanvas-header border-bottom border-secondary">
						<div class="offcanvas-title" id="siteMenuLabel">
							<?php
							mia_aesthetics_the_logo(
								array(
									'height'     => '30',
									'width'      => '120',
									'loading'    => 'lazy',
									'aria_label' => 'Homepage – Offcanvas Navigation',
								)
							);
							?>
						</div>
						<button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close menu"></button>
					</div>
					<div class="offcanvas-body">
						<div class="d-flex flex-column flex-xl-row align-items-start align-items-xl-center w-100">
							<!-- Main Navigation -->
							<ul class="navbar-nav ms-xl-auto me-xl-3 mb-2 mb-xl-0">
								<?php
								$menu_structure = mia_aesthetics_get_menu_structure();
								// 1. Locations
								mia_aesthetics_render_locations_menu( false );
								mia_aesthetics_render_locations_menu( true );
								// 2. Surgeons
								mia_aesthetics_render_surgeons_menu( false );
								mia_aesthetics_render_surgeons_menu( true );
								// 3. Procedures
								mia_aesthetics_render_procedures_menu( $menu_structure['procedures'], false );
								mia_aesthetics_render_procedures_menu( $menu_structure['procedures'], true );
								// 4. Non-Surgical
								mia_aesthetics_render_non_surgical_menu( false );
								mia_aesthetics_render_non_surgical_menu( true );
								// 5. Before & After
								mia_aesthetics_render_before_after_menu( false );
								mia_aesthetics_render_before_after_menu( true );
								?>
								<!-- 6. Financing -->
								<li class="nav-item <?php echo mia_aesthetics_is_current_section( 'financing' ) ? 'current-menu-ancestor' : ''; ?>">
									<a class="nav-link" href="<?php echo esc_url( home_url( '/financing/' ) ); ?>" <?php echo mia_aesthetics_is_current_url( home_url( '/financing/' ) ) ? 'aria-current="page"' : ''; ?>><?php esc_html_e( 'Financing', 'mia-aesthetics' ); ?></a>
								</li>
								<!-- 7. Specials -->
								<li class="nav-item <?php echo mia_aesthetics_is_current_section( 'specials' ) ? 'current-menu-ancestor' : ''; ?>">
									<a class="nav-link" href="<?php echo esc_url( home_url( '/specials/' ) ); ?>" <?php echo mia_aesthetics_is_current_url( home_url( '/specials/' ) ) ? 'aria-current="page"' : ''; ?>><?php esc_html_e( 'Specials', 'mia-aesthetics' ); ?></a>
								</li>
								<!-- 8. Shop -->
								<li class="nav-item">
									<a class="nav-link" href="https://shop.miaaesthetics.com/" target="_blank" rel="noopener"><?php esc_html_e( 'Shop', 'mia-aesthetics' ); ?></a>
								</li>
								<!-- 9. Patient Portal -->
								<li class="nav-item dropdown">
									<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true" id="patient-portal-dropdown" aria-controls="patient-portal-menu">
										<?php esc_html_e( 'Patient Portal', 'mia-aesthetics' ); ?>
										<i class="fa-solid fa-chevron-down d-xl-none"></i>
									</a>
									<ul class="dropdown-menu" aria-labelledby="patient-portal-dropdown" id="patient-portal-menu">
										<li>
											<a class="dropdown-item" href="https://patient.miaaesthetics.com/s/login?ec=302&amp;startURL=%2Fs%2Fhome" target="_blank" rel="noopener">
												<?php esc_html_e( 'Portal Login', 'mia-aesthetics' ); ?>
											</a>
										</li>
										<li><a class="dropdown-item" href="<?php echo esc_url( home_url( '/web-to-case/' ) ); ?>"><?php esc_html_e( 'Portal Support', 'mia-aesthetics' ); ?></a></li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
				</div>
				
				<!-- Desktop CTA Button -->
				<div class="d-none d-xl-block">
					<a href="<?php echo esc_url( home_url( '/free-plastic-surgery-consultation/' ) ); ?>" class="header-btn desktop-cta" aria-label="Schedule free virtual consultation">
						<?php esc_html_e( 'Free Virtual Consultation', 'mia-aesthetics' ); ?>
					</a>
				</div>
			</div>
		</div>
	</nav>
</header>
