<?php
/**
 * Search Form Template
 */

// Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <div class="input-group">
        <input type="search" 
               class="form-control search-field" 
               placeholder="<?php esc_attr_e('Search...', 'blog-bootstrap'); ?>" 
               value="<?php echo get_search_query(); ?>" 
               name="s" 
               id="search-input"
               aria-label="<?php esc_attr_e('Search', 'blog-bootstrap'); ?>">
        <button class="btn btn-primary" type="submit" id="search-submit">
            <i class="fas fa-search"></i>
            <span class="d-none d-md-inline-block ms-1"><?php esc_html_e('Search', 'blog-bootstrap'); ?></span>
        </button>
    </div>
</form>