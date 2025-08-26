<?php
/**
 * The template for displaying the Specials archive page.
 *
 * @package Mia_Aesthetics
 */

get_header();
?>

<div class="specials-archive-page">

	<!-- 1. Hero Section -->
	<header class="specials-hero text-center">
		<div class="container">
			<h1 class="display-4 fw-bold">Current Specials</h1>
			<p class="lead mb-4">Limited-time offers on your favorite treatments</p>
			<a href="/free-virtual-consultation/" class="btn btn-primary btn-lg">
				Free Virtual Consultation <i class="fas fa-arrow-right"></i>
			</a>
		</div>
	</header>

	<!-- 2. Language Toggle -->
	<div class="specials-language-toggle py-3">
		<div class="container">
			<div class="btn-group" role="group" aria-label="Language filter">
				<button type="button" class="btn btn-primary active" data-lang="english" id="english-tab">English</button>
				<button type="button" class="btn btn-outline-primary" data-lang="spanish" id="spanish-tab">Español</button>
			</div>
		</div>
	</div>

	<!-- 3. Specials Archive Grid -->
	<main class="specials-grid py-5">
		<div class="container">

			<!-- Content will be handled by your custom implementation -->

		</div> <!-- .container -->
	</main>

</div><!-- .specials-archive-page -->

<?php get_footer(); ?>