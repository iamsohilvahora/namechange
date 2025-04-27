jQuery(document).ready(function($) {
    // Initialize the phone input mask
    $('#billing_phone').mask('999-999-9999');

    // Watch for change in the "Will the applicant be Applying for a passport?" radio button
    $('input[name="applying_passport"]').change(function() {
        if ($(this).val() === 'Yes') {
            // Show and enable passport related fields
            $('#custom-thankyou-form').find('.applying_passport_no_class').css('display', 'block');
            $('#custom-thankyou-form').find('.applying_passport_no_class').find('input, select, textarea').prop('disabled', false).prop('required', true);
        } else {
            // Hide and disable passport related fields
            $('#custom-thankyou-form').find('.applying_passport_no_class').css('display', 'none');
            $('#custom-thankyou-form').find('.applying_passport_no_class').find('input, select, textarea').prop('disabled', true).prop('required', false);
        }
    });

    $('#custom-thankyou-form').submit(function(e) {
        e.preventDefault();

        // Client-side validation.
        if (!validateForm()) {
            return false; // Prevent form submission if validation fails.
        }

        let ajaxUrl = namechange_obj.ajax_url;

        let formData = new FormData(this);
        formData.append('action', 'save_custom_applicant_form');          

        // Disable submit button and change text to "Submitting..."
        $('#applicant_submit').prop('disabled', true); // Disable button
        $('#applicant_submit').text('Submitting...');

        $.ajax({
            url: ajaxUrl,
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    // Display success message.
                    $('#alertMessage').removeClass('alert-danger').addClass('alert-success').html(response.data.message).show();
                    $('#custom-thankyou-form').remove();
                } else {
                    // Display error message.
                    $('#alertMessage').removeClass('alert-success').addClass('alert-danger').html(response.data.message).show();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Display error message.
                $('#alertMessage').removeClass('alert-success').addClass('alert-danger').html('There is an error to submitting the form. Please try again later..').show();
            }
        });
    });

    // Function to validate the form
    function validateForm() {
        // Reset any previous error messages
        $('.invalid-feedback').remove();

        let isValid = true;

        // Validate postal_code field
        let postalCode = $('#postal_code').val().trim();
        // Check if postal_code field is required
        if ($('#postal_code').prop('required')) {
            if (postalCode.length !== 5 || !/^\d{5}$/.test(postalCode)) {
                $('#postal_code').after('<span class="invalid-feedback">Please enter exactly 5 numbers for the postal code.</span>').show();
                $('span.invalid-feedback').show();
                isValid = false;
                if (!isValid) {
                    $('#postal_code').focus(); // Move cursor to this field if it's the first error found
                }
            }
        }

        // Validate text-only fields
        if (!isValidTextOnly($('#parent2_name').val())) {
            $('#parent2_name').after('<span class="invalid-feedback">Please enter valid text only.</span>').show();
            $('span.invalid-feedback').show();
            isValid = false;
            if (!isValid) {
                $('#parent2_name').focus(); // Move cursor to this field if it's the first error found
            }
        }

        // Validate text-only fields
        if (!isValidTextOnly($('#parent1_name').val())) {
            $('#parent1_name').after('<span class="invalid-feedback">Please enter valid text only.</span>').show();
            $('span.invalid-feedback').show();
            isValid = false;
            if (!isValid) {
                $('#parent1_name').focus(); // Move cursor to this field if it's the first error found
            }
        }

        return isValid;
    }

    // Function to validate text-only fields
    function isValidTextOnly(value) {
        return /^[a-zA-Z\s]+$/.test(value); // Only allow letters and spaces
    }

});
