<?php
/**
 * 404 Template (Not Found)
 */

get_header(); ?>

<div class="container site-main">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            
            <div class="card">
                <div class="card-body py-5">
                    <h1 class="display-1 mb-4">404</h1>
                    <h2 class="mb-4"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'blog-bootstrap'); ?></h2>
                    
                    <div class="mb-4">
                        <p class="lead"><?php esc_html_e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'blog-bootstrap'); ?></p>
                    </div>
                    
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <?php get_search_form(); ?>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i><?php esc_html_e('Go Home', 'blog-bootstrap'); ?>
                        </a>
                    </div>
                    
                    <?php if (is_active_sidebar('sidebar-1')) : ?>
                        <div class="mt-5">
                            <h3><?php esc_html_e('Recent Posts', 'blog-bootstrap'); ?></h3>
                            <?php
                            $recent_posts = new WP_Query(array(
                                'posts_per_page' => 5,
                                'post_status' => 'publish'
                            ));
                            
                            if ($recent_posts->have_posts()) : ?>
                                <ul class="list-unstyled">
                                    <?php while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
                                        <li class="mb-2">
                                            <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                                                <?php the_title(); ?>
                                            </a>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            <?php endif; ?>
                            <?php wp_reset_postdata(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php get_footer(); ?>