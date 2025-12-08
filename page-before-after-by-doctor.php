<?php
/**
 * Template Name: Before & After Gallery by Doctor
 * Template Post Type: case
 * Description: Displays the static before/after gallery by doctor for the /before-after/before-after-by-doctor/ URL.
 * Uses Bootstrap 5.3, semantic HTML, and accessibility best practices.
 * All gallery markup is static for performance and maintainability.
 *
 * @package MiaAesthetics
 */

get_header();
?>

<main id="primary" role="main" tabindex="-1">
<?php mia_aesthetics_breadcrumbs(); ?>

	<!-- Page Hero / Title ----------------------------------------->
	<section class="post-header py-5">
	<div class="container">
		<h1 class="text-center">
		Before&nbsp;&&nbsp;After
		<small class="d-block fs-4">Results by Surgeon</small>
		</h1>
	</div>
	</section>

	<div class="container py-5">
	<section id="doctors-gallery">

	<!-- Surgeon selector -->
	<div class="d-flex justify-content-center mb-5">
		<div>
		<label for="doctorSelect" class="visually-hidden">Choose a surgeon</label>
		<select id="doctorSelect" class="form-select fs-5 px-4 py-3" aria-controls="galleries">
			<option value="">Choose a Surgeon</option>
			<?php
			// Get surgeons dynamically from the surgeon custom post type.
			$surgeons = mia_aesthetics_get_surgeons_direct();


			// Load gallery data to check which surgeons have cases.
			$gallery_json_path = get_template_directory() . '/assets/data/before-after-gallery.json';
			$gallery_data      = array();

			if ( file_exists( $gallery_json_path ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
				WP_Filesystem();
				$json = $GLOBALS['wp_filesystem']->get_contents( $gallery_json_path );
				if ( false !== $json ) {
					$gallery_data = json_decode( $json, true );
				}
			}

			if ( count( $surgeons ) > 0 ) {
				foreach ( $surgeons as $surgeon ) {
					// Parse surgeon name format: "FirstName LastName, MD" or "FirstName LastName, DO, FACS".
					// First, split by comma to separate name from suffixes.
					$name_parts = explode( ',', $surgeon['name'] );
					$full_name  = trim( $name_parts[0] ); // This gives us "FirstName LastName".

					// Now extract just the last name from the full name.
					$name_components = explode( ' ', $full_name );
					$last_name       = end( $name_components );

					// Clean the last name of any special characters.
					$last_name = preg_replace( '/[^a-zA-Z0-9]/', '', $last_name ) ?? '';

					// Generate data-doctor value as "dr-lastname" format to match JSON keys.
					$surgeon_slug = 'dr-' . strtolower( $last_name );

					// Check if this surgeon has gallery data.
					$has_gallery = isset( $gallery_data[ $surgeon_slug ] );

					// Display name as "Dr. LastName" only.
					$display_name = 'Dr. ' . $last_name;
					if ( ! $has_gallery ) {
						$display_name .= ' (Coming Soon)';
					}

					// Debug output for each surgeon.
					echo '<!-- Surgeon: ' . esc_html( $surgeon['name'] ?? '' ) . ' => LastName: ' . esc_html( $last_name ) . ' => Slug: ' . esc_html( $surgeon_slug ) . ' => Has Gallery: ' . ( $has_gallery ? 'Yes' : 'No' ) . ' -->';
					?>
					<option value="<?php echo esc_attr( $surgeon_slug ); ?>"><?php echo esc_html( $display_name ); ?></option>
					<?php
				}
			}
			?>
		</select>
		</div>
	</div>

	<!-- ================= Galleries ================= -->
	<div id="galleries">
<?php
// Dynamically generate gallery from JSON data for maintainability.
$gallery_json_path = get_template_directory() . '/assets/data/before-after-gallery.json';
$gallery_data      = array();

if ( file_exists( $gallery_json_path ) ) {
	// Use WP_Filesystem API for better security and compatibility.
	require_once ABSPATH . 'wp-admin/includes/file.php';
	WP_Filesystem();
	$json = $GLOBALS['wp_filesystem']->get_contents( $gallery_json_path );
	if ( false !== $json ) {
		$gallery_data = json_decode( $json, true );
	}
}

if ( is_array( $gallery_data ) && count( $gallery_data ) > 0 ) :
	foreach ( $gallery_data as $doctor_slug => $doctor ) :
		?>
		<article class="gallery d-none" data-doctor="<?php echo esc_attr( $doctor_slug ); ?>">
		<h2 class="h2 text-center mt-5 mb-5"><?php echo esc_html( $doctor['name'] ); ?></h2>
		<?php foreach ( $doctor['procedures'] as $procedure => $images ) : ?>
			<h3 class="h4 border-bottom pb-2 mb-4<?php echo array_key_first( $doctor['procedures'] ) !== $procedure ? ' mt-5' : ''; ?>">
			<?php echo esc_html( $procedure ); ?>
			</h3>
			<div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3">
			<?php foreach ( $images as $i => $img_url ) : ?>
				<div class="col">
				<button
					class="btn p-0 border-0"
					type="button"
					data-bs-toggle="modal"
					data-bs-target="#lightboxModal"
					data-bs-index="<?php echo esc_attr( $i ); ?>"
					data-bs-gallery="<?php echo esc_attr( $doctor_slug . '-' . strtolower( preg_replace( '/[^a-z0-9]+/i', '-', $procedure ) ) ); ?>"
				>
					<img
					src="<?php echo esc_url( $img_url ); ?>"
					alt="<?php echo esc_attr( $doctor['name'] . ' • ' . $procedure . ' • image ' . ( $i + 1 ) ); ?>"
					class="img-fluid rounded shadow-sm"
					loading="lazy"
					/>
				</button>
				</div>
			<?php endforeach; ?>
			</div>
		<?php endforeach; ?>
		</article>
		<?php
	endforeach;

	// Add "Coming Soon" galleries for surgeons without data.
	// Get all surgeons to find those without galleries.
	$all_surgeons = mia_aesthetics_get_surgeons_direct();
	foreach ( $all_surgeons as $surgeon ) {
		// Parse surgeon name to get slug.
		$name_parts      = explode( ',', $surgeon['name'] );
		$full_name       = trim( $name_parts[0] );
		$name_components = explode( ' ', $full_name );
		$last_name       = end( $name_components );
		$last_name       = preg_replace( '/[^a-zA-Z0-9]/', '', $last_name ) ?? '';
		$surgeon_slug    = 'dr-' . strtolower( $last_name );

		// If this surgeon doesn't have gallery data, create a coming soon message.
		if ( ! isset( $gallery_data[ $surgeon_slug ] ) ) {
			?>
			<article class="gallery d-none" data-doctor="<?php echo esc_attr( $surgeon_slug ); ?>">
				<h2 class="h2 text-center mt-5 mb-5">Dr. <?php echo esc_html( '' !== $last_name ? $last_name : 'Unknown' ); ?></h2>
				<div class="alert alert-info text-center" role="alert">
					<h3 class="h4 mb-3">Before & After Images Coming Soon!</h3>
					<p class="mb-0">We're currently preparing the before and after gallery for Dr. <?php echo esc_html( '' !== $last_name ? $last_name : 'Unknown' ); ?>. Please check back soon to see amazing transformation results.</p>
				</div>
			</article>
			<?php
		}
	}
else :
	?>
		<div class="alert alert-warning text-center" role="alert">
		<h3>Gallery data not found</h3>
		<p>The gallery data file could not be loaded. Please check that <code>/assets/data/before-after-gallery.json</code> exists and is properly formatted.</p>
		</div>
	<?php
endif;
?>
	</div>
	</section>

	<!-- ============ Lightbox (Bootstrap Modal with Carousel) ============ -->
	<div class="modal fade" id="lightboxModal" tabindex="-1" aria-labelledby="lightboxModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="lightboxModalLabel"></h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div id="galleryCarousel" class="carousel slide carousel-fade" data-bs-ride="false">
			<div class="carousel-inner" id="carouselInner">
				<!-- Carousel items will be dynamically inserted here -->
			</div>
			<button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel" data-bs-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="visually-hidden">Previous</span>
			</button>
			<button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel" data-bs-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="visually-hidden">Next</span>
			</button>
			</div>
		</div>
		</div>
	</div>
	</div>
	<!--
	Custom gallery JS and CSS are now in assets/js/gallery.js and assets/css/gallery.css.
	Enqueue these in inc/enqueue.php for this template.
	-->
	</div>
</main>

<?php get_footer(); ?>
