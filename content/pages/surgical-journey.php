<?php
/**
 * Surgical Journey Page Content
 *
 * @package Mia_Aesthetics
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- Page Header -->
<section class="post-header py-5">
	<div class="container">
		<h1><?php echo esc_html( get_the_title() ); ?></h1>
	</div>
</section>

<!-- Video -->
<section class="py-4">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-8">
				<div class="ratio ratio-16x9">
					<iframe src="https://www.youtube.com/embed/eK4bAgtMUf4" title="Surgical Journey" allowfullscreen></iframe>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Journey Steps -->
<section class="surgical-journey-steps py-5">
	<div class="container">
		<!-- Step 1 -->
		<div class="journey-step mb-5">
			<h2>STEP 1 – Free Virtual Consultation</h2>
			<p>
				First, you'll fill out and submit a few medical forms along with your high-quality consultation photos. And don't worry, we have a video to explain exactly how to take your consultation photos. Once we receive your forms and photos, a surgical coordinator will reach out to schedule your free online consultation from the comfort of your home.
			</p>
			<p>
				During the consultation, you'll discuss your goals and surgical options, including which procedure and surgeon you'd like, as well as what dates are available for your surgery. We'll also tell you if there are any discounts or specials going on.
			</p>
		</div>

		<!-- Step 2 -->
		<div class="journey-step mb-5">
			<h2>STEP 2 – Scheduling</h2>
			<p>
				So which of our 25 plus skilled plastic surgeons, across multiple national locations should you choose? Check out their bios <a href="<?php echo esc_url( home_url( '/plastic-surgeons/' ) ); ?>">here</a>, as well as info about all the different procedures we perform <a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/' ) ); ?>">here</a>. You can also find all our surgeons on social media to see <a href="<?php echo esc_url( home_url( '/before-after/' ) ); ?>">before and after photos</a> of their patients.
			</p>
			<p>
				Once you've decided on your procedure and surgeon, you'll call your assigned surgical coordinator to set a surgery date and pay your deposit to secure your spot.
			</p>
		</div>

		<!-- Step 3 -->
		<div class="journey-step mb-5">
			<h2>STEP 3 – Preparation</h2>
			<p>
				After your surgery date is confirmed, our concierge team will keep you well informed and answer any questions you may have before your big day. They'll send you emails with important info like medications to avoid before surgery and more.
			</p>
			<p>
				During this time our labs department will reach out to inform you about required labs and necessary tests you may need before surgery to ensure a safe experience.
			</p>
		</div>

		<!-- Step 4 -->
		<div class="journey-step mb-5">
			<h2>STEP 4 – Pre-Op, Surgery, Post-Op</h2>
			<p>
				Before your surgery you'll come in for a Pre-Op visit to make sure you meet all the necessary health requirements to undergo surgery.
			</p>
			<p>
				After surgery, our Post-Op team will give you instructions to follow to make sure you have a safe and comfortable recovery and will schedule your Post-Op appointment.
			</p>
		</div>
	</div>
</section>

<!-- Video -->
<section class="py-4">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-8">
				<div class="ratio ratio-16x9">
					<iframe src="https://www.youtube.com/embed/KB4AUA29uxg" title="Post-Op Recovery" allowfullscreen></iframe>
				</div>
			</div>
		</div>
	</div>
</section>
