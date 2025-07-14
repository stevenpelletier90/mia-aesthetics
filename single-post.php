<?php
/**
 * Single blog post template
 * 
 * @package Mia_Aesthetics
 */
get_header(); ?>

<main>
<?php mia_breadcrumbs(); ?>
    
    <?php while (have_posts()) : the_post(); 
        $content = get_the_content();
        $word_count = str_word_count(strip_tags($content));
        $reading_time = ceil($word_count / 200);
    ?>

        <section class="post-header py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <h1 class="mb-3"><?php the_title(); ?></h1>
                        
                        <div class="post-meta text-muted small mb-3">
                            <div class="d-flex align-items-center">
                                <i class="far fa-calendar-alt me-2" aria-hidden="true"></i>
                                <span><?php echo get_the_date(); ?></span>
                                <span class="mx-2">•</span>
                                <i class="far fa-clock me-2" aria-hidden="true"></i>
                                <span><?php echo $reading_time; ?> min read</span>
                            </div>
                        </div>

                        <?php if (has_excerpt()) : ?>
                            <p class="lead mb-0"><?php echo get_the_excerpt(); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <article class="py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <?php if (has_post_thumbnail()) : ?>
                            <figure class="mb-4">
                                <?php the_post_thumbnail('large', ['class' => 'img-fluid mb-2']); ?>
                                <?php
                                $thumbnail_caption = get_the_post_thumbnail_caption();
                                if ($thumbnail_caption) {
                                    echo '<figcaption class="text-muted small">' . $thumbnail_caption . '</figcaption>';
                                }
                                ?>
                            </figure>
                        <?php endif; ?>
                        
                        <div class="content mb-5">
                            <?php the_content(); ?>
                        </div>

                        <?php
                        $tags = get_the_tags();
                        if ($tags) : ?>
                            <div class="related-topics mb-5">
                                <h2 class="h5 mb-3">Related Topics</h2>
                                <?php foreach($tags as $tag) : ?>
                                    <a href="<?php echo get_tag_link($tag->term_id); ?>" class="text-decoration-none me-2 text-primary">#<?php echo $tag->name; ?></a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-lg-4 sidebar">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h2 class="h5 mb-3">Patient Resources</h2>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <a href="/before-after/" class="text-decoration-none">
                                            <i class="fas fa-images me-2" aria-hidden="true"></i>
                                            Before & After Gallery
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="/faqs/" class="text-decoration-none">
                                            <i class="fas fa-question-circle me-2" aria-hidden="true"></i>
                                            Frequently Asked Questions
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="/conditions/" class="text-decoration-none">
                                            <i class="fas fa-heart me-2" aria-hidden="true"></i>
                                            Conditions We Treat
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <?php
                        $categories = get_the_category();
                        if ($categories) {
                            $category_ids = array();
                            foreach($categories as $category) {
                                $category_ids[] = $category->term_id;
                            }
                            
                            $related_posts = new WP_Query([
                                'category__in' => $category_ids,
                                'post__not_in' => [get_the_ID()],
                                'posts_per_page' => 3,
                                'orderby' => 'rand'
                            ]);

                            if ($related_posts->have_posts()) : ?>
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h2 class="h5 mb-3">Related Articles</h2>
                                        <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
                                            <div class="mb-3">
                                                <?php if (has_post_thumbnail()) : ?>
                                                    <a href="<?php the_permalink(); ?>">
                                                        <?php the_post_thumbnail('thumbnail', ['class' => 'img-fluid mb-2']); ?>
                                                    </a>
                                                <?php endif; ?>
                                                <h3 class="h6 mb-1"><a href="<?php the_permalink(); ?>" class="text-decoration-none"><?php the_title(); ?></a></h3>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                </div>
                            <?php endif;
                            wp_reset_postdata();
                        }
                        ?>
                    </div>
                </div>
            </div>
        </article>

        <?php
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => get_the_title(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => [
                '@type' => 'Person',
                'name' => get_the_author()
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => get_site_icon_url()
                ]
            ]
        ];

        if (has_post_thumbnail()) {
            $schema['image'] = get_the_post_thumbnail_url(null, 'large');
        }
        ?>
        <script type="application/ld+json"><?php echo json_encode($schema); ?></script>
    <?php endwhile; ?>
</main>
<?php get_footer(); ?>
