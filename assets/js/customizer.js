/**
 * Customizer Live Preview JavaScript
 */

(function($) {
    'use strict';

    // Update primary color
    wp.customize('primary_color', function(value) {
        value.bind(function(newVal) {
            document.documentElement.style.setProperty('--primary-color', newVal);
        });
    });

    // Update secondary color
    wp.customize('secondary_color', function(value) {
        value.bind(function(newVal) {
            document.documentElement.style.setProperty('--secondary-color', newVal);
        });
    });

    // Update accent color
    wp.customize('accent_color', function(value) {
        value.bind(function(newVal) {
            document.documentElement.style.setProperty('--accent-color', newVal);
        });
    });

})(jQuery);