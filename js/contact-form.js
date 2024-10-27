jQuery(document).ready(function($) {
    $('#contact-form').on('submit', function(e) {
        e.preventDefault();

        // Prepare data for AJAX
        let formData = {
            action: 'submit_contact_form',
            name: $('#name').val(),
            email: $('#email').val(),
            message: $('#message').val(),
            nonce: contactFormAjax.nonce // Adding the nonce for security
        };

        // Perform AJAX request
        $.post(contactFormAjax.ajaxurl, formData, function(response) {
            $('#form-response').html(response.message); // Display message in response div
            if (response.success) {
                $('#contact-form')[0].reset(); // Clear form on success
            }
        });
    });
});
