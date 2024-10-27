# AJAX Contact Form Plugin

A simple WordPress plugin that provides a contact form with AJAX submission. The form fields include **Name**, **Email**, and **Message**. This form submits data via AJAX to avoid page reloads, validates inputs, and sends the data to the WordPress admin email.

## Features

- AJAX-powered form submission to prevent page reloads.
- Shortcode `[ajax_contact_form]` to easily add the form to any page or post.
- Server-side validation and sanitization for security.
- Sends form data via email to the site administrator.
- Displays success or error messages upon form submission.

## Installation

1. **Download the Plugin**
   - Download or clone this repository.

2. **Upload to WordPress**
   - Upload the `contact-form-ajax` folder to your WordPress `wp-content/plugins/` directory.

3. **Activate the Plugin**
   - Go to **Plugins** in your WordPress dashboard and activate the **AJAX Contact Form** plugin.

## Usage

1. After activating the plugin, you can add the contact form to any page or post using the following shortcode:
   ```plaintext
   [ajax_contact_form]

