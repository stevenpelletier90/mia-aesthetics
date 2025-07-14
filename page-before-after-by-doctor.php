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

<main id="main-content" role="main" tabindex="-1">
<?php mia_breadcrumbs(); ?>

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
          <option value="dr-bronstein">Dr. Bronstein</option>
          <option value="dr-charepoo">Dr. Charepoo</option>
          <option value="dr-cooper">Dr. Cooper</option>
          <option value="dr-elbayer">Dr. Elbayer</option>
          <option value="dr-fasusi">Dr. Fasusi</option>
          <option value="dr-giorgis">Dr. Giorgis</option>
          <option value="dr-gray">Dr. Gray</option>
          <option value="dr-gross">Dr. Gross</option>
          <option value="dr-horowitz">Dr. Horowitz</option>
          <option value="dr-kramer">Dr. Kramer</option>
          <option value="dr-kumar">Dr. Kumar</option>
          <option value="dr-mehio">Dr. Mehio</option>
          <option value="dr-morse">Dr. Morse</option>
          <option value="dr-orlando">Dr. Orlando</option>
          <option value="dr-patino">Dr. Patino</option>
          <option value="dr-rozentsvit">Dr. Rozentsvit</option>
          <option value="dr-sarcia">Dr. Sarcia</option>
          <option value="dr-scroggins">Dr. Scroggins</option>
          <option value="dr-shaikh">Dr. Shaikh</option>
          <option value="dr-towle">Dr. Towle</option>
          <option value="dr-valauri">Dr. Valauri</option>
          <option value="dr-wright">Dr. Wright</option>
          <option value="dr-xu">Dr. Xu</option>
          <option value="dr-ziakas">Dr. Ziakas</option>
        </select>
      </div>
    </div>

    <!-- ================= Galleries ================= -->
    <div id="galleries">
<?php
// Dynamically generate gallery from JSON data for maintainability.
$gallery_json_path = get_template_directory() . '/assets/data/before-after-gallery.json';
$gallery_data = [];

// Debug gallery data loading
error_log('=== Gallery Data Loading Debug ===');
error_log('Gallery JSON path: ' . $gallery_json_path);
error_log('File exists: ' . (file_exists($gallery_json_path) ? 'Yes' : 'No'));

if (file_exists($gallery_json_path)) {
    $json = file_get_contents($gallery_json_path);
    error_log('JSON content length: ' . strlen($json));
    $gallery_data = json_decode($json, true);
    error_log('JSON decode successful: ' . ($gallery_data !== null ? 'Yes' : 'No'));
    if ($gallery_data !== null) {
        error_log('Number of doctors in data: ' . count($gallery_data));
        foreach ($gallery_data as $doctor_slug => $doctor) {
            error_log("Doctor {$doctor['name']} has " . count($doctor['procedures']) . " procedures");
        }
    } else {
        error_log('JSON decode error: ' . json_last_error_msg());
    }
} else {
    error_log('Gallery JSON file not found');
}
error_log('=== End Gallery Data Debug ===');

if (!empty($gallery_data)) :
    foreach ($gallery_data as $doctor_slug => $doctor) :
?>
      <article class="gallery d-none" data-doctor="<?php echo esc_attr($doctor_slug); ?>">
        <h2 class="h2 text-center mt-5 mb-5"><?php echo esc_html($doctor['name']); ?></h2>
        <?php foreach ($doctor['procedures'] as $procedure => $images) : ?>
          <h3 class="h4 border-bottom pb-2 mb-4<?php echo $procedure !== array_key_first($doctor['procedures']) ? ' mt-5' : ''; ?>">
            <?php echo esc_html($procedure); ?>
          </h3>
          <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3">
            <?php foreach ($images as $i => $img_url) : ?>
              <div class="col">
                <button
                  class="btn p-0 border-0"
                  type="button"
                  data-bs-toggle="modal"
                  data-bs-target="#lightboxModal"
                  data-bs-index="<?php echo esc_attr($i); ?>"
                  data-bs-gallery="<?php echo esc_attr($doctor_slug . '-' . strtolower(preg_replace('/[^a-z0-9]+/i', '-', $procedure))); ?>"
                >
                  <img
                    src="<?php echo esc_url($img_url); ?>"
                    alt="<?php echo esc_attr($doctor['name'] . ' • ' . $procedure . ' • image ' . ($i + 1)); ?>"
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
