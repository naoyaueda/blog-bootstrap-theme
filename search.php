<?php
/**
 * Search Results Template
 */

get_header(); ?>

<div class="container site-main">
    <div class="row">
        <div class="<?php echo is_active_sidebar('sidebar-1') ? 'col-lg-8' : 'col-12'; ?>">
            
            <header class="page-header mb-4">
                <h1 class="page-title">
                    <?php
                    if (have_posts()) {
                        printf(
                            esc_html__('Search Results for: %s', 'blog-bootstrap'),
                            '<span>' . get_search_query() . '</span>'
                        );
                    } else {
                        esc_html_e('Nothing Found', 'blog-bootstrap');
                    }
                    ?>
                </h1>
            </header>

            <?php if (have_posts()) : ?>
                
                <div class="row">
                    <?php
                    // Start the Loop
                    while (have_posts()) : the_post();
                        
                        get_template_part('template-parts/content', 'search');
                        
                    endwhile;
                    ?>
                </div>

                <?php
                // Pagination
                the_posts_pagination(array(
                    'mid_size'  => 2,
                    'prev_text' => __('&laquo; Previous', 'blog-bootstrap'),
                    'next_text' => __('Next &raquo;', 'blog-bootstrap'),
                    'class'     => 'pagination justify-content-center',
                ));
                ?>

            <?php else : ?>
                
                <?php get_template_part('template-parts/content', 'none'); ?>

            <?php endif; ?>

        </div>

        <?php if (is_active_sidebar('sidebar-1')) : ?>
            <div class="col-lg-4">
                <?php get_sidebar(); ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>