<?php
/**
 * The front page template file
 *
 * @package Mia_Aesthetics
 */

get_header();

// Get site stats.
$stats = mia_aesthetics_get_site_stats();
?>

<main id="primary" class="site-main">

	<section class="hero">
		<div class="container">
			<div class="hero__content">
				<h1 class="hero__title">Transform Your<br>Confidence</h1>
				<p class="hero__subtitle">Expert plastic surgery at prices you can afford.</p>
				<a href="/free-plastic-surgery-consultation/" class="btn btn-primary btn-lg">
					Book Free Consultation
				</a>
			</div>
		</div>
	</section>

	<section class="about">
		<div class="container">
			<div class="about__grid">
				<div class="about__content">
					<span class="about__tagline">Why Mia Aesthetics</span>
					<h2 class="about__title">Beauty Meets Affordability</h2>
					<p class="about__description">Our mission is to deliver the highest quality of plastic surgery at affordable prices, demonstrating that beauty and cost-effectiveness can coexist harmoniously.</p>
					<a href="/about/" class="btn btn-outline-primary">Learn More About Us</a>
				</div>
				<div class="about__stats">
					<div class="stat">
						<span class="stat__number" data-count="2017">0</span>
						<span class="stat__label">Year Founded</span>
					</div>
					<div class="stat">
						<span class="stat__number" data-count="<?php echo esc_attr( (string) $stats['surgeons'] ); ?>">0</span>
						<span class="stat__label">Expert Surgeons</span>
					</div>
					<div class="stat">
						<span class="stat__number" data-count="<?php echo esc_attr( (string) $stats['locations'] ); ?>">0</span>
						<span class="stat__label">Locations</span>
					</div>
					<div class="stat">
						<span class="stat__number" data-count="150" data-suffix="k+">0</span>
						<span class="stat__label">Happy Patients</span>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="procedures">
		<div class="container">
			<header class="procedures__header">
				<span class="procedures__tagline">Our Expertise</span>
				<h2 class="procedures__title">Procedures We Offer</h2>
				<p class="procedures__subtitle">Discover the perfect procedure for your aesthetic goals</p>
			</header>

			<div class="procedures__grid">
				<!-- Body -->
				<div class="procedure-card">
					<div class="procedure-card__header">
						<h3 class="procedure-card__title">Body</h3>
						<span class="procedure-card__tagline">Sculpt Your Ideal Figure</span>
					</div>
					<ul class="procedure-card__list">
						<li><a href="/cosmetic-plastic-surgery/body/brazilian-butt-lift-bbl/">Brazilian Butt Lift</a></li>
						<li><a href="/cosmetic-plastic-surgery/body/tummy-tuck/">Tummy Tuck</a></li>
						<li><a href="/cosmetic-plastic-surgery/body/lipo-360/">Lipo 360</a></li>
						<li><a href="/cosmetic-plastic-surgery/body/mommy-makeover/">Mommy Makeover</a></li>
						<li><a href="/cosmetic-plastic-surgery/body/awake-liposuction/">Awake Liposuction</a></li>
						<li><a href="/cosmetic-plastic-surgery/body/arm-lift/">Arm Lift</a></li>
					</ul>
					<a href="/cosmetic-plastic-surgery/body/" class="procedure-card__link">
						View All Body <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
					</a>
				</div>

				<!-- Breast -->
				<div class="procedure-card">
					<div class="procedure-card__header">
						<h3 class="procedure-card__title">Breast</h3>
						<span class="procedure-card__tagline">Enhance Your Silhouette</span>
					</div>
					<ul class="procedure-card__list">
						<li><a href="/cosmetic-plastic-surgery/breast/augmentation-implants/">Breast Augmentation</a></li>
						<li><a href="/cosmetic-plastic-surgery/breast/lift/">Breast Lift</a></li>
						<li><a href="/cosmetic-plastic-surgery/breast/reduction/">Breast Reduction</a></li>
						<li><a href="/cosmetic-plastic-surgery/breast/implant-revision-surgery/">Implant Revision</a></li>
						<li><a href="/cosmetic-plastic-surgery/breast/male-breast-procedures/">Male Breast Procedures</a></li>
					</ul>
					<a href="/cosmetic-plastic-surgery/breast/" class="procedure-card__link">
						View All Breast <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
					</a>
				</div>

				<!-- Face -->
				<div class="procedure-card">
					<div class="procedure-card__header">
						<h3 class="procedure-card__title">Face</h3>
						<span class="procedure-card__tagline">Rejuvenate Your Look</span>
					</div>
					<ul class="procedure-card__list">
						<li><a href="/cosmetic-plastic-surgery/face/facelift/">Facelift</a></li>
						<li><a href="/cosmetic-plastic-surgery/face/mini-facelift/">Mini Facelift</a></li>
						<li><a href="/cosmetic-plastic-surgery/face/neck-lift/">Neck Lift</a></li>
						<li><a href="/cosmetic-plastic-surgery/face/eyelid-lift-blepharoplasty/">Eyelid Lift</a></li>
						<li><a href="/cosmetic-plastic-surgery/face/brow-lift/">Brow Lift</a></li>
						<li><a href="/cosmetic-plastic-surgery/face/buccal-cheek-fat-removal/">Buccal Fat Removal</a></li>
					</ul>
					<a href="/cosmetic-plastic-surgery/face/" class="procedure-card__link">
						View All Face <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
					</a>
				</div>

				<!-- Non-Surgical -->
				<div class="procedure-card">
					<div class="procedure-card__header">
						<h3 class="procedure-card__title">Non-Surgical</h3>
						<span class="procedure-card__tagline">Results Without Downtime</span>
					</div>
					<ul class="procedure-card__list">
						<li><a href="/non-surgical/j-plasma-skin-tightening/">J-Plasma Skin Tightening</a></li>
						<li><a href="/weight-loss/">Medical Weight Loss</a></li>
					</ul>
					<a href="/non-surgical/" class="procedure-card__link">
						View All Non-Surgical <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
					</a>
				</div>

				<!-- Men -->
				<div class="procedure-card">
					<div class="procedure-card__header">
						<h3 class="procedure-card__title">Men</h3>
						<span class="procedure-card__tagline">Tailored For Men</span>
					</div>
					<ul class="procedure-card__list">
						<li><a href="/cosmetic-plastic-surgery/body/male-bbl/">Male Brazilian Butt Lift</a></li>
						<li><a href="/cosmetic-plastic-surgery/body/male-liposuction/">Male Liposuction</a></li>
						<li><a href="/cosmetic-plastic-surgery/body/male-tummy-tuck/">Male Tummy Tuck</a></li>
						<li><a href="/cosmetic-plastic-surgery/breast/male-breast-procedures/">Male Breast Procedures</a></li>
					</ul>
					<a href="/cosmetic-plastic-surgery/men/" class="procedure-card__link">
						View All Men's <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
					</a>
				</div>
			</div>
		</div>
	</section>

</main>

<?php get_footer(); ?>
