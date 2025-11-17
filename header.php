<?php
/**
 * The header for our theme
 * Displays all of the <head> section and everything up till <div id="content">
 */

// Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <!-- Enhanced SEO Meta Tags -->
    <?php if (is_singular()) : ?>
        <meta name="description" content="<?php echo esc_attr(get_the_excerpt() ? wp_strip_all_tags(get_the_excerpt()) : get_bloginfo('description')); ?>">
        <meta property="og:title" content="<?php echo esc_attr(get_the_title()); ?>">
        <meta property="og:description" content="<?php echo esc_attr(get_the_excerpt() ? wp_strip_all_tags(get_the_excerpt()) : get_bloginfo('description')); ?>">
        <meta property="og:type" content="article">
        <meta property="og:url" content="<?php echo esc_url(get_permalink()); ?>">
        <meta property="og:site_name" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
        <meta property="og:locale" content="<?php echo esc_attr(get_locale()); ?>">
        <?php if (has_post_thumbnail()) : ?>
            <meta property="og:image" content="<?php echo esc_url(get_the_post_thumbnail_url('large')); ?>">
            <meta property="og:image:width" content="1200">
            <meta property="og:image:height" content="630">
        <?php endif; ?>
        
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="<?php echo esc_attr(get_the_title()); ?>">
        <meta name="twitter:description" content="<?php echo esc_attr(get_the_excerpt() ? wp_strip_all_tags(get_the_excerpt()) : get_bloginfo('description')); ?>">
        <?php if (has_post_thumbnail()) : ?>
            <meta name="twitter:image" content="<?php echo esc_url(get_the_post_thumbnail_url('large')); ?>">
        <?php endif; ?>
    <?php else : ?>
        <meta name="description" content="<?php echo esc_attr(get_bloginfo('description')); ?>">
        <meta property="og:title" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
        <meta property="og:description" content="<?php echo esc_attr(get_bloginfo('description')); ?>">
        <meta property="og:type" content="website">
        <meta property="og:url" content="<?php echo esc_url(home_url('/')); ?>">
        <meta property="og:site_name" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
        <meta property="og:locale" content="<?php echo esc_attr(get_locale()); ?>">
        
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
        <meta name="twitter:description" content="<?php echo esc_attr(get_bloginfo('description')); ?>">
    <?php endif; ?>
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo esc_url(is_singular() ? get_permalink() : home_url('/')); ?>">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'blog-bootstrap'); ?></a>

<header class="site-header">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <!-- Brand -->
            <?php
            if (function_exists('the_custom_logo') && has_custom_logo()) {
                the_custom_logo();
            } else {
                ?>
                <a class="navbar-brand site-title" href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                    <?php bloginfo('name'); ?>
                </a>
                <?php
            }
            ?>
            
            <?php if (get_bloginfo('description')) : ?>
                <span class="site-description d-none d-lg-inline-block ms-3"><?php echo get_bloginfo('description'); ?></span>
            <?php endif; ?>
            
            <!-- Mobile toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-nav" aria-controls="main-nav" aria-expanded="false" aria-label="<?php esc_attr_e('Toggle navigation', 'blog-bootstrap'); ?>">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Navigation -->
            <div class="collapse navbar-collapse" id="main-nav">
                <?php
                if (has_nav_menu('primary')) {
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_class'     => 'navbar-nav ms-auto',
                        'container'      => false,
                        'fallback_cb'    => false,
                        'walker'         => new Blog_Bootstrap_Nav_Walker(),
                    ));
                } else {
                    ?>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo esc_url(admin_url('nav-menus.php')); ?>">
                                <?php esc_html_e('Add a menu', 'blog-bootstrap'); ?>
                            </a>
                        </li>
                    </ul>
                    <?php
                }
                ?>
                
                <!-- Search -->
                <div class="search-form-wrapper ms-lg-3">
                    <button class="btn btn-link search-icon-btn" type="button" data-bs-toggle="modal" data-bs-target="#searchModal" aria-label="<?php esc_attr_e('Search', 'blog-bootstrap'); ?>">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>
</header>

<!-- Search Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="searchModalLabel"><?php esc_html_e('Search', 'blog-bootstrap'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php esc_attr_e('Close', 'blog-bootstrap'); ?>"></button>
            </div>
            <div class="modal-body">
                <?php get_search_form(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Breadcrumb -->
<?php if (!is_front_page() && !is_home()) : ?>
    <div class="container">
        <nav aria-label="<?php esc_attr_e('Breadcrumb', 'blog-bootstrap'); ?>" class="py-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'blog-bootstrap'); ?></a>
                </li>
                <?php
                if (is_category() || is_tag() || is_tax()) {
                    ?>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?php single_term_title(); ?>
                    </li>
                    <?php
                } elseif (is_search()) {
                    ?>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?php printf(esc_html__('Search Results for: %s', 'blog-bootstrap'), '<span>' . get_search_query() . '</span>'); ?>
                    </li>
                    <?php
                } elseif (is_404()) {
                    ?>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?php esc_html_e('404 - Page Not Found', 'blog-bootstrap'); ?>
                    </li>
                    <?php
                } elseif (is_single()) {
                    $categories = get_the_category();
                    if ($categories) {
                        echo '<li class="breadcrumb-item"><a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a></li>';
                    }
                    echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
                } elseif (is_page()) {
                    echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
                } elseif (is_archive()) {
                    ?>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?php the_archive_title(); ?>
                    </li>
                    <?php
                }
                ?>
            </ol>
        </nav>
    </div>
<?php endif; ?>

<main id="content" class="site-content">