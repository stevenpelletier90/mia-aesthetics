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
<body 
<?php
body_class();
echo esc_attr( $body_data_attrs );
?>
>
<?php wp_body_open(); ?>
<a href="#primary" class="skip-link">Skip to content</a>

<header class="site-header position-sticky top-0">
	<nav class="navbar navbar-expand-xl navbar-dark" id="site-navigation" aria-label="Primary navigation">
		<div class="container-fluid">
			<!-- Branding -->
			<?php
			mia_aesthetics_the_logo(
				array(
					'fetchpriority' => 'high',
					'link_class'    => 'navbar-brand',
					'aria_label'    => 'Homepage – Primary Navigation',
				)
			);
			?>

			<!-- Desktop navigation -->
			<ul class="navbar-nav d-none d-xl-flex ms-auto" role="list">
				<?php
					$menu_structure = mia_aesthetics_get_menu_structure();
					mia_aesthetics_render_procedures_menu( $menu_structure['procedures'], false );
					mia_aesthetics_render_non_surgical_menu( false );
					mia_aesthetics_render_locations_menu( false );
					mia_aesthetics_render_surgeons_menu( false );
					mia_aesthetics_render_before_after_menu( false );
				?>
				<li class="nav-item <?php echo mia_aesthetics_is_current_section( 'financing' ) ? 'current-menu-ancestor' : ''; ?>">
					<a class="nav-link" href="<?php echo esc_url( home_url( '/financing/' ) ); ?>" <?php echo mia_aesthetics_is_current_url( home_url( '/financing/' ) ) ? 'aria-current="page"' : ''; ?>><?php esc_html_e( 'Financing', 'mia-aesthetics' ); ?></a>
				</li>
				<li class="nav-item <?php echo mia_aesthetics_is_current_section( 'specials' ) ? 'current-menu-ancestor' : ''; ?>">
					<a class="nav-link" href="<?php echo esc_url( home_url( '/specials/' ) ); ?>" <?php echo mia_aesthetics_is_current_url( home_url( '/specials/' ) ) ? 'aria-current="page"' : ''; ?>><?php esc_html_e( 'Specials', 'mia-aesthetics' ); ?></a>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="patient-portal-dropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true" aria-controls="patient-portal-menu"><?php esc_html_e( 'Patient Portal', 'mia-aesthetics' ); ?></a>
					<ul class="dropdown-menu" aria-labelledby="patient-portal-dropdown" id="patient-portal-menu">
						<li><a class="dropdown-item" href="https://patient.miaaesthetics.com/s/login?ec=302&amp;startURL=%2Fs%2Fhome" target="_blank" rel="noopener"><?php esc_html_e( 'Portal Login', 'mia-aesthetics' ); ?></a></li>
						<li><a class="dropdown-item" href="<?php echo esc_url( home_url( '/web-to-case/' ) ); ?>"><?php esc_html_e( 'Portal Support', 'mia-aesthetics' ); ?></a></li>
					</ul>
				</li>
				<li class="nav-item"><a class="nav-link" href="https://shop.miaaesthetics.com/" target="_blank" rel="noopener"><?php esc_html_e( 'Shop', 'mia-aesthetics' ); ?></a></li>
			</ul>

			<!-- Desktop CTA Button -->
			<a class="btn btn-outline-primary d-none d-xl-block ms-3" href="<?php echo esc_url( home_url( '/free-plastic-surgery-consultation/' ) ); ?>" aria-label="Schedule free virtual consultation">
				<?php esc_html_e( 'Free Virtual Consultation', 'mia-aesthetics' ); ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
			</a>

			<!-- Mobile menu toggle -->
			<button class="navbar-toggler d-xl-none ms-auto" type="button" data-bs-toggle="offcanvas" data-bs-target="#siteMenu" aria-controls="siteMenu" aria-expanded="false" aria-label="Open menu">
				<span class="navbar-toggler-icon" aria-hidden="true"></span>
			</button>

			<!-- Offcanvas (mobile) navigation -->
			<div class="offcanvas offcanvas-end" tabindex="-1" id="siteMenu" aria-labelledby="siteMenuLabel">
				<div class="offcanvas-header border-bottom border-secondary">
					<h2 class="offcanvas-title h6 mb-0" id="siteMenuLabel"><?php esc_html_e( 'Main Menu', 'mia-aesthetics' ); ?></h2>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close menu"></button>
				</div>
				<div class="offcanvas-body">
					<ul class="navbar-nav mb-3" role="list">
						<?php
							$menu_structure = mia_aesthetics_get_menu_structure();
							// Mobile variants.
							mia_aesthetics_render_procedures_menu( $menu_structure['procedures'], true );
							mia_aesthetics_render_non_surgical_menu( true );
							mia_aesthetics_render_locations_menu( true );
							mia_aesthetics_render_surgeons_menu( true );
							mia_aesthetics_render_before_after_menu( true );
						?>
						<li class="nav-item <?php echo mia_aesthetics_is_current_section( 'financing' ) ? 'current-menu-ancestor' : ''; ?>">
							<a class="nav-link" href="<?php echo esc_url( home_url( '/financing/' ) ); ?>" <?php echo mia_aesthetics_is_current_url( home_url( '/financing/' ) ) ? 'aria-current="page"' : ''; ?>><?php esc_html_e( 'Financing', 'mia-aesthetics' ); ?></a>
						</li>
						<li class="nav-item <?php echo mia_aesthetics_is_current_section( 'specials' ) ? 'current-menu-ancestor' : ''; ?>">
							<a class="nav-link" href="<?php echo esc_url( home_url( '/specials/' ) ); ?>" <?php echo mia_aesthetics_is_current_url( home_url( '/specials/' ) ) ? 'aria-current="page"' : ''; ?>><?php esc_html_e( 'Specials', 'mia-aesthetics' ); ?></a>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="patient-portal-dropdown-mobile" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true" aria-controls="patient-portal-menu-mobile"><?php esc_html_e( 'Patient Portal', 'mia-aesthetics' ); ?></a>
							<ul class="dropdown-menu" aria-labelledby="patient-portal-dropdown-mobile" id="patient-portal-menu-mobile">
								<li><a class="dropdown-item" href="https://patient.miaaesthetics.com/s/login?ec=302&amp;startURL=%2Fs%2Fhome" target="_blank" rel="noopener"><?php esc_html_e( 'Portal Login', 'mia-aesthetics' ); ?></a></li>
								<li><a class="dropdown-item" href="<?php echo esc_url( home_url( '/web-to-case/' ) ); ?>"><?php esc_html_e( 'Portal Support', 'mia-aesthetics' ); ?></a></li>
							</ul>
						</li>
						<li class="nav-item"><a class="nav-link" href="https://shop.miaaesthetics.com/" target="_blank" rel="noopener"><?php esc_html_e( 'Shop', 'mia-aesthetics' ); ?></a></li>
					</ul>

					<a class="btn btn-outline-primary w-100" href="<?php echo esc_url( home_url( '/free-plastic-surgery-consultation/' ) ); ?>" aria-label="Schedule free virtual consultation">
						<?php esc_html_e( 'Free Virtual Consultation', 'mia-aesthetics' ); ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
					</a>
				</div>
			</div>
		</div>
	</nav>
</header>
