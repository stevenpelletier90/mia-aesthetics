<?php
/**
 * Template Name: Page Default (No Breadcrumbs)
 * Template Post Type: page, post, procedure, non-surgical, fat-transfer
 * Description: Full‑width canvas template that keeps the flexibility of a blank
 * canvas but adds structured breadcrumbs and a hero header. Built for
 * Gutenberg or Classic editor content blocks wrapped in Bootstrap containers.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Mia_Aesthetics
 */

get_header(); ?>

<main>
    <?php while (have_posts()) : the_post(); ?>
        <!-- Page Header -->
        <section class="post-header py-5">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h1 class="mb-2"><?php the_title(); ?></h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Content -->
        <article class="py-5">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="mb-4">
                                <?php the_post_thumbnail('large', ['class' => 'img-fluid']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="content">
                            <?php the_content(); ?>
                        </div>
                        
                        <?php
                        // Display FAQs if available
                        echo display_page_faqs();
                        ?>
                    </div>
                </div>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
