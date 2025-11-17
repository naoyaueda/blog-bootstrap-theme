<?php
/**
 * Template part for displaying posts in search results
 */

// Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('card mb-3'); ?>>
    <div class="card-body">
        <header class="entry-header">
            <?php the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>'); ?>
        </header>

        <div class="entry-summary">
            <?php the_excerpt(); ?>
        </div>

        <footer class="entry-footer">
            <div class="post-meta small">
                <span class="posted-on">
                    <i class="fas fa-calendar-alt me-1"></i>
                    <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                        <?php echo esc_html(get_the_date()); ?>
                    </time>
                </span>
                <?php if (get_the_category()) : ?>
                    <span class="cat-links ms-2">
                        <i class="fas fa-folder me-1"></i>
                        <?php the_category(', '); ?>
                    </span>
                <?php endif; ?>
            </div>
        </footer>
    </div>
</article>