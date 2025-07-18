<?php
/**
 * Non-Surgical Archive Template
 * Organizes non-surgical procedures into categories
 */

get_header(); ?>

<main>    
    <!-- Archive Header -->
    <section class="post-header py-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="mb-2">Our Non-Surgical Procedures</h1>
                    <p class="lead mb-0">Transform your appearance with our advanced non-surgical treatments and procedures.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Non-Surgical Procedures Grid -->
    <section class="procedures-listing-section">
        <div class="container">
            <div class="row g-4">
                <?php
                if ( have_posts() ) :
                    while ( have_posts() ) : the_post();
                        $procedure_title = get_the_title();
                        $procedure_excerpt = get_the_excerpt();
                        $procedure_link = get_permalink();
                        $procedure_image = get_the_post_thumbnail( get_the_ID(), 'medium_large', array(
                            'class' => 'img-fluid',
                            'alt' => esc_attr( $procedure_title )
                        ));
                        
                        // Fallback image if no featured image
                        if ( ! $procedure_image ) {
                            $procedure_image = '<img src="' . get_template_directory_uri() . '/assets/images/placeholder-procedure.jpg" alt="' . esc_attr( $procedure_title ) . '" class="img-fluid">';
                        }
                        ?>
                        
                        <div class="col-lg-4 col-md-6">
                            <article class="procedure-card h-100">
                                <figure class="procedure-image">
                                    <a href="<?php echo esc_url( $procedure_link ); ?>" aria-label="Learn more about <?php echo esc_attr( $procedure_title ); ?>">
                                        <?php echo $procedure_image; ?>
                                    </a>
                                </figure>
                                <div class="procedure-content">
                                    <h3 class="procedure-title">
                                        <a href="<?php echo esc_url( $procedure_link ); ?>">
                                            <?php echo esc_html( $procedure_title ); ?>
                                        </a>
                                    </h3>
                                    <a href="<?php echo esc_url( $procedure_link ); ?>" class="mia-button" data-variant="gold-outline" data-size="sm" role="button">
                                        Learn More
                                    </a>
                                </div>
                            </article>
                        </div>
                        
                        <?php
                    endwhile;
                    
                    // Add Weight Loss post at the end for alphabetical order
                    $weight_loss_post = get_post(233);
                    if ($weight_loss_post && $weight_loss_post->post_status === 'publish') :
                        setup_postdata($weight_loss_post);
                        $procedure_title = get_the_title(233);
                        $procedure_excerpt = get_the_excerpt(233);
                        $procedure_link = get_permalink(233);
                        $procedure_image = get_the_post_thumbnail(233, 'medium_large', array(
                            'class' => 'img-fluid',
                            'alt' => esc_attr($procedure_title)
                        ));
                        
                        // Fallback image if no featured image
                        if (!$procedure_image) {
                            $procedure_image = '<img src="' . get_template_directory_uri() . '/assets/images/placeholder-procedure.jpg" alt="' . esc_attr($procedure_title) . '" class="img-fluid">';
                        }
                        ?>
                        
                        <div class="col-lg-4 col-md-6">
                            <article class="procedure-card h-100">
                                <figure class="procedure-image">
                                    <a href="<?php echo esc_url($procedure_link); ?>" aria-label="Learn more about <?php echo esc_attr($procedure_title); ?>">
                                        <?php echo $procedure_image; ?>
                                    </a>
                                </figure>
                                <div class="procedure-content">
                                    <h3 class="procedure-title">
                                        <a href="<?php echo esc_url($procedure_link); ?>">
                                            <?php echo esc_html($procedure_title); ?>
                                        </a>
                                    </h3>
                                    <a href="<?php echo esc_url($procedure_link); ?>" class="mia-button" data-variant="gold-outline" data-size="sm" role="button">
                                        Learn More
                                    </a>
                                </div>
                            </article>
                        </div>
                        
                        <?php
                        wp_reset_postdata();
                    endif;
                    
                else :
                    ?>
                    <div class="col-12 text-center">
                        <p class="lead">No non-surgical procedures found.</p>
                    </div>
                    <?php
                endif;
                ?>
            </div>
        </div>
    </section>


</main>

<?php get_footer(); ?>