<?php
/**
 * The template for displaying the footer
 * Contains the closing of the #content div and all content after
 */

// Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}
?>
    </main><!-- #content -->

    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <!-- About CalgaryJP - Widgetizable -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <?php if (is_active_sidebar('footer-1')) : ?>
                        <?php dynamic_sidebar('footer-1'); ?>
                    <?php else : ?>
                        <!-- Default About content when no widget is active -->
                        <h3 class="widget-title"><?php bloginfo('name'); ?></h3>
                        <p><?php echo wp_kses_post(get_bloginfo('description')); ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Quick Links -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h3 class="widget-title"><?php esc_html_e('Quick Links', 'blog-bootstrap'); ?></h3>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'menu_class'     => 'list-unstyled',
                        'container'      => false,
                        'fallback_cb'    => false,
                        'depth'          => 1,
                    ));
                    ?>
                </div>
                
                <!-- Contact Info -->
                <div class="col-lg-4 col-md-12 mb-4">
                    <h3 class="widget-title"><?php esc_html_e('Contact Info', 'blog-bootstrap'); ?></h3>
                    <address>
                        <p><i class="fas fa-map-marker-alt me-2"></i> <?php esc_html_e('Calgary, Alberta, Canada', 'blog-bootstrap'); ?></p>
                        <p><i class="fas fa-envelope me-2"></i> <a href="mailto:info@calgary.jp" class="footer-email">info@calgary.jp</a></p>
                    </address>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0">
                        &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. 
                        <?php esc_html_e('All rights reserved.', 'blog-bootstrap'); ?>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>