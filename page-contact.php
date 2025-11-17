<?php
/**
 * Template Name: Contact
 */

get_header(); ?>

<div class="container site-main">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <header class="page-header mb-5">
                <h1 class="page-title"><?php esc_html_e('Contact Us', 'blog-bootstrap'); ?></h1>
                <p class="lead"><?php esc_html_e('Get in touch with us, we\'d love to hear from you!', 'blog-bootstrap'); ?></p>
            </header>

            <div class="card">
                <div class="card-body p-4">
                    <?php
                    if (isset($_POST['submit_contact'])) {
                        // Verify nonce
                        if (!wp_verify_nonce($_POST['contact_nonce'], 'contact_form_nonce')) {
                            echo '<div class="alert alert-danger">' . esc_html__('Security check failed. Please try again.', 'blog-bootstrap') . '</div>';
                        } else {
                            // Sanitize and validate form data
                            $name = isset($_POST['contact_name']) ? sanitize_text_field($_POST['contact_name']) : '';
                            $email = isset($_POST['contact_email']) ? sanitize_email($_POST['contact_email']) : '';
                            $subject = isset($_POST['contact_subject']) ? sanitize_text_field($_POST['contact_subject']) : '';
                            $message = isset($_POST['contact_message']) ? sanitize_textarea_field($_POST['contact_message']) : '';
                            
                            // Validate required fields
                            $errors = array();
                            if (empty($name)) $errors[] = esc_html__('Name is required.', 'blog-bootstrap');
                            if (empty($email)) $errors[] = esc_html__('Email is required.', 'blog-bootstrap');
                            if (!is_email($email)) $errors[] = esc_html__('Please enter a valid email address.', 'blog-bootstrap');
                            if (empty($subject)) $errors[] = esc_html__('Subject is required.', 'blog-bootstrap');
                            if (empty($message)) $errors[] = esc_html__('Message is required.', 'blog-bootstrap');
                            if (strlen($message) < 10) $errors[] = esc_html__('Message must be at least 10 characters long.', 'blog-bootstrap');
                            
                            // Honeypot check
                            if (!empty($_POST['contact_website'])) {
                                // This is likely a bot, don't process but show success
                                echo '<div class="alert alert-success">' . esc_html__('Thank you for your message!', 'blog-bootstrap') . '</div>';
                            } elseif (empty($errors)) {
                                // Process the form
                                $to = get_option('admin_email');
                                $headers = array('Content-Type: text/html; charset=UTF-8', 'From: ' . get_option('admin_email'), 'Reply-To: ' . $name . ' <' . $email . '>');
                                $subject = '[Contact Form] ' . $subject;
                                
                                $email_body = '
                                <html>
                                <body>
                                    <h2>' . esc_html($subject) . '</h2>
                                    <p><strong>' . esc_html__('Name:', 'blog-bootstrap') . '</strong> ' . esc_html($name) . '</p>
                                    <p><strong>' . esc_html__('Email:', 'blog-bootstrap') . '</strong> ' . esc_html($email) . '</p>
                                    <p><strong>' . esc_html__('Message:', 'blog-bootstrap') . '</strong></p>
                                    <p>' . esc_html($message) . '</p>
                                    <hr>
                                    <p><small>' . esc_html__('Sent from:', 'blog-bootstrap') . ' ' . get_bloginfo('name') . ' (' . home_url() . ')</small></p>
                                </body>
                                </html>';
                                
                                if (wp_mail($to, $subject, $email_body, $headers)) {
                                    echo '<div class="alert alert-success">' . esc_html__('Thank you for your message! We\'ll get back to you soon.', 'blog-bootstrap') . '</div>';
                                } else {
                                    echo '<div class="alert alert-danger">' . esc_html__('Sorry, there was an error sending your message. Please try again.', 'blog-bootstrap') . '</div>';
                                }
                            } else {
                                echo '<div class="alert alert-danger"><ul>';
                                foreach ($errors as $error) {
                                    echo '<li>' . esc_html($error) . '</li>';
                                }
                                echo '</ul></div>';
                            }
                        }
                    }
                    ?>
                    
                    <form id="contactForm" method="post" action="">
                        <?php wp_nonce_field('contact_form_nonce', 'contact_nonce'); ?>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contact_name" class="form-label"><?php esc_html_e('Name *', 'blog-bootstrap'); ?></label>
                                <input type="text" class="form-control" id="contact_name" name="contact_name" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="contact_email" class="form-label"><?php esc_html_e('Email *', 'blog-bootstrap'); ?></label>
                                <input type="email" class="form-control" id="contact_email" name="contact_email" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="contact_subject" class="form-label"><?php esc_html_e('Subject *', 'blog-bootstrap'); ?></label>
                            <input type="text" class="form-control" id="contact_subject" name="contact_subject" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="contact_message" class="form-label"><?php esc_html_e('Message *', 'blog-bootstrap'); ?></label>
                            <textarea class="form-control" id="contact_message" name="contact_message" rows="6" required></textarea>
                            <small class="form-text text-muted"><?php esc_html_e('Minimum 10 characters', 'blog-bootstrap'); ?></small>
                        </div>
                        
                        <!-- Honeypot field - hidden from humans -->
                        <div class="d-none">
                            <label for="contact_website"><?php esc_html_e('Website', 'blog-bootstrap'); ?></label>
                            <input type="text" class="form-control" id="contact_website" name="contact_website" tabindex="-1" autocomplete="off">
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="contact_privacy" name="contact_privacy" required>
                                <label class="form-check-label" for="contact_privacy">
                                    <?php esc_html_e('I agree to the privacy policy and consent to my data being processed.', 'blog-bootstrap'); ?>
                                </label>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" name="submit_contact" class="btn btn-primary btn-lg">
                                <?php esc_html_e('Send Message', 'blog-bootstrap'); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced form validation
    const form = document.getElementById('contactForm');
    const submitBtn = form ? form.querySelector('[type="submit"]') : null;
    
    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            const name = document.getElementById('contact_name').value.trim();
            const email = document.getElementById('contact_email').value.trim();
            const subject = document.getElementById('contact_subject').value.trim();
            const message = document.getElementById('contact_message').value.trim();
            const privacy = document.getElementById('contact_privacy').checked;
            
            // Enhanced client-side validation
            let isValid = true;
            let errorMessages = [];
            
            if (!name) {
                isValid = false;
                errorMessages.push('<?php esc_html_e('Name is required.', 'blog-bootstrap'); ?>');
            }
            
            if (!email) {
                isValid = false;
                errorMessages.push('<?php esc_html_e('Email is required.', 'blog-bootstrap'); ?>');
            }
            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                isValid = false;
                errorMessages.push('<?php esc_html_e('Please enter a valid email address.', 'blog-bootstrap'); ?>');
            }
            
            if (!subject) {
                isValid = false;
                errorMessages.push('<?php esc_html_e('Subject is required.', 'blog-bootstrap'); ?>');
            }
            
            if (!message) {
                isValid = false;
                errorMessages.push('<?php esc_html_e('Message is required.', 'blog-bootstrap'); ?>');
            }
            
            if (message.length < 10) {
                isValid = false;
                errorMessages.push('<?php esc_html_e('Message must be at least 10 characters long.', 'blog-bootstrap'); ?>');
            }
            
            if (!privacy) {
                isValid = false;
                errorMessages.push('<?php esc_html_e('You must agree to the privacy policy.', 'blog-bootstrap'); ?>');
            }
            
            if (!isValid) {
                e.preventDefault();
                showValidationErrors(errorMessages);
                return false;
            }
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span><?php esc_html_e('Sending...', 'blog-bootstrap'); ?>';
        });
    }
    
    function showValidationErrors(errors) {
        // Remove existing error alerts
        const existingAlerts = document.querySelectorAll('.alert-danger');
        existingAlerts.forEach(alert => alert.remove());
        
        if (errors.length > 0) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'alert alert-danger mt-3';
            errorDiv.innerHTML = '<ul class="mb-0"><li>' + errors.join('</li><li>') + '</li></ul>';
            
            const form = document.getElementById('contactForm');
            form.parentNode.insertBefore(errorDiv, form);
            
            // Scroll to error
            errorDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            
            // Auto-remove after 10 seconds
            setTimeout(() => {
                if (errorDiv.parentNode) {
                    errorDiv.remove();
                }
            }, 10000);
        }
    }
});
</script>

<?php get_footer(); ?>