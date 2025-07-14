<?php
/**
 * Template Name: Case Category
 * Template Post Type: case
 * Description: Displays a grid of Case posts that share the same “case-category” terms
 *              attached to this Page. Mirrors the default page.php layout (breadcrumbs,
 *              hero header, featured image, content) and then injects the dynamic grid.
 *
 * @package Mia_Aesthetics
 */

get_header();
?>

<main>
    <!-- Breadcrumbs ---------------------------------------------------->
<?php mia_breadcrumbs(); ?>

    <?php while ( have_posts() ) : the_post(); ?>
        <!-- Page Hero / Title ----------------------------------------->
        <section class="post-header py-5">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h1><?php echo esc_html( get_the_title() ); ?></h1>
                    </div>
                </div>
            </div>
        </section>

    <?php endwhile; ?>

    <?php
    /* -----------------------------------------------------------------
     * CASE GRID QUERY
     * ----------------------------------------------------------------*/
    $term_ids = wp_get_post_terms( get_the_ID(), 'case-category', [ 'fields' => 'ids' ] );

    if ( empty( $term_ids ) ) {
        $case_query = null; // No grid.
    } else {
        $paged = max( 1, get_query_var( 'paged' ) );

        $case_query = new WP_Query( [
            'post_type'              => 'case',
            'post_status'            => 'publish',
            'posts_per_page'         => 12,
            'post__not_in'           => [ get_the_ID() ],
            'paged'                  => $paged,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
            'tax_query'              => [
                [
                    'taxonomy' => 'case-category',
                    'field'    => 'term_id',
                    'terms'    => $term_ids,
                ],
            ],
        ] );
    }
    ?>

    <?php if ( $case_query ) : ?>
        <section class="py-5">
            <div class="container">
                <?php if ( $case_query->have_posts() ) : ?>
                    <div id="cases-grid" class="row g-4">
                        <?php while ( $case_query->have_posts() ) : $case_query->the_post(); ?>
                            <?php
                            // Get case information ACF fields
                            $case_info = get_field( 'case_information' );
                            $surgeon = $case_info['performed_by_surgeon'] ?? null;
                            $location = $case_info['performed_at_location'] ?? null;
                            $procedure_performed = $case_info['procedure_performed'] ?? array();
                            ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'medium', [
                                            'class'   => 'card-img-top',
                                            'loading' => 'lazy',
                                            'alt'     => esc_attr( get_the_title() ),
                                        ] ); ?>
                                    <?php endif; ?>

                                    <div class="card-body d-flex flex-column">
                                        <h2 class="card-title">
                                            <a href="<?php echo esc_url( get_permalink() ); ?>">
                                                <?php echo esc_html( get_the_title() ); ?>
                                            </a>
                                        </h2>

                                        <?php if ( $surgeon ) : ?>
                                            <div class="case-meta">
                                                <i class="fas fa-user-md" aria-hidden="true"></i>
                                                <?php echo esc_html( get_the_title( $surgeon ) ); ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ( ! empty( $procedure_performed ) ) : ?>
                                            <div class="case-meta">
                                                <i class="fas fa-procedures" aria-hidden="true"></i>
                                                <?php 
                                                $procedure_names = array();
                                                foreach ( (array) $procedure_performed as $procedure_id ) {
                                                    $procedure_names[] = get_the_title( $procedure_id );
                                                }
                                                echo esc_html( implode( ', ', $procedure_names ) );
                                                ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ( $location ) : ?>
                                            <div class="case-meta">
                                                <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                                                <?php echo esc_html( get_the_title( $location ) ); ?>
                                            </div>
                                        <?php endif; ?>

                                        <a href="<?php echo esc_url( get_permalink() ); ?>" class="btn btn-primary btn-view-case mt-auto">
                                            View Case <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>

                    <!-- Pagination --------------------------------------->
                    <?php if ( $case_query->max_num_pages > 1 ) : ?>
                        <div class="row mt-5">
                            <div class="col">
                                <?php the_posts_pagination( [
                                    'prev_text' => '&laquo;',
                                    'next_text' => '&raquo;',
                                    'class'     => 'pagination justify-content-center',
                                ] ); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else : ?>
                    <p class="lead text-center mb-0">
                        <?php esc_html_e( 'No cases found for the selected category.', 'mia-aesthetics' ); ?>
                    </p>
                <?php endif; wp_reset_postdata(); ?>
            </div>
        </section>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
