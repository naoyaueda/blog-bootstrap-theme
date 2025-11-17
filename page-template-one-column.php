<?php
/**
 * Template Name: One Column
 *
 * @package CalgaryJP
 */

get_header(); ?>

<div class="container site-main">
    <div class="row">
        <div class="col-12">
            
            <?php while (have_posts()) : the_post(); ?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class('mb-4'); ?>>
                    <?php if (has_post_thumbnail() && !post_password_required()) : ?>
                        <div class="page-thumbnail">
                            <?php the_post_thumbnail('blog-bootstrap-large', array('class' => 'img-fluid mb-4')); ?>
                        </div>
                    <?php endif; ?>
                    
                    <header class="entry-header mb-4">
                        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                        
                        <?php if (!is_front_page()) : ?>
                            <div class="post-meta">
                                <span class="posted-on">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                        <?php echo esc_html(get_the_date()); ?>
                                    </time>
                                </span>
                                <?php if (get_the_modified_date() !== get_the_date()) : ?>
                                    <span class="updated ms-3">
                                        <i class="fas fa-edit me-1"></i>
                                        <time class="entry-date updated" datetime="<?php echo esc_attr(get_the_modified_date('c')); ?>">
                                            <?php esc_html_e('Updated:', 'blog-bootstrap'); ?> <?php echo esc_html(get_the_modified_date()); ?>
                                        </time>
                                    </span>
                                <?php endif; ?>
                                <?php
                                // Show author if different from site admin
                                if (get_the_author_meta('ID') !== 1) : ?>
                                    <span class="byline ms-3">
                                        <i class="fas fa-user me-1"></i>
                                        <span class="author vcard">
                                            <a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                                <?php echo esc_html(get_the_author()); ?>
                                            </a>
                                        </span>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </header>

                    <div class="entry-content">
                        <?php
                        the_content();

                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'blog-bootstrap'),
                            'after'  => '</div>',
                        ));
                        ?>
                    </div>

                    <?php if (get_edit_post_link()) : ?>
                        <footer class="entry-footer mt-4">
                            <a href="<?php echo esc_url(get_edit_post_link()); ?>" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-edit me-1"></i>
                                <?php esc_html_e('Edit Page', 'blog-bootstrap'); ?>
                            </a>
                        </footer>
                    <?php endif; ?>
                </article>

                <!-- Page Comments -->
                <?php
                // If comments are open or we have at least one comment, load up the comment template
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>

            <?php endwhile; ?>

        </div>
    </div>
</div>

<?php
get_footer();
