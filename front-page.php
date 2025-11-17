<?php
/**
 * Front Page Template
 * Displays a news feed grid layout followed by the static front page content
 */

get_header(); ?>

<div class="container site-main">
    <div class="row">
        <div class="col-12">
            
            <?php
            // Display the content of the static page assigned as the homepage
            // We need to get the global $post object for the static front page
            global $post;
            $front_page_id = get_option('page_on_front');
            if ($front_page_id) {
                $post = get_post($front_page_id);
                setup_postdata($post);
                the_title('<header class="page-header mb-4"><h1 class="page-title">', '</h1></header>');
                wp_reset_postdata(); // Reset post data after displaying static page title
            }
            ?>

            <?php
            // Display the latest posts
            $paged = (get_query_var('page')) ? get_query_var('page') : ((get_query_var('paged')) ? get_query_var('paged') : 1);

            $latest_posts_query = new WP_Query(array(
                'post_type'      => 'post',
                'posts_per_page' => 6, // Explicitly set to 6
                'paged'          => $paged,
            ));

            if ($latest_posts_query->have_posts()) : ?>
                
                <div class="row">
                    <?php
                    while ($latest_posts_query->have_posts()) : $latest_posts_query->the_post();
                        
                        get_template_part('template-parts/content', 'front-page');
                        
                    endwhile;
                    ?>
                </div>

                <?php
                // Pagination for the latest posts
                echo '<div class="pagination-wrapper">';
                echo paginate_links(array(
                    'total'     => $latest_posts_query->max_num_pages,
                    'current'   => max(1, $paged),
                    'mid_size'  => 2,
                    'prev_text' => __('&laquo; Previous', 'blog-bootstrap'),
                    'next_text' => __('Next &raquo;', 'blog-bootstrap'),
                    'class'     => 'pagination justify-content-center',
                    'type'      => 'list',
                ));
                echo '</div>';
                ?>

            <?php else : ?>
                
                <?php get_template_part('template-parts/content', 'none'); ?>

            <?php endif; ?>

            <?php wp_reset_postdata(); // Restore original Post Data ?>

            <hr class="my-5">

            <?php do_action('blog_bootstrap_after_loop'); // Action hook for custom content after latest posts ?>

            <?php
            // Display the content of the static page assigned as the homepage
            // We need to get the global $post object for the static front page
            global $post;
            $front_page_id = get_option('page_on_front');
            if ($front_page_id) {
                $post = get_post($front_page_id);
                setup_postdata($post);
                the_content();
                wp_reset_postdata(); // Reset post data after displaying static page content
            }
            ?>

        </div>
    </div>
</div>

<?php
get_footer();