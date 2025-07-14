<?php
/**
 * Taxonomy Archive Template for "case-category"
 */

get_header();

// Grab current term object
$term = get_queried_object();
?>
<main>
	
	<?php mia_breadcrumbs(); ?>
    <!-- Archive Header -->
    <section class="post-header py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Show the term name -->
                    <h1 class="mb-3">
                        <?php single_term_title(); ?>
                    </h1>
                    
                    <!-- Optional: display term description if present -->
                    <?php if (!empty($term->description)) : ?>
                        <div class="taxonomy-description">
                            <?php echo wp_kses_post($term->description); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Archive Content -->
    <section class="py-5">
        <div class="container">
            <?php if (have_posts()) : ?>
                <div class="row g-4">
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium', ['class' => 'card-img-top']); ?>
                                <?php endif; ?>

                                <div class="card-body">
                                    <h2 class="h5">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                        </a>
                                    </h2>
                                    <?php the_excerpt(); ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <div class="row mt-5">
                    <div class="col">
                        <?php the_posts_pagination([
                            'prev_text' => '&laquo;',
                            'next_text' => '&raquo;',
                            'class'     => 'pagination justify-content-center',
                        ]); ?>
                    </div>
                </div>

            <?php else : ?>
                <div class="row">
                    <div class="col">
                        <p>No posts found in this category.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
