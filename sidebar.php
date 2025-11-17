<?php
/**
 * The sidebar containing the main widget area
 */

// Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}

if (!is_active_sidebar('sidebar-1')) {
    return;
}
?>

<aside class="widget-area" id="secondary">
    <?php dynamic_sidebar('sidebar-1'); ?>
</aside>