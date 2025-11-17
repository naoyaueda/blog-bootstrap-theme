<?php
/**
 * CalgaryJP Theme Functions
 * Sets up theme defaults and registers support for various WordPress features
 */

// Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}

// Include helper functions
require_once get_template_directory() . '/inc/helpers.php';

/**
 * Theme Setup
 */
function blog_bootstrap_theme_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');
    
    // Let WordPress manage the document title
    add_theme_support('title-tag');
    
    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');
    
    // Set default thumbnail size
    set_post_thumbnail_size(1200, 630, true); // 16:9 aspect ratio
    
    // Add custom image sizes
    add_image_size('blog-bootstrap-card', 400, 250, true);
    add_image_size('blog-bootstrap-large', 1200, 800, true);
    add_image_size('blog-bootstrap-square', 600, 600, true);
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'blog-bootstrap'),
        'footer' => esc_html__('Footer Menu', 'blog-bootstrap'),
        'mobile' => esc_html__('Mobile Menu', 'blog-bootstrap'),
    ));
    
    // Switch default core markup for search form, comment form, and comments to output valid HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    
    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');
    
    // Add support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 80,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    
    // Add support for custom background
    add_theme_support('custom-background', array(
        'default-color' => 'f8f9fa',
        'default-image' => '',
    ));
    
    // Add support for block styles
    add_theme_support('wp-block-styles');
    
    // Add support for full and wide align images
    add_theme_support('align-wide');
    
    // Add support for responsive embedded content
    add_theme_support('responsive-embeds');
    
    // Add support for editor styles
    add_theme_support('editor-styles');
    
    // Add editor font styles
    add_editor_style(array(
        'assets/css/editor-style.css',
        'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap'
    ));
}
add_action('after_setup_theme', 'blog_bootstrap_theme_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet
 */
function blog_bootstrap_content_width() {
    $GLOBALS['content_width'] = apply_filters('blog_bootstrap_content_width', 1200);
}
add_action('after_setup_theme', 'blog_bootstrap_content_width', 0);

/**
 * Change number of posts shown on homepage
 */
function blog_bootstrap_posts_per_page($query) {
    if (is_home() && $query->is_main_query()) {
        $query->set('posts_per_page', 6);
    }
}
add_action('pre_get_posts', 'blog_bootstrap_posts_per_page');

/**
 * Custom archive titles
 */
function blog_bootstrap_custom_archive_title($title) {
    if (is_category()) {
        $title = single_cat_title('', false);
    }
    return $title;
}
add_filter('get_the_archive_title', 'blog_bootstrap_custom_archive_title');

/**
 * Register widget areas
 */
function blog_bootstrap_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Primary Sidebar', 'blog-bootstrap'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here to appear in your primary sidebar.', 'blog-bootstrap'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
    
    register_sidebar(array(
        'name'          => esc_html__('Footer Widget 1', 'blog-bootstrap'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Add widgets here to appear in your footer.', 'blog-bootstrap'),
        'before_widget' => '<div id="%1$s" class="widget %2$s col-md-4">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => esc_html__('Footer Widget 2', 'blog-bootstrap'),
        'id'            => 'footer-2',
        'description'   => esc_html__('Add widgets here to appear in your footer.', 'blog-bootstrap'),
        'before_widget' => '<div id="%1$s" class="widget %2$s col-md-4">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => esc_html__('Footer Widget 3', 'blog-bootstrap'),
        'id'            => 'footer-3',
        'description'   => esc_html__('Add widgets here to appear in your footer.', 'blog-bootstrap'),
        'before_widget' => '<div id="%1$s" class="widget %2$s col-md-4">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'blog_bootstrap_widgets_init');

/**
 * Enqueue scripts and styles
 */
function blog_bootstrap_scripts() {
    // Google Fonts
    wp_enqueue_style('blog-bootstrap-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap', array(), null);
    
    // Bootstrap CSS
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css', array(), '5.3.0');
    
    // Font Awesome
    wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0');
    
    // Theme Stylesheet
    wp_enqueue_style('blog-bootstrap-style', get_stylesheet_uri(), array('bootstrap-css', 'blog-bootstrap-fonts', 'fontawesome'), wp_get_theme()->get('Version'));
    
    // Bootstrap JS Bundle (includes Popper)
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array(), '5.3.0', true);
    
    // Theme JavaScript
    wp_enqueue_script('blog-bootstrap-script', get_template_directory_uri() . '/assets/js/theme.js', array('bootstrap-js'), wp_get_theme()->get('Version'), true);
    
    // Localize script for AJAX
    wp_localize_script('blog-bootstrap-script', 'blog_bootstrap_vars', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('blog_bootstrap_nonce'),
    ));
    
    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'blog_bootstrap_scripts');

/**
 * Add preconnect for Google Fonts
 */
function blog_bootstrap_resource_hints($urls, $relation_type) {
    if (wp_style_is('blog-bootstrap-fonts', 'queue') && 'preconnect' === $relation_type) {
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }
    return $urls;
}
add_filter('wp_resource_hints', 'blog_bootstrap_resource_hints', 10, 2);

/**
 * Performance optimizations
 */
function blog_bootstrap_optimize_scripts() {
    if (!is_admin()) {
        wp_dequeue_script('jquery-migrate');
    }
}
add_action('wp_enqueue_scripts', 'blog_bootstrap_optimize_scripts');

/**
 * Customizer additions
 */
function blog_bootstrap_customize_register($wp_customize) {
    // Add theme color settings
    $wp_customize->add_section('blog_bootstrap_colors', array(
        'title'    => esc_html__('Theme Colors', 'blog-bootstrap'),
        'priority' => 30,
    ));
    
    $wp_customize->add_setting('primary_color', array(
        'default'           => '#D63030',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color', array(
        'label'    => esc_html__('Primary Color', 'blog-bootstrap'),
        'section'  => 'blog_bootstrap_colors',
        'settings' => 'primary_color',
    )));
    
    $wp_customize->add_setting('secondary_color', array(
        'default'           => '#493657',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'secondary_color', array(
        'label'    => esc_html__('Secondary Color', 'blog-bootstrap'),
        'section'  => 'blog_bootstrap_colors',
        'settings' => 'secondary_color',
    )));
    
    $wp_customize->add_setting('accent_color', array(
        'default'           => '#DFF3E3',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'accent_color', array(
        'label'    => esc_html__('Accent Color', 'blog-bootstrap'),
        'section'  => 'blog_bootstrap_colors',
        'settings' => 'accent_color',
    )));

    // Front Page Content Section
    $wp_customize->add_section('blog_bootstrap_front_page_content', array(
        'title'    => esc_html__('Front Page Content', 'blog-bootstrap'),
        'priority' => 40,
    ));

    $wp_customize->add_setting('blog_bootstrap_front_page_content_setting', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('blog_bootstrap_front_page_content_control', array(
        'label'    => esc_html__('Content After Posts', 'blog-bootstrap'),
        'section'  => 'blog_bootstrap_front_page_content',
        'settings' => 'blog_bootstrap_front_page_content_setting',
        'type'     => 'textarea',
    ));
}
add_action('customize_register', 'blog_bootstrap_customize_register');

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously
 */
function blog_bootstrap_customize_preview_js() {
    wp_enqueue_script('blog-bootstrap-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array('customize-preview'), wp_get_theme()->get('Version'), true);
}
add_action('customize_preview_init', 'blog_bootstrap_customize_preview_js');

/**
 * SEO and AIEO optimizations
 */

// Add meta tags for SEO
function blog_bootstrap_seo_meta_tags() {
    // Add meta description
    if (is_single() || is_page()) {
        $description = get_the_excerpt() ? get_the_excerpt() : get_bloginfo('description');
        echo '<meta name="description" content="' . esc_attr(wp_trim_words($description, 30)) . '">' . "\n";
    } elseif (is_home() || is_front_page()) {
        echo '<meta name="description" content="' . esc_attr(get_bloginfo('description')) . '">' . "\n";
    }
    
    // Add Open Graph meta tags
    if (is_single() || is_page()) {
        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr(wp_trim_words(get_the_excerpt(), 30)) . '">' . "\n";
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
        echo '<meta property="og:type" content="article">' . "\n";
        
        if (has_post_thumbnail()) {
            echo '<meta property="og:image" content="' . esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')) . '">' . "\n";
        }
    }
    
    // Add Twitter Card meta tags
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:site" content="@' . esc_attr(get_bloginfo('name')) . '">' . "\n";
    
    if (is_single() || is_page()) {
        echo '<meta name="twitter:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr(wp_trim_words(get_the_excerpt(), 30)) . '">' . "\n";
        
        if (has_post_thumbnail()) {
            echo '<meta name="twitter:image" content="' . esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')) . '">' . "\n";
        }
    }
    
    // Add structured data for homepage
    if (is_home() || is_front_page()) {
        echo '<script type="application/ld+json">' . "\n";
        echo '{
            "@context": "https://schema.org",
            "@type": "WebSite",
            "name": "' . esc_js(get_bloginfo('name')) . '",
            "description": "' . esc_js(get_bloginfo('description')) . '",
            "url": "' . esc_url(home_url('/')) . '",
            "potentialAction": {
                "@type": "SearchAction",
                "target": "' . esc_url(home_url('/?s={search_term_string}')) . '",
                "query-input": "required name=search_term_string"
            }
        }' . "\n";
        echo '</script>' . "\n";
    }
}
add_action('wp_head', 'blog_bootstrap_seo_meta_tags', 5);

// Add JSON-LD structured data for posts
function blog_bootstrap_post_structured_data() {
    if (is_single() && 'post' === get_post_type()) {
        global $post;
        
        $author_id = $post->post_author;
        $author_name = get_the_author_meta('display_name', $author_id);
        
        echo '<script type="application/ld+json">' . "\n";
        echo '{
            "@context": "https://schema.org",
            "@type": "BlogPosting",
            "headline": "' . esc_js(get_the_title()) . '",
            "description": "' . esc_js(wp_trim_words(get_the_excerpt(), 30)) . '",
            "image": "' . esc_url(has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : '') . '",
            "author": {
                "@type": "Person",
                "name": "' . esc_js($author_name) . '"
            },
            "publisher": {
                "@type": "Organization",
                "name": "' . esc_js(get_bloginfo('name')) . '"
            },
            "datePublished": "' . esc_js(get_the_date('c')) . '",
            "dateModified": "' . esc_js(get_the_modified_date('c')) . '",
            "mainEntityOfPage": {
                "@type": "WebPage",
                "@id": "' . esc_url(get_permalink()) . '"
            }
        }' . "\n";
        echo '</script>' . "\n";
    }
}
add_action('wp_head', 'blog_bootstrap_post_structured_data');

// Custom excerpt length
function blog_bootstrap_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'blog_bootstrap_excerpt_length');

// Custom excerpt more
function blog_bootstrap_excerpt_more($more) {
    return '... <a href="' . esc_url(get_permalink()) . '" class="read-more">' . esc_html__('Continue Reading', 'blog-bootstrap') . ' &rarr;</a>';
}
add_filter('excerpt_more', 'blog_bootstrap_excerpt_more');

// Add body classes for better styling
function blog_bootstrap_body_classes($classes) {
    // Adds a class of hfeed to non-singular pages
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }
    
    // Adds a class of no-sidebar when there is no sidebar present
    if (!is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }
    
    // Add custom color classes
    if (get_theme_mod('primary_color')) {
        $classes[] = 'custom-primary-color';
    }
    
    return $classes;
}
add_filter('body_class', 'blog_bootstrap_body_classes');

// Custom Bootstrap Navigation Walker for 3-level support
class Blog_Bootstrap_Nav_Walker extends Walker_Nav_Menu {
    
    public function start_lvl(&$output, $depth = 0, $args = array()) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat($t, $depth);
        
        // Build dropdown menu classes based on depth
        if ($depth === 0) {
            $output .= "{$n}{$indent}<ul class=\"dropdown-menu\" role=\"menu\">{$n}";
        } elseif ($depth === 1) {
            $output .= "{$n}{$indent}<ul class=\"dropdown-menu dropdown-submenu\" role=\"menu\">{$n}";
        } else {
            $output .= "{$n}{$indent}<ul class=\"dropdown-menu\" role=\"menu\">{$n}";
        }
    }
    
    public function end_lvl(&$output, $depth = 0, $args = array()) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat($t, $depth);
        $output .= "{$indent}</ul>{$n}";
    }
    
    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat($t, $depth);
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        // Add Bootstrap classes based on depth
        if ($depth === 0) {
            $classes[] = 'nav-item';
            if (in_array('menu-item-has-children', $item->classes)) {
                $classes[] = 'dropdown';
            }
        } elseif ($depth === 1) {
            $classes[] = 'dropdown-submenu-item';
            if (in_array('menu-item-has-children', $item->classes)) {
                $classes[] = 'dropdown';
            }
        } else {
            $classes[] = 'dropdown-item';
        }
        
        $args = apply_filters('nav_menu_item_args', $args, $item, $depth);
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names . '>';
        
        $atts = array();
        $atts['title']  = ! empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = ! empty($item->target)     ? $item->target     : '';
        $atts['rel']    = ! empty($item->xfn)        ? $item->xfn        : '';
        $atts['href']   = ! empty($item->url)        ? $item->url        : '';
        
        // Add dropdown attributes for items with children
        if (in_array('menu-item-has-children', $item->classes)) {
            if ($depth === 0) {
                $atts['class'] = 'nav-link dropdown-toggle';
                $atts['data-bs-toggle'] = 'dropdown';
                $atts['aria-expanded'] = 'false';
                $atts['role'] = 'button';
            } else {
                $atts['class'] = 'dropdown-item';
            }
        } else {
            if ($depth === 0) {
                $atts['class'] = 'nav-link';
            } else {
                $atts['class'] = 'dropdown-item';
            }
        }
        
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);
        
        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (! empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        
        $title = apply_filters('the_title', $item->title, $item->ID);
        $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);

        // Add icons based on menu item classes
        $icon = '';
        $classes = implode(' ', $item->classes);
        if (strpos($classes, 'menu-home') !== false) {
            $icon = '<i class="fas fa-home"></i>';
        } elseif (strpos($classes, 'menu-about') !== false) {
            $icon = '<i class="fas fa-info-circle"></i>';
        } elseif (strpos($classes, 'menu-life') !== false) {
            $icon = '<i class="fas fa-heart"></i>';
        } elseif (strpos($classes, 'menu-school') !== false) {
            $icon = '<i class="fas fa-graduation-cap"></i>';
        } elseif (strpos($classes, 'menu-calgary') !== false) {
            $icon = '<i class="fas fa-city"></i>';
        } elseif (strpos($classes, 'menu-contact') !== false) {
            $icon = '<i class="fas fa-envelope"></i>';
        }

        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . '<div class="menu-icon">' . $icon . '</div><div class="menu-text">' . $title . '</div>' . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

/**
 * Display content after the loop on the front page.
 */
function blog_bootstrap_display_after_loop_content() {
    $content = get_theme_mod('blog_bootstrap_front_page_content_setting');
    if (!empty($content)) {
        echo '<div class="row"><div class="col-12"><hr class="my-5">' . wp_kses_post($content) . '</div></div>';
    }
}
add_action('blog_bootstrap_after_loop', 'blog_bootstrap_display_after_loop_content');