<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package BlogBootstrap
 */

// Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}

if (is_singular()) : ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="entry-header mb-4">
            <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
        </header>

        <?php if (has_post_thumbnail() && !post_password_required()) : ?>
            <div class="post-thumbnail">
                <?php the_post_thumbnail('blog-bootstrap-large'); ?>
            </div>
        <?php endif; ?>

        <div class="entry-content">
            <?php
            the_content();
            wp_link_pages(array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'blog-bootstrap'),
                'after'  => '</div>',
            ));
            ?>
        </div>

        <footer class="entry-footer mt-4">
            <div class="post-meta">
                <span class="posted-on">
                    <i class="fas fa-calendar-alt me-2"></i>
                    <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                        <?php echo esc_html(get_the_date()); ?>
                    </time>
                </span>
                <?php if (get_the_author()) : ?>
                    <span class="byline ms-3">
                        <i class="fas fa-user me-2"></i>
                        <span class="author vcard">
                            <a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                <?php echo esc_html(get_the_author()); ?>
                            </a>
                        </span>
                    </span>
                <?php endif; ?>
                <?php if (get_the_category()) : ?>
                    <span class="cat-links ms-3">
                        <i class="fas fa-folder me-2"></i>
                        <?php the_category(', '); ?>
                    </span>
                <?php endif; ?>
                <?php if (get_the_tag_list()) : ?>
                    <span class="tags-links ms-3">
                        <i class="fas fa-tags me-2"></i>
                        <?php echo get_the_tag_list('', ', ', ''); ?>
                    </span>
                <?php endif; ?>
            </div>
        </footer>
    </article>

<?php else : ?>

<div class="col-lg-4 col-md-6 mb-4">
    <article id="post-<?php the_ID(); ?>" <?php post_class('card h-100'); ?>>
        <?php if (has_post_thumbnail() && !post_password_required()) : ?>
            <div class="post-thumbnail">
                <?php the_post_thumbnail('blog-bootstrap-large', array('class' => 'card-img-top')); ?>
            </div>
        <?php endif; ?>
        
        <div class="card-body">
            <header class="entry-header mb-4">
                <?php
                if (is_sticky() && is_home() && !is_paged()) {
                    printf('<span class="sticky-badge badge bg-primary me-2">%s</span>', esc_html__('Featured', 'blog-bootstrap'));
                }
                the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                ?>
            </header>

            <div class="entry-content">
                <?php the_excerpt(); ?>
            </div>

            <footer class="entry-footer mt-4">
                <div class="post-meta">
                    <span class="posted-on">
                        <i class="fas fa-calendar-alt me-2"></i>
                        <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                            <?php echo esc_html(get_the_date()); ?>
                        </time>
                    </span>
                    <?php if (get_the_author()) : ?>
                        <span class="byline ms-3">
                            <i class="fas fa-user me-2"></i>
                            <span class="author vcard">
                                <a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                    <?php echo esc_html(get_the_author()); ?>
                                </a>
                            </span>
                        </span>
                    <?php endif; ?>
                    <?php if (get_the_category()) : ?>
                        <span class="cat-links ms-3">
                            <i class="fas fa-folder me-2"></i>
                            <?php the_category(', '); ?>
                        </span>
                    <?php endif; ?>
                    <?php if (get_the_tag_list()) : ?>
                        <span class="tags-links ms-3">
                            <i class="fas fa-tags me-2"></i>
                            <?php echo get_the_tag_list('', ', ', ''); ?>
                        </span>
                    <?php endif; ?>
                </div>
                
                <div class="mt-3">
                    <a href="<?php the_permalink(); ?>" class="btn btn-primary">
                        <?php esc_html_e('Read More', 'blog-bootstrap'); ?> <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </footer>
        </div>
    </article>
</div>

<?php endif; ?>