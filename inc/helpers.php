<?php
/**
 * CalgaryJP Helper Functions
 * 
 * @package BlogBootstrap
 * @version 2.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Breadcrumb navigation
 */
function blog_bootstrap_breadcrumb() {
    if (is_front_page()) {
        return;
    }
    
    $breadcrumb = '<ol class="breadcrumb">';
    $breadcrumb .= '<li class="breadcrumb-item"><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'blog-bootstrap') . '</a></li>';
    
    if (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        $breadcrumb .= '<li class="breadcrumb-item active" aria-current="page">' . esc_html($term->name) . '</li>';
    } elseif (is_search()) {
        $breadcrumb .= '<li class="breadcrumb-item active" aria-current="page">' . 
                     sprintf(esc_html__('Search Results for: %s', 'blog-bootstrap'), '<span>' . esc_html(get_search_query()) . '</span>') . 
                     '</li>';
    } elseif (is_404()) {
        $breadcrumb .= '<li class="breadcrumb-item active" aria-current="page">' . esc_html__('404 - Page Not Found', 'blog-bootstrap') . '</li>';
    } elseif (is_single()) {
        $categories = get_the_category();
        if ($categories && !is_wp_error($categories)) {
            $breadcrumb .= '<li class="breadcrumb-item"><a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a></li>';
        }
        $breadcrumb .= '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
    } elseif (is_page()) {
        $ancestors = get_post_ancestors(get_the_ID());
        if ($ancestors) {
            $ancestors = array_reverse($ancestors);
            foreach ($ancestors as $ancestor) {
                $breadcrumb .= '<li class="breadcrumb-item"><a href="' . esc_url(get_permalink($ancestor)) . '">' . esc_html(get_the_title($ancestor)) . '</a></li>';
            }
        }
        $breadcrumb .= '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
    } elseif (is_archive()) {
        if (is_day()) {
            $breadcrumb .= '<li class="breadcrumb-item"><a href="' . esc_url(get_year_link(get_the_time('Y'))) . '">' . get_the_time('Y') . '</a></li>';
            $breadcrumb .= '<li class="breadcrumb-item"><a href="' . esc_url(get_month_link(get_the_time('Y'), get_the_time('m'))) . '">' . get_the_time('F') . '</a></li>';
            $breadcrumb .= '<li class="breadcrumb-item active" aria-current="page">' . get_the_time('d') . '</li>';
        } elseif (is_month()) {
            $breadcrumb .= '<li class="breadcrumb-item"><a href="' . esc_url(get_year_link(get_the_time('Y'))) . '">' . get_the_time('Y') . '</a></li>';
            $breadcrumb .= '<li class="breadcrumb-item active" aria-current="page">' . get_the_time('F') . '</li>';
        } elseif (is_year()) {
            $breadcrumb .= '<li class="breadcrumb-item active" aria-current="page">' . get_the_time('Y') . '</li>';
        } elseif (is_author()) {
            $author = get_queried_object();
            $breadcrumb .= '<li class="breadcrumb-item active" aria-current="page">' . 
                         sprintf(esc_html__('Author: %s', 'blog-bootstrap'), esc_html($author->display_name)) . 
                         '</li>';
        } else {
            $breadcrumb .= '<li class="breadcrumb-item active" aria-current="page">' . get_the_archive_title() . '</li>';
        }
    }
    
    $breadcrumb .= '</ol>';
    
    echo $breadcrumb;
}

/**
 * Get post view count
 */
function blog_bootstrap_get_post_views($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    
    if ($count === '') {
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return 0;
    }
    
    return intval($count);
}

/**
 * Set post view count
 */
function blog_bootstrap_set_post_views($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    
    if ($count === '') {
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '1');
    } else {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

/**
 * Track post views
 */
function blog_bootstrap_track_post_views($post_id) {
    if (!is_single()) return;
    if (empty($post_id)) {
        global $post;
        $post_id = $post->ID;
    }
    
    blog_bootstrap_set_post_views($post_id);
}
add_action('wp_head', 'blog_bootstrap_track_post_views');

/**
 * Newsletter subscription handler
 */
function blog_bootstrap_newsletter_subscribe() {
    check_ajax_referer('blog_bootstrap_nonce', 'nonce');
    
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    
    if (empty($email) || !is_email($email)) {
        wp_send_json_error(esc_html__('Please enter a valid email address.', 'blog-bootstrap'));
    }
    
    // Check if email already exists
    if (email_exists($email)) {
        wp_send_json_error(esc_html__('This email is already subscribed.', 'blog-bootstrap'));
    }
    
    // Here you would integrate with your newsletter service
    // For now, we'll just store it in options or create a user
    
    // Store in options for demo purposes
    $subscribers = get_option('blog_bootstrap_newsletter_subscribers', array());
    if (!in_array($email, $subscribers)) {
        $subscribers[] = $email;
        update_option('blog_bootstrap_newsletter_subscribers', $subscribers);
    }
    
    wp_send_json_success(esc_html__('Thank you for subscribing!', 'blog-bootstrap'));
}
add_action('wp_ajax_blog_bootstrap_newsletter_subscribe', 'blog_bootstrap_newsletter_subscribe');
add_action('wp_ajax_nopriv_blog_bootstrap_newsletter_subscribe', 'blog_bootstrap_newsletter_subscribe');

/**
 * Get reading time estimate
 */
function blog_bootstrap_reading_time() {
    $content = get_the_content();
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Assuming 200 words per minute
    
    return $reading_time;
}

/**
 * Get estimated reading time with proper formatting
 */
function blog_bootstrap_get_reading_time() {
    $minutes = blog_bootstrap_reading_time();
    
    if ($minutes === 1) {
        return sprintf(esc_html__('%d min read', 'blog-bootstrap'), $minutes);
    } else {
        return sprintf(esc_html__('%d mins read', 'blog-bootstrap'), $minutes);
    }
}

/**
 * Custom excerpt with more control
 */
function blog_bootstrap_custom_excerpt($length = 55, $more = '...') {
    $excerpt = get_the_excerpt();
    $excerpt = wp_trim_words($excerpt, $length, $more);
    return $excerpt;
}

/**
 * Get related posts by category
 */
function blog_bootstrap_get_related_posts($post_id, $number = 3) {
    $categories = wp_get_post_categories($post_id);
    
    if (empty($categories)) {
        return array();
    }
    
    $args = array(
        'category__in' => $categories,
        'post__not_in' => array($post_id),
        'posts_per_page' => $number,
        'orderby' => 'rand',
        'post_status' => 'publish'
    );
    
    return get_posts($args);
}

/**
 * Schema.org structured data for breadcrumbs
 */
function blog_bootstrap_breadcrumb_schema() {
    if (is_front_page()) {
        return;
    }
    
    $breadcrumbs = array();
    
    // Home
    $breadcrumbs[] = array(
        '@type' => 'ListItem',
        'position' => 1,
        'name' => esc_html__('Home', 'blog-bootstrap'),
        'item' => esc_url(home_url('/'))
    );
    
    $position = 2;
    
    if (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => $term->name,
            'item' => get_term_link($term)
        );
    } elseif (is_single()) {
        $categories = get_the_category();
        if ($categories && !is_wp_error($categories)) {
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => $position,
                'name' => $categories[0]->name,
                'item' => get_category_link($categories[0]->term_id)
            );
            $position++;
        }
        
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => get_the_title(),
            'item' => get_permalink()
        );
    } elseif (is_page()) {
        $ancestors = get_post_ancestors(get_the_ID());
        if ($ancestors) {
            $ancestors = array_reverse($ancestors);
            foreach ($ancestors as $ancestor) {
                $breadcrumbs[] = array(
                    '@type' => 'ListItem',
                    'position' => $position,
                    'name' => get_the_title($ancestor),
                    'item' => get_permalink($ancestor)
                );
                $position++;
            }
        }
        
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => get_the_title(),
            'item' => get_permalink()
        );
    }
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $breadcrumbs
    );
    
    return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>';
}

/**
 * Add breadcrumb schema to head
 */
function blog_bootstrap_add_breadcrumb_schema() {
    if (!is_front_page()) {
        echo blog_bootstrap_breadcrumb_schema();
    }
}
add_action('wp_head', 'blog_bootstrap_add_breadcrumb_schema', 10);

/**
 * Get featured posts
 */
function blog_bootstrap_get_featured_posts($number = 5) {
    $args = array(
        'posts_per_page' => $number,
        'post_status' => 'publish',
        'meta_key' => '_is_featured',
        'meta_value' => '1',
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    return get_posts($args);
}

/**
 * Check if post is featured
 */
function blog_bootstrap_is_featured($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    return get_post_meta($post_id, '_is_featured', true) === '1';
}

/**
 * Add meta box for featured posts
 */
function blog_bootstrap_add_featured_meta_box() {
    add_meta_box(
        'blog_bootstrap_featured_meta_box',
        esc_html__('Featured Post', 'blog-bootstrap'),
        'blog_bootstrap_featured_meta_box_callback',
        'post',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'blog_bootstrap_add_featured_meta_box');

/**
 * Featured meta box callback
 */
function blog_bootstrap_featured_meta_box_callback($post) {
    wp_nonce_field('blog_bootstrap_featured_meta_box', 'blog_bootstrap_featured_nonce');
    
    $is_featured = get_post_meta($post->ID, '_is_featured', true);
    
    echo '<label>';
    echo '<input type="checkbox" name="blog_bootstrap_is_featured" value="1" ' . checked($is_featured, '1', false) . '>';
    echo esc_html__('Mark as featured post', 'blog-bootstrap');
    echo '</label>';
}

/**
 * Save featured meta box
 */
function blog_bootstrap_save_featured_meta_box($post_id) {
    if (!isset($_POST['blog_bootstrap_featured_nonce']) || !wp_verify_nonce($_POST['blog_bootstrap_featured_nonce'], 'blog_bootstrap_featured_meta_box')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    $is_featured = isset($_POST['blog_bootstrap_is_featured']) ? '1' : '0';
    update_post_meta($post_id, '_is_featured', $is_featured);
}
add_action('save_post', 'blog_bootstrap_save_featured_meta_box');