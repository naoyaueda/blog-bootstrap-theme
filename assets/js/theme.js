/**
 * BlogBootstrap Theme JavaScript
 * Modern, optimized JavaScript with reduced jQuery dependency
 * 
 * @package BlogBootstrap
 * @version 2.0.0
 */

(function() {
    'use strict';

    // Utility functions
    const BlogBootstrap = {
        // Debounce function for performance
        debounce(func, wait, immediate) {
            let timeout;
            return function() {
                const context = this, args = arguments;
                const later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                const callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        },

        // Throttle function for scroll events
        throttle(func, limit) {
            let inThrottle;
            return function() {
                const args = arguments;
                const context = this;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            };
        },

        // Check if element is in viewport
        isInViewport(element) {
            const rect = element.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        },

        // Animate elements when they come into view
        animateOnScroll() {
            const elements = document.querySelectorAll('.animate-on-scroll');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animated');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1
            });

            elements.forEach(element => {
                observer.observe(element);
            });
        },

        // Smooth scroll to element
        smoothScroll(target, offset = 80) {
            const targetElement = document.querySelector(target);
            if (targetElement) {
                const targetPosition = targetElement.offsetTop - offset;
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        },

        // Show/hide element with fade effect
        toggleFade(element, show) {
            if (show) {
                element.style.opacity = '1';
                element.style.display = 'block';
            } else {
                element.style.opacity = '0';
                setTimeout(() => {
                    element.style.display = 'none';
                }, 300);
            }
        }
    };

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        
        // Back to top button
        const backToTop = document.getElementById('backToTop');
        if (backToTop) {
            window.addEventListener('scroll', BlogBootstrap.throttle(function() {
                BlogBootstrap.toggleFade(backToTop, window.pageYOffset > 300);
            }, 100));

            backToTop.addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]:not([href="#"]):not([href="#0"])').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const target = this.getAttribute('href');
                if (document.querySelector(target)) {
                    e.preventDefault();
                    BlogBootstrap.smoothScroll(target);
                }
            });
        });

        // Mobile menu enhancements
        const navbarToggler = document.querySelector('.navbar-toggler');
        const mainNav = document.getElementById('main-nav');
        
        if (navbarToggler && mainNav) {
            navbarToggler.addEventListener('click', function() {
                this.classList.toggle('active');
                mainNav.classList.toggle('show');
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.navbar')) {
                    navbarToggler.classList.remove('active');
                    mainNav.classList.remove('show');
                }
            });
        }

        // Enhanced dropdown menus (using Bootstrap's built-in functionality)
        const dropdowns = document.querySelectorAll('.dropdown');
        dropdowns.forEach(dropdown => {
            dropdown.addEventListener('mouseenter', function() {
                const menu = this.querySelector('.dropdown-menu');
                if (menu) {
                    menu.style.opacity = '1';
                    menu.style.transform = 'translateY(0)';
                }
            });

            dropdown.addEventListener('mouseleave', function() {
                const menu = this.querySelector('.dropdown-menu');
                if (menu) {
                    menu.style.opacity = '0';
                    menu.style.transform = 'translateY(-10px)';
                }
            });
        });

        // Search modal enhancements
        const searchModal = document.getElementById('searchModal');
        if (searchModal) {
            searchModal.addEventListener('shown.bs.modal', function() {
                const searchInput = searchModal.querySelector('input[type="search"]');
                if (searchInput) {
                    searchInput.focus();
                }
            });

            searchModal.addEventListener('hidden.bs.modal', function() {
                const searchInput = searchModal.querySelector('input[type="search"]');
                if (searchInput) {
                    searchInput.value = '';
                }
            });
        }

        // Load more posts functionality
        const loadMoreBtn = document.getElementById('loadMorePosts');
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function() {
                const button = this;
                const paged = parseInt(button.dataset.paged) || 1;
                const maxPages = parseInt(button.dataset.maxPages) || 1;
                const container = document.getElementById('posts-container');
                
                if (paged > maxPages) return;
                
                // Show loading state
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i><?php esc_html_e('Loading...', 'blog-bootstrap'); ?>';
                
                // Prepare AJAX data
                const data = new FormData();
                data.append('action', 'blog_bootstrap_load_more_posts');
                data.append('nonce', blog_bootstrap_vars.nonce);
                data.append('paged', paged);
                
                // Add additional parameters based on current page type
                if (button.dataset.search) {
                    data.append('search', button.dataset.search);
                } else if (button.dataset.archive) {
                    data.append('archive', button.dataset.archive);
                    data.append('term', button.dataset.term);
                } else if (button.dataset.category) {
                    data.append('category', button.dataset.category);
                }
                
                fetch(blog_bootstrap_vars.ajax_url, {
                    method: 'POST',
                    body: data
                })
                .then(response => response.json())
                .then(response => {
                    if (response.success) {
                        // Append new posts
                        container.insertAdjacentHTML('beforeend', response.data.posts);
                        
                        // Update button state
                        const newPaged = paged + 1;
                        button.dataset.paged = newPaged;
                        
                        if (newPaged > maxPages) {
                            button.style.display = 'none';
                        } else {
                            button.innerHTML = '<i class="fas fa-plus me-2"></i><?php esc_html_e('Load More Posts', 'blog-bootstrap'); ?>';
                            button.disabled = false;
                        }
                        
                        // Trigger custom event for other scripts
                        document.dispatchEvent(new CustomEvent('postsLoaded', { 
                            detail: { posts: response.data.posts } 
                        }));
                    } else {
                        // Show error message
                        button.insertAdjacentHTML('afterend', `<div class="alert alert-danger mt-3">${response.data}</div>`);
                        button.style.display = 'none';
                    }
                })
                .catch(error => {
                    button.insertAdjacentHTML('afterend', '<div class="alert alert-danger mt-3"><?php esc_html_e('An error occurred. Please try again.', 'blog-bootstrap'); ?></div>');
                    button.style.display = 'none';
                });
            });
        }

        // Contact form AJAX submission
        const contactForm = document.getElementById('contactForm');
        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const form = this;
                const submitBtn = form.querySelector('button[type="submit"]');
                const formData = new FormData(this);
                
                // Add AJAX action
                formData.append('action', 'blog_bootstrap_contact_form');
                formData.append('nonce', blog_bootstrap_vars.nonce);
                
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i><?php esc_html_e('Sending...', 'blog-bootstrap'); ?>';
                
                // Remove existing alerts
                form.querySelectorAll('.alert').forEach(alert => alert.remove());
                
                fetch(blog_bootstrap_vars.ajax_url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(response => {
                    if (response.success) {
                        // Show success message
                        form.insertAdjacentHTML('afterbegin', `<div class="alert alert-success" role="alert">${response.data}</div>`);
                        form.reset();
                    } else {
                        // Show error message
                        if (response.data.errors) {
                            let errorHtml = '<div class="alert alert-danger" role="alert"><strong><?php esc_html_e('Please fix the following errors:', 'blog-bootstrap'); ?></strong><ul>';
                            response.data.errors.forEach(error => {
                                errorHtml += `<li>${error}</li>`;
                            });
                            errorHtml += '</ul></div>';
                            form.insertAdjacentHTML('afterbegin', errorHtml);
                        } else {
                            form.insertAdjacentHTML('afterbegin', `<div class="alert alert-danger" role="alert">${response.data}</div>`);
                        }
                    }
                    
                    // Scroll to top of form
                    form.scrollIntoView({ behavior: 'smooth', block: 'start' });
                })
                .catch(error => {
                    form.insertAdjacentHTML('afterbegin', '<div class="alert alert-danger" role="alert"><?php esc_html_e('An error occurred. Please try again.', 'blog-bootstrap'); ?></div>');
                })
                .finally(() => {
                    // Reset button state
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i><?php esc_html_e('Send Message', 'blog-bootstrap'); ?>';
                });
            });
        }

        // Newsletter form submission
        document.querySelectorAll('.newsletter-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const form = this;
                const submitBtn = form.querySelector('button[type="submit"]');
                const email = form.querySelector('input[type="email"]').value;
                
                if (!email) {
                    form.querySelectorAll('.alert').forEach(alert => alert.remove());
                    form.insertAdjacentHTML('afterbegin', '<div class="alert alert-danger"><?php esc_html_e('Please enter your email address.', 'blog-bootstrap'); ?></div>');
                    return;
                }
                
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                
                const formData = new FormData();
                formData.append('action', 'blog_bootstrap_newsletter_subscribe');
                formData.append('nonce', blog_bootstrap_vars.nonce);
                formData.append('email', email);
                
                fetch(blog_bootstrap_vars.ajax_url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(response => {
                    form.querySelectorAll('.alert').forEach(alert => alert.remove());
                    if (response.success) {
                        form.insertAdjacentHTML('afterbegin', `<div class="alert alert-success">${response.data}</div>`);
                        form.reset();
                    } else {
                        form.insertAdjacentHTML('afterbegin', `<div class="alert alert-danger">${response.data}</div>`);
                    }
                })
                .catch(error => {
                    form.querySelectorAll('.alert').forEach(alert => alert.remove());
                    form.insertAdjacentHTML('afterbegin', '<div class="alert alert-danger"><?php esc_html_e('An error occurred. Please try again.', 'blog-bootstrap'); ?></div>');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<?php esc_html_e('Subscribe', 'blog-bootstrap'); ?>';
                });
            });
        });

        // Image lazy loading with fade-in effect
        document.querySelectorAll('img[loading="lazy"]').forEach(img => {
            if (img.complete) {
                img.classList.add('loaded');
            } else {
                img.addEventListener('load', function() {
                    this.classList.add('loaded');
                });
            }
        });

        // Enhanced accessibility for keyboard navigation
        document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
            toggle.addEventListener('keydown', function(e) {
                if (e.keyCode === 13 || e.keyCode === 32) { // Enter or Space
                    e.preventDefault();
                    // Use Bootstrap's dropdown API if available
                    if (window.bootstrap && window.bootstrap.Dropdown) {
                        const dropdown = new bootstrap.Dropdown(this);
                        dropdown.toggle();
                    }
                }
            });
        });

        // Focus management for modals
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('shown.bs.modal', function() {
                const focusableElement = this.querySelector('input, textarea, button, select');
                if (focusableElement) {
                    focusableElement.focus();
                }
            });
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert-success, .alert-info').forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);

        // Initialize tooltips if Bootstrap is available
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        // Performance optimization: Debounce resize events
        window.addEventListener('resize', BlogBootstrap.debounce(function() {
            // Handle responsive adjustments
            if (window.innerWidth < 992) {
                document.querySelectorAll('.navbar-nav').forEach(nav => nav.classList.add('mobile-nav'));
            } else {
                document.querySelectorAll('.navbar-nav').forEach(nav => nav.classList.remove('mobile-nav'));
            }
        }, 250));

        // Initialize animations
        BlogBootstrap.animateOnScroll();
    });

    // Custom event handlers
    document.addEventListener('postsLoaded', function(e) {
        // Re-initialize any scripts for newly loaded content
        e.detail.posts.querySelectorAll('img[loading="lazy"]').forEach(img => {
            if (img.complete) {
                img.classList.add('loaded');
            } else {
                img.addEventListener('load', function() {
                    this.classList.add('loaded');
                });
            }
        });

        // Re-initialize tooltips for new content
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            const tooltipTriggerList = [].slice.call(e.detail.posts.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    });

    // Make utilities available globally
    window.BlogBootstrap = BlogBootstrap;

})();