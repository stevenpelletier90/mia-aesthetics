<?php
/**
 * Conditions Archive Template
 * Displays conditions in a hierarchical structure with parents and children
 */

get_header(); ?>

<main>
<?php mia_breadcrumbs(); ?>
    
    <!-- Archive Header -->
    <section class="post-header py-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="mb-2">Conditions We Treat</h1>
                    <p class="lead mb-0">Browse our comprehensive list of conditions organized by category.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Conditions Content -->
    <section class="py-5">
        <div class="container">
            <?php
            // Get all parent conditions (no parent)
            $parent_conditions = new WP_Query([
                'post_type' => 'condition',
                'post_parent' => 0,
                'posts_per_page' => -1,
                'orderby' => 'title',
                'order' => 'ASC'
            ]);

            if ($parent_conditions->have_posts()) : ?>
                <div class="conditions-grid">
                    <?php while ($parent_conditions->have_posts()) : $parent_conditions->the_post(); 
                        $parent_id = get_the_ID();
                        
                        // Get child conditions for this parent
                        $child_conditions = new WP_Query([
                            'post_type' => 'condition',
                            'post_parent' => $parent_id,
                            'posts_per_page' => -1,
                            'orderby' => 'title',
                            'order' => 'ASC'
                        ]);
                        
                        $has_children = $child_conditions->have_posts();
                    ?>
                        <div class="condition-group mb-4">
                            <div class="condition-parent">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h2 class="condition-title mb-0">
                                        <?php the_title(); ?>
                                    </h2>
                                    <a href="<?php the_permalink(); ?>" class="view-main-condition btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-external-link me-1"></i>
                                        View Main Page
                                    </a>
                                </div>
                            </div>
                            
                            <?php if ($has_children) : ?>
                                <div class="condition-children">
                                    <div class="row g-2">
                                        <?php while ($child_conditions->have_posts()) : $child_conditions->the_post(); ?>
                                            <div class="col-lg-3 col-md-4 col-sm-6">
                                                <div class="condition-item">
                                                    <a href="<?php the_permalink(); ?>" class="condition-link">
                                                        <span class="condition-name"><?php the_title(); ?></span>
                                                        <i class="fa-solid fa-arrow-right"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                </div>
                                <?php wp_reset_postdata(); ?>
                            <?php else : ?>
                                <!-- If parent has no children, show it as a standalone item -->
                                <div class="condition-children">
                                    <div class="row g-2">
                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                            <div class="condition-item standalone">
                                                <a href="<?php the_permalink(); ?>" class="condition-link">
                                                    <span class="condition-name"><?php the_title(); ?></span>
                                                    <i class="fa-solid fa-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
                
            <?php else: ?>
                <div class="row">
                    <div class="col-12">
                        <p class="text-center text-muted">No conditions found.</p>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php wp_reset_postdata(); ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>