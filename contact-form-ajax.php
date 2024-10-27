<?php
/*
Plugin Name: AJAX Contact Form
Description: A contact form with AJAX submission.
Version: 1.0
Author: Hager Sayed
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register the shortcode and enqueue scripts
add_action('init', 'register_contact_form_shortcode');
add_action('wp_enqueue_scripts', 'enqueue_contact_form_scripts');
add_action('wp_ajax_submit_contact_form', 'handle_contact_form_submission');
add_action('wp_ajax_nopriv_submit_contact_form', 'handle_contact_form_submission');

/**
 * Register the shortcode for the contact form
 */
function register_contact_form_shortcode() {
    add_shortcode('ajax_contact_form', 'render_contact_form');
}

/**
 * Render the contact form HTML
 */
function render_contact_form() {
    ob_start();
    ?>
    <form id="contact-form" action="#" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="message">Message:</label>
        <textarea id="message" name="message" required></textarea>
        
        <button type="submit">Send Message</button>
        <div id="form-response"></div>
    </form>
    <?php
    return ob_get_clean();
}

/**
 * Enqueue the JavaScript for AJAX functionality
 */
function enqueue_contact_form_scripts() {
    // Enqueue custom script with jQuery as a dependency
    wp_enqueue_script('contact-form-ajax', plugin_dir_url(__FILE__) . 'contact-form.js', array('jquery'), null, true);

    // Localize script to provide ajaxurl and a nonce for security
    wp_localize_script('contact-form-ajax', 'contactFormAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('contact_form_nonce') // Generates a security nonce
    ));
}

/**
 * Handle AJAX form submission
 */
function handle_contact_form_submission() {
    // Security check: verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'contact_form_nonce')) {
        wp_send_json_error(['message' => 'Invalid request.']);
    }

    // Check for missing fields and sanitize input
    $name = sanitize_text_field($_POST['name'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $message = sanitize_textarea_field($_POST['message'] ?? '');

    // Validate fields
    if (empty($name) || empty($email) || empty($message)) {
        wp_send_json_error(['message' => 'All fields are required.']);
    }
    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Invalid email address.']);
    }

    // Prepare email
    $admin_email = get_option('admin_email');
    $subject = 'New Contact Form Message';
    $body = "Name: $name\nEmail: $email\nMessage: $message";
    $headers = ['Content-Type: text/plain; charset=UTF-8'];

    // Attempt to send the email
    if (wp_mail($admin_email, $subject, $body, $headers)) {
        wp_send_json_success(['message' => 'Thank you for your message!']);
    } else {
        wp_send_json_error(['message' => 'Unable to send message. Please try again later.']);
    }
}
?>
