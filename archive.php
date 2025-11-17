<?php
/**
 * Archive Template
 * Uses same layout as homepage without sidebar
 */

get_header(); ?>

<div class="container site-main">
            
    <header class="page-header mb-4">
        <?php
        the_archive_title('<h1 class="page-title">', '</h1>');
        the_archive_description('<div class="archive-description">', '</div>');
        ?>
    </header>

    <?php if (have_posts()) : ?>

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

<?php get_footer(); ?>