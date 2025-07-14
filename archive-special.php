<?php
/**
 * The template for displaying the Specials archive page.
 */

get_header(); ?>

<?php mia_breadcrumbs(); ?>

<div class="specials-archive-page">

    <!-- 1. Hero Section -->
    <header class="specials-hero text-center">
        <div class="container">
            <h1 class="display-4 fw-bold">Current Specials</h1>
            <p class="lead mb-4">Limited-time offers on your favorite treatments</p>
            <a href="/free-virtual-consultation/" class="mia-button" data-variant="gold" data-size="lg">
                Free Virtual Consultation <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </header>

    <!-- 2. Specials Archive Grid -->
    <main class="specials-grid py-5">
        <div class="container">

            <?php if ( have_posts() ) : ?>

                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 g-lg-5">

                    <?php while ( have_posts() ) : the_post(); ?>

                        <div class="col">
                            <article class="special-card h-100">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <div class="special-card-image">
                                        <a href="<?php the_permalink(); ?>" aria-label="View <?php echo esc_attr(get_the_title()); ?>">
                                            <?php the_post_thumbnail('large', ['class' => 'img-fluid']); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <div class="special-card-body">
                                    <?php the_title( '<h2 class="special-card-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>
                                    <div class="special-card-excerpt">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    <a href="<?php the_permalink(); ?>" class="mia-button" data-variant="gold-outline">
                                        View Special <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </article>
                        </div>

                    <?php endwhile; ?>

                </div> <!-- .row -->

            <?php else : ?>

                <div class="text-center">
                    <h2>No Specials Found</h2>
                    <p>Please check back later for new offers.</p>
                </div>

            <?php endif; ?>

        </div> <!-- .container -->
    </main>

</div><!-- .specials-archive-page -->

<?php get_footer(); ?>