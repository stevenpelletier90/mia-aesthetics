<?php
/**
 * Thigh Lift Procedure Content
 *
 * @package Mia_Aesthetics
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- Hero/Intro Section -->
<div class="container">
  <section class="py-5">
    <div class="row align-items-center g-3 g-lg-5">
      <div class="col-lg-7 mb-3 mb-lg-0">
<?php echo mia_render_inline_breadcrumbs(); ?>
        <h2 class="display-6 fw-bold mb-4">What is a Thigh Lift?</h2>
        <p class="fs-5 mb-0">
          Thigh lift surgery is a solution to sagging thigh skin. The procedure helps redefine,
          tighten and re-contour the thighs and lower body. Benefits of a thigh lift are improved
          contour and smoother, tighter skin on the thighs. Patients feel more confident wearing
          shorts or skirts and typically experience a significant boost in self-esteem.
        </p>
      </div>
      <div class="col-lg-5">
        <img
          src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/09/thigh.jpg' ) ); ?>"
          alt="A woman in beige underwear poses against a matching background, emphasizing body tattoos and a confident stance with one hand on her hip."
          class="img-fluid w-100 rounded-3"
          loading="lazy"
        />
      </div>
    </div>
  </section>

  <!-- Quick Links Section -->
  <section class="procedure-section procedure-links-section py-5">
    <div class="row">
      <div class="col-12">
        <div class="section-header mb-5 text-center">
          <h2 class="display-6 fw-bold">Learn More About the Arm Lift</h2>
        </div>
      </div>
    </div>
    <div class="row g-3">
      <div class="col-sm-6 col-lg-4">
        <a
          href="<?php echo esc_url( home_url( '/is-a-thigh-lift-right-for-you/' ) ); ?>"
          class="procedure-link-item d-flex align-items-center justify-content-between p-3"
        >
          <span class="link-text">Is It Right For You?</span>
          <i class="fas fa-arrow-right link-arrow" aria-hidden="true"></i>
        </a>
      </div>
      <div class="col-sm-6 col-lg-4">
        <a
          href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/thigh-lift/how-to-prepare-for-surgery/' ) ); ?>"
          class="procedure-link-item d-flex align-items-center justify-content-between p-3"
        >
          <span class="link-text">How to Prepare For Surgery</span>
          <i class="fas fa-arrow-right link-arrow" aria-hidden="true"></i>
        </a>
      </div>
    </div>
  </section>
</div>

<!-- About the Procedure Section - Fixed Background -->
<section class="thigh-lift-fixed-bg fixed-bg-section py-5 d-flex flex-column align-items-center">
  <!-- Mobile image - shown above content on mobile -->
  <div class="thigh-lift-mobile-image mb-4 d-lg-none">
    <img
      src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/09/thigh-lift-1.jpg' ) ); ?>"
      alt="Thigh Lift Procedure"
      class="img-fluid w-100"
      loading="lazy"
    />
  </div>

  <div class="container flex-grow-1 d-flex align-items-center">
    <div class="row justify-content-center">
      <div class="col-lg-8 col-xl-7">
        <div class="about-procedure-content">
          <div class="section-header">
            <h2 class="fixed-bg-heading text-start text-lg-center">About The Thigh Procedure</h2>
          </div>

          <div class="fixed-bg-content-box p-0 p-lg-4">
            <p>
              Many thigh lift procedures are performed on an outpatient basis, allowing patients to
              return home the day of surgery. General anesthesia is used during the procedure. Step
              one is the incision, which may be placed in different areas depending on the amount of
              contouring and tightening required. Many incisions start in the groin area and extend
              to the buttocks or down the legs. Typically, the longer the incision, the easier it is
              to tighten the skin and contour the thigh. To avoid scarring, plastic surgeons will
              make the incision as short as possible. Once the incision is made, excess skin (and
              fat, if necessary) is removed, and the remaining skin is tightened and contoured to
              improve the overall shape of the thighs. Support sutures may be used to help shape and
              hold the tissues as well.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="container">
  <!-- Recovery Section -->
  <section class="procedure-section py-5">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 mb-4 mb-lg-0">
        <div class="recovery-content">
          <div class="section-header mb-4">
            <h2 class="display-6 fw-bold mt-2">Thigh Lift Recovery</h2>
          </div>
          <div class="recovery-overview">
            <p class="mb-3">
              Immediately after surgery, dressings are placed over your wounds. Patients will be
              given compression garments to provide support and reduce swelling throughout the
              healing process.
            </p>
            <p class="mb-3">
              In some cases, temporary drain tubes may be placed for a week or so. Patients should
              wait one to three weeks before returning to everyday activities, depending on the
              extent of the procedure and the patient's unique healing speed.
            </p>
            <p class="mb-0">
              Patients can speed up the process and improve the outcome of the surgery by following
              all surgeon instructions for aftercare.
            </p>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="recovery-visual position-relative">
          <div class="image-container rounded-3 overflow-hidden">
            <img
              src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/09/thigh-lift.jpg' ) ); ?>"
              alt="Thigh Lift Recovery and Healing Process"
              class="img-fluid w-100"
              loading="lazy"
            />
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
