<?php
/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 */

get_header(); ?>

<div class="container site-main">
    <div class="row">
        <div class="col-12">
            
            <?php if (have_posts()) : ?>
                
                <?php if (is_home() && !is_front_page()) : ?>
                    <header class="page-header mb-4">
                        <h1 class="page-title"><?php single_post_title(); ?></h1>
                    </header>
                <?php endif; ?>

                <div class="row">
                    <?php
                    // Start the Loop
                    while (have_posts()) : the_post();
                        
                        get_template_part('template-parts/content', get_post_format());
                        
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
    </div>
</div>

<?php
get_footer();