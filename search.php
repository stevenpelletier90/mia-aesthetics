<?php
/**
 * Search results template
 */

get_header(); ?>

<main class="search-results">
<?php mia_breadcrumbs(); ?>
    
    <!-- Page Header -->
    <section class="post-header py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h1>
                        <?php printf(
                            esc_html__('Search Results for: %s', 'mia-aesthetics'),
                            '<span style="color: var(--color-gold);">' . get_search_query() . '</span>'
                        ); ?>
                    </h1>
                    <?php
                    global $wp_query;
                    $total_results = $wp_query->found_posts;
                    if ($total_results > 0) {
                        printf(
                            '<p class="lead mb-0">Found %d %s matching your search.</p>',
                            $total_results,
                            $total_results === 1 ? 'result' : 'results'
                        );
                    } else {
                        echo '<p class="lead mb-0">No results found for your search query.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

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
                                    <div class="text-muted small">
                                        <?php echo get_post_type_object(get_post_type())->labels->singular_name; ?>
                                    </div>
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
                            'class' => 'pagination justify-content-center',
                        ]); ?>
                    </div>
                </div>

            <?php else : ?>
                <div class="row">
                    <div class="col">
                        <div class="no-results">
                            <h3>No Results Found</h3>
                            <p>We couldn't find any content matching your search. Try different keywords or browse our procedures and services.</p>
                            <div class="mt-4">
                                <a href="<?php echo esc_url(home_url('/procedures/')); ?>" class="mia-button me-3" data-variant="gold-outline">
                                    Browse Procedures
                                </a>
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="mia-button" data-variant="gold-outline">
                                    Return Home
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>