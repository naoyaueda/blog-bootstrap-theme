<?php
/**
 * The template for displaying all single posts
 */

get_header(); ?>

<div class="container site-main">
    <div class="row">
        <div class="<?php echo is_active_sidebar('sidebar-1') ? 'col-lg-8' : 'col-12'; ?>">
            
            <?php while (have_posts()) : the_post(); ?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class('mb-4'); ?>>
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-thumbnail">
                            <?php the_post_thumbnail('blog-bootstrap-large', array('class' => 'img-fluid mb-4')); ?>
                        </div>
                    <?php endif; ?>
                    
                    <header class="entry-header mb-4">
                        <?php
                        the_title('<h1 class="entry-title">', '</h1>');
                        
                        // Post meta
                        ?>
                        <div class="post-meta">
                            <span class="posted-on">
                                <i class="fas fa-calendar-alt me-1"></i>
                                <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                    <?php echo esc_html(get_the_date()); ?>
                                </time>
                            </span>
                            <span class="byline ms-3">
                                <i class="fas fa-user me-1"></i>
                                <span class="author vcard">
                                    <a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                        <?php echo esc_html(get_the_author()); ?>
                                    </a>
                                </span>
                            </span>
                            <?php if (has_category()) : ?>
                                <span class="cat-links ms-3">
                                    <i class="fas fa-folder me-1"></i>
                                    <?php the_category(', '); ?>
                                </span>
                            <?php endif; ?>
                            <?php if (has_tag()) : ?>
                                <span class="tags-links ms-3">
                                    <i class="fas fa-tags me-1"></i>
                                    <?php the_tags('', ', '); ?>
                                </span>
                            <?php endif; ?>
                            <span class="comments-link ms-3">
                                <i class="fas fa-comments me-1"></i>
                                <?php
                                comments_popup_link(
                                    __('No Comments', 'blog-bootstrap'),
                                    __('1 Comment', 'blog-bootstrap'),
                                    __('% Comments', 'blog-bootstrap')
                                );
                                ?>
                            </span>
                        </div>
                    </header>

                    <div class="entry-content">
                        <?php
                        the_content(
                            sprintf(
                                wp_kses(
                                    __('Continue reading %s <span class="meta-nav">&rarr;</span>', 'blog-bootstrap'),
                                    array('span' => array('class' => array()))
                                ),
                                get_the_title()
                            )
                        );

                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'blog-bootstrap'),
                            'after'  => '</div>',
                        ));
                        ?>
                    </div>

                    <footer class="entry-footer mt-4">
                        <div class="row">
                            <div class="col-md-6">
                                <?php if (get_the_tag_list()) : ?>
                                    <div class="tag-links">
                                        <strong><?php esc_html_e('Tags:', 'blog-bootstrap'); ?></strong>
                                        <?php echo get_the_tag_list('', ' ', ''); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 text-md-end">
                                
                            </div>
                        </div>
                    </footer>
                </article>
			
                <!-- Author Bio
                <div class="card author-bio mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center">
                                <?php echo get_avatar(get_the_author_meta('ID'), 120, '', '', array('class' => 'rounded-circle')); ?>
                            </div>
                            <div class="col-md-9">
                                <h3 class="author-name">
                                    <?php esc_html_e('About', 'blog-bootstrap'); ?> <?php echo esc_html(get_the_author()); ?>
                                </h3>
                                <p class="author-description">
                                    <?php echo wp_kses_post(get_the_author_meta('description')); ?>
                                </p>
                                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="btn btn-outline-primary btn-sm">
                                    <?php esc_html_e('View all posts by', 'blog-bootstrap'); ?> <?php echo esc_html(get_the_author()); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
				-->

                <!-- Related Posts -->
                <?php
                $related_posts = new WP_Query(array(
                    'posts_per_page' => 3,
                    'post__not_in' => array(get_the_ID()),
                    'category__in' => wp_get_post_categories(get_the_ID()),
                    'orderby' => 'rand',
                ));

                if ($related_posts->have_posts()) : ?>
                    <div class="related-posts mb-4">
                        <h3 class="related-posts-title mb-4"><?php esc_html_e('Related Posts', 'blog-bootstrap'); ?></h3>
                        <div class="row">
                            <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('blog-bootstrap-card', array('class' => 'card-img-top')); ?>
                                            </a>
                                        <?php endif; ?>
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                                                    <?php the_title(); ?>
                                                </a>
                                            </h5>
                                            <div class="post-meta small">
                                                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                                    <?php echo esc_html(get_the_date()); ?>
                                                </time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                    <?php
                    wp_reset_postdata();
                endif;
                ?>

                <!-- Navigation -->
                <nav class="navigation post-navigation" role="navigation">
                    <h2 class="sr-only"><?php esc_html_e('Post navigation', 'blog-bootstrap'); ?></h2>
                    <div class="row">
                        <div class="col-6">
                            <?php previous_post_link('<div class="nav-previous">%link</div>', '<i class="fas fa-arrow-left me-2"></i>' . esc_html__('Previous Post', 'blog-bootstrap')); ?>
                        </div>
                        <div class="col-6 text-end">
                            <?php next_post_link('<div class="nav-next">%link</div>', esc_html__('Next Post', 'blog-bootstrap') . '<i class="fas fa-arrow-right ms-2"></i>'); ?>
                        </div>
                    </div>
                </nav>

            <?php endwhile; ?>

            <!-- Comments -->
            <?php
            // If comments are open or we have at least one comment, load up the comment template
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
            ?>

        </div>

        <?php if (is_active_sidebar('sidebar-1')) : ?>
            <div class="col-lg-4">
                <?php get_sidebar(); ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>