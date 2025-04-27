<?php
// Add field type to WooCommerce form field.
function namechange_woocommerce_form_field_heading($field, $key, $args, $value) {
    $output = '<h3 class="form-row form-row-wide">'.__( $args['label'], 'woocommerce' ).'</h3>';
    echo $output;
}
add_filter( 'woocommerce_form_field_heading','namechange_woocommerce_form_field_heading', 10, 4 );

// Customize checkout page field.
function namechange_customize_checkout_fields($fields) {
    // Remove billing fields
    unset($fields['billing']['billing_first_name']);
    unset($fields['billing']['billing_last_name']);
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_email']);
    unset($fields['billing']['billing_phone']);

    // Order fields
    unset( $fields['order']['order_comments'] );

    // Add custom fields under the custom headings
    // Name Details - Applicant Current Name
    $fields['billing']['billing_heading_applicant_current'] = array(
       'type' => 'heading',
       'label' => __('1. Name Details - Applicant Current Name', 'woocommerce'),
    );

    // Name Details - Applicant Current Name
    $fields['billing']['billing_email'] = array(
        'label'       => __('Email', 'woocommerce'),
        'placeholder' => _x('Email', 'placeholder', 'woocommerce'),
        'required'    => true,
        'class'       => array('form-row-first'),
        'clear'       => true
    );

    $fields['billing']['billing_phone'] = array(
        'label'       => __('Contact Number', 'woocommerce'),
        'placeholder' => _x('xxx-xxx-xxxx', 'placeholder', 'woocommerce'),
        'required'    => true,
        'class'       => array('form-row-last'),
        'clear'       => true,
    );

    $fields['billing']['billing_former_first_name'] = array(
        'label'       => __('Former First Name', 'woocommerce'),
        'placeholder' => _x('Former First Name', 'placeholder', 'woocommerce'),
        'required'    => true,
        'class'       => array('form-row-first'),
        'clear'       => true
    );

    $fields['billing']['billing_former_middle_name'] = array(
        'label'       => __('Former Middle Name', 'woocommerce'),
        'placeholder' => _x('Former Middle Name', 'placeholder', 'woocommerce'),
        'required'    => false,
        'class'       => array('form-row-last'),
        'clear'       => true
    );

    $fields['billing']['billing_former_last_name'] = array(
        'label'       => __('Former Last Name', 'woocommerce'),
        'placeholder' => _x('Former Last Name', 'placeholder', 'woocommerce'),
        'required'    => true,
        'class'       => array('form-row-first'),
        'clear'       => true
    );

    // **Heading 2: Applicant New Name**
    // Name Details - Applicant New Name
    $fields['billing']['billing_heading_applicant_new'] = array(
        'type' => 'heading',
        'label' => __('2. Name Details - Applicant New Name', 'woocommerce'),
    );

    // Name Details - Applicant New Name
    $fields['billing']['billing_new_first_name'] = array(
        'label'       => __('New First Name', 'woocommerce'),
        'placeholder' => _x('New First Name', 'placeholder', 'woocommerce'),
        'required'    => true,
        'class'       => array('form-row-first'),
        'clear'       => true
    );

    $fields['billing']['billing_new_middle_name'] = array(
        'label'       => __('New Middle Name', 'woocommerce'),
        'placeholder' => _x('New Middle Name', 'placeholder', 'woocommerce'),
        'required'    => false,
        'class'       => array('form-row-last'),
        'clear'       => true
    );

    $fields['billing']['billing_new_last_name'] = array(
        'label'       => __('New Last Name', 'woocommerce'),
        'placeholder' => _x('New Last Name', 'placeholder', 'woocommerce'),
        'required'    => true,
        'class'       => array('form-row-first'),
        'clear'       => true
    );

    return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'namechange_customize_checkout_fields' );

// Validate checkout fields on the WooCommerce checkout page.
function namechange_custom_checkout_field_validation() {
    $name_fields = array(
        'billing_email' => 'Email',
        'billing_phone' => 'Contact Number',
        'billing_former_first_name' => 'Former First Name',
        'billing_former_middle_name' => 'Former Middle Name',
        'billing_former_last_name' => 'Former Last Name',
        'billing_new_first_name' => 'New First Name',
        'billing_new_middle_name' => 'New Middle Name',
        'billing_new_last_name' => 'New Last Name',
    );

    $validation_errors = new WP_Error(); // Create a WP_Error object for validation messages

    foreach ($name_fields as $field_name => $field_label) {
        if (isset($_POST[$field_name]) && !empty($_POST[$field_name]) && $field_name === "billing_email") {
            if ( ! isset( $_POST['billing_email'] ) || empty( $_POST['billing_email'] ) ) {
                $validation_errors->add(
                    $field_name,
                    sprintf(
                        __('<strong>%s</strong>: Please enter a valid email address.', 'woocommerce'),
                        $field_label
                    )
                );
            } else if ( ! is_email( $_POST['billing_email'] ) ) {
                $validation_errors->add(
                    $field_name,
                    sprintf(
                       __('<strong>%s</strong>: Please enter a valid email address.', 'woocommerce'),
                       $field_label
                    )
                );
            }
        }
        elseif(isset($_POST[$field_name]) && !empty($_POST[$field_name]) && $field_name === "billing_phone") {
            $phone_number = $_POST['billing_phone'];
                    
            // Validate US phone number format (xxx-xxx-xxxx)
            if (!preg_match('/^\d{3}-\d{3}-\d{4}$/', $phone_number)) {
                $validation_errors->add(
                    $field_name,
                    sprintf(
                        __('<strong>%s</strong>: Please enter a valid US format contact number (xxx-xxx-xxxx).', 'woocommerce'),
                        $field_label
                    )
                );
            }
        }
        elseif (isset($_POST[$field_name]) && !empty($_POST[$field_name])) {
            $name = sanitize_text_field($_POST[$field_name]); // Sanitize the name

            // Check if the name contains only letters and no spaces
            if (!preg_match('/^[a-zA-Z]+$/', $name)) {
                $validation_errors->add(
                    $field_name,
                    sprintf(
                        __('<strong>%s</strong>: Please enter a valid name.', 'woocommerce'),
                        $field_label
                    )
                );
            }
        }
    }

    if ($validation_errors->get_error_messages()) {
        wc_add_notice(implode('<br/>', $validation_errors->get_error_messages()), 'error'); // Add error messages to the checkout notices
    }
}
add_action('woocommerce_checkout_process', 'namechange_custom_checkout_field_validation', 20, 1);

// Save custom fields to order meta.
function namechange_save_custom_checkout_fields($order_id) {
    if (!empty($_POST['billing_former_first_name'])) {
        update_post_meta($order_id, '_billing_former_first_name', sanitize_text_field($_POST['billing_former_first_name']));
    }

    if (!empty($_POST['billing_former_middle_name'])) {
        update_post_meta($order_id, '_billing_former_middle_name', sanitize_text_field($_POST['billing_former_middle_name']));
    }

    if (!empty($_POST['billing_former_last_name'])) {
        update_post_meta($order_id, '_billing_former_last_name', sanitize_text_field($_POST['billing_former_last_name']));
    }

    if (!empty($_POST['billing_new_first_name'])) {
        update_post_meta($order_id, '_billing_new_first_name', sanitize_text_field($_POST['billing_new_first_name']));
    }

    if (!empty($_POST['billing_new_middle_name'])) {
        update_post_meta($order_id, '_billing_new_middle_name', sanitize_text_field($_POST['billing_new_middle_name']));
    }

    if (!empty($_POST['billing_new_last_name'])) {
        update_post_meta($order_id, '_billing_new_last_name', sanitize_text_field($_POST['billing_new_last_name']));
    }
}
add_action( 'woocommerce_checkout_update_order_meta', 'namechange_save_custom_checkout_fields' );

// Display custom fields in the order edit page in the admin.
function namechange_display_custom_checkout_fields_in_admin($order) {
    $order_id = $order->get_id();
    echo '<p><strong>' . __('Former First Name') . ':</strong> ' . get_post_meta($order_id, '_billing_former_first_name', true) . '</p>';
    echo '<p><strong>' . __('Former Middle Name') . ':</strong> ' . get_post_meta($order_id, '_billing_former_middle_name', true) . '</p>';
    echo '<p><strong>' . __('Former Last Name') . ':</strong> ' . get_post_meta($order_id, '_billing_former_last_name', true) . '</p>';
    echo '<p><strong>' . __('New First Name') . ':</strong> ' . get_post_meta($order_id, '_billing_new_first_name', true) . '</p>';
    echo '<p><strong>' . __('New Middle Name') . ':</strong> ' . get_post_meta($order_id, '_billing_new_middle_name', true) . '</p>';
    echo '<p><strong>' . __('New Last Name') . ':</strong> ' . get_post_meta($order_id, '_billing_new_last_name', true) . '</p>';

    echo '<p><strong>' . __('Applicant DOB') . ':</strong> ' . get_post_meta($order_id, '_applicant_dob', true) . '</p>';
    echo '<p><strong>' . __('Applicant Sex') . ':</strong> ' . get_post_meta($order_id, '_applicant_sex', true) . '</p>';
    echo '<p><strong>' . __('City') . ':</strong> ' . get_post_meta($order_id, '_city', true) . '</p>';
    echo '<p><strong>' . __('State or Country') . ':</strong> ' . get_post_meta($order_id, '_state_or_country', true) . '</p>';
    echo '<p><strong>' . __('Parent 1 Name') . ':</strong> ' . get_post_meta($order_id, '_parent1_name', true) . '</p>';
    echo '<p><strong>' . __('Parent 2 Name') . ':</strong> ' . get_post_meta($order_id, '_parent2_name', true) . '</p>';
    echo '<p><strong>' . __('Change DL') . ':</strong> ' . get_post_meta($order_id, '_change_dl', true) . '</p>';
    echo '<p><strong>' . __('DL State') . ':</strong> ' . get_post_meta($order_id, '_dl_state', true) . '</p>';
    echo '<p><strong>' . __('Applying Passport') . ':</strong> ' . get_post_meta($order_id, '_applying_passport', true) . '</p>';
    echo '<p><strong>' . __('Have Passport') . ':</strong> ' . get_post_meta($order_id, '_have_passport', true) . '</p>';
    echo '<p><strong>' . __('Passport Issued at 16') . ':</strong> ' . get_post_meta($order_id, '_passport_issued_16', true) . '</p>';
    echo '<p><strong>' . __('Passport Issued at 15') . ':</strong> ' . get_post_meta($order_id, '_passport_issued_15', true) . '</p>';
    echo '<p><strong>' . __('Passport Damaged') . ':</strong> ' . get_post_meta($order_id, '_passport_damaged', true) . '</p>';
    echo '<p><strong>' . __('Name Changed') . ':</strong> ' . get_post_meta($order_id, '_name_changed', true) . '</p>';
    echo '<p><strong>' . __('Height (Feet)') . ':</strong> ' . get_post_meta($order_id, '_height_feet', true) . '</p>';
    echo '<p><strong>' . __('Height (Inches)') . ':</strong> ' . get_post_meta($order_id, '_height_inches', true) . '</p>';
    echo '<p><strong>' . __('Hair Color') . ':</strong> ' . get_post_meta($order_id, '_hair_color', true) . '</p>';
    echo '<p><strong>' . __('Eye Color') . ':</strong> ' . get_post_meta($order_id, '_eye_color', true) . '</p>';
    echo '<p><strong>' . __('Permanent add. same as mailing add.') . ':</strong> ' . get_post_meta($order_id, '_same_as_mailing', true) . '</p>';
    echo '<p><strong>' . __('Street Address') . ':</strong> ' . get_post_meta($order_id, '_street_address', true) . '</p>';
    echo '<p><strong>' . __('Address Line 2') . ':</strong> ' . get_post_meta($order_id, '_address_line2', true) . '</p>';
    echo '<p><strong>' . __('Permanent City') . ':</strong> ' . get_post_meta($order_id, '_permanent_city', true) . '</p>';
    echo '<p><strong>' . __('Permanent State') . ':</strong> ' . get_post_meta($order_id, '_permanent_state', true) . '</p>';
    echo '<p><strong>' . __('Postal Code') . ':</strong> ' . get_post_meta($order_id, '_postal_code', true) . '</p>';
}
add_action('woocommerce_admin_order_data_after_billing_address', 'namechange_display_custom_checkout_fields_in_admin', 10, 1);

// Add POS heading before the "Your order" section.
function namechange_add_pos_heading_before_order_review() {
    echo '<h3>' . __('3. POS (Stripe or other) General Payment & Shipping Details', 'woocommerce') . '</h3>';
}
add_action( 'woocommerce_checkout_before_order_review_heading', 'namechange_add_pos_heading_before_order_review' );

// Disable my-account page
function namechange_disable_my_account_page_redirect() {
    if (is_account_page()) {
        wp_redirect(home_url());
        exit;
    }
}
add_action('template_redirect', 'namechange_disable_my_account_page_redirect');

// Thank you page form's custom fields.
function namechange_custom_thankyou_form($order) {
    // Check if user already submitted the form.
    if ( get_post_meta( $order->get_id(), 'applicant_details_submitted', true ) ) { ?>
        <div class="alert alert-success">Applicant form already submitted.</div>
    <?php
        return;
    }
    ?>
    <div id="alertMessage" class="alert" role="alert" style="display: none;"></div>
    <form id="custom-thankyou-form" method="post">
        <h2>Applicant Details</h2>
        <input type="hidden" name="order_id" value="<?php echo esc_attr($order->get_id()); ?>" />
        <label for="applicant_dob">Applicant Date Of Birth (DD/MM/YYYY)*</label>
        <input type="date" class="form-control" name="applicant_dob" id="applicant_dob" required><br>

        <label>Applicant Sex*</label><br>
        <label>
            <input type="radio" class="form-check-input" name="applicant_sex" value="Male" required> Male
        </label>
        <label>
            <input type="radio" class="form-check-input" name="applicant_sex" value="Female" required> Female
        </label>
        <label>
            <input type="radio" class="form-check-input" name="applicant_sex" value="x" required> X
        </label><br>

        <label for="city">City*</label>
        <input type="text" class="form-control" name="city" id="city" required><br>

        <label for="state_or_country">State (if in the U.S.), or Country (if in foreign country)*</label>
        <input type="text" class="form-control" name="state_or_country" id="state_or_country" required><br>

        <label for="parent1_name">Mother/Father/Parent (1) - First, Middle, & Last Name (at Parent's Birth)</label>
        <input type="text" class="form-control" name="parent1_name" id="parent1_name"><br>

        <label for="parent2_name">Mother/Father/Parent (2) - First, Middle, & Last Name (at Parent's Birth)</label>
        <input type="text" class="form-control" name="parent2_name" id="parent2_name"><br>

        <h2>Driver License & Passport Details</h2>
        
        <label>Will you be changing your name on your driver's license*</label><br>
        <label>
            <input type="radio" class="form-check-input" name="change_dl" value="Yes" required> Yes
        </label>
        <label>
            <input type="radio" class="form-check-input" name="change_dl" value="No" required> No
        </label><br>

        <label for="dl_state">State Where You would like to apply for a driver license</label>
        <select name="dl_state" class="form-select" id="dl_state">
            <?php
            $state_arr = array(
                '' => __( 'Select a state', 'woocommerce' ),
                'AL' => __( 'Alabama', 'woocommerce' ),
                'AK' => __( 'Alaska', 'woocommerce' ),
                'AZ' => __( 'Arizona', 'woocommerce' ),
                'AR' => __( 'Arkansas', 'woocommerce' ),
                'CA' => __( 'California', 'woocommerce' ),
                'CO' => __( 'Colorado', 'woocommerce' ),
                'CT' => __( 'Connecticut', 'woocommerce' ),
                'DE' => __( 'Delaware', 'woocommerce' ),
                'DC' => __( 'District of Columbia', 'woocommerce' ),
                'FL' => __( 'Florida', 'woocommerce' ),
                'GA' => __( 'Georgia', 'woocommerce' ),
                'HI' => __( 'Hawaii', 'woocommerce' ),
                'ID' => __( 'Idaho', 'woocommerce' ),
                'IL' => __( 'Illinois', 'woocommerce' ),
                'IN' => __( 'Indiana', 'woocommerce' ),
                'IA' => __( 'Iowa', 'woocommerce' ),
                'KS' => __( 'Kansas', 'woocommerce' ),
                'KY' => __( 'Kentucky', 'woocommerce' ),
                'LA' => __( 'Louisiana', 'woocommerce' ),
                'ME' => __( 'Maine', 'woocommerce' ),
                'MD' => __( 'Maryland', 'woocommerce' ),
                'MA' => __( 'Massachusetts', 'woocommerce' ),
                'MI' => __( 'Michigan', 'woocommerce' ),
                'MN' => __( 'Minnesota', 'woocommerce' ),
                'MS' => __( 'Mississippi', 'woocommerce' ),
                'MO' => __( 'Missouri', 'woocommerce' ),
                'MT' => __( 'Montana', 'woocommerce' ),
                'NE' => __( 'Nebraska', 'woocommerce' ),
                'NV' => __( 'Nevada', 'woocommerce' ),
                'NH' => __( 'New Hampshire', 'woocommerce' ),
                'NJ' => __( 'New Jersey', 'woocommerce' ),
                'NM' => __( 'New Mexico', 'woocommerce' ),
                'NY' => __( 'New York', 'woocommerce' ),
                'NC' => __( 'North Carolina', 'woocommerce' ),
                'ND' => __( 'North Dakota', 'woocommerce' ),
                'OH' => __( 'Ohio', 'woocommerce' ),
                'OK' => __( 'Oklahoma', 'woocommerce' ),
                'OR'  => __( 'Oregon', 'woocommerce' ),
                'PA' => __( 'Pennsylvania', 'woocommerce' ),
                'RI' => __( 'Rhode Island', 'woocommerce' ),
                'SC' => __( 'South Carolina', 'woocommerce' ),
                'SD' => __( 'South Dakota', 'woocommerce' ),
                'TN' => __( 'Tennessee', 'woocommerce' ),
                'TX' => __( 'Texas', 'woocommerce' ),
                'UT' => __( 'Utah', 'woocommerce' ),
                'VT' => __( 'Vermont', 'woocommerce' ),
                'VA' => __( 'Virginia', 'woocommerce' ),
                'WA' => __( 'Washington', 'woocommerce' ),
                'WV' => __( 'West Virginia', 'woocommerce' ),
                'WI' => __( 'Wisconsin', 'woocommerce' ),
                'WY' => __( 'Wyoming', 'woocommerce' ),
            );

            foreach ($state_arr as $key => $value) { ?>
                <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
            <?php } ?>
        </select><br>

        <label>Will the applicant be Applying for a passport ?*</label><br>
        <label>
            <input type="radio" class="form-check-input" name="applying_passport" value="Yes" required> Yes
        </label>
        <label>
            <input type="radio" class="form-check-input" name="applying_passport" value="No" required> No
        </label><br>

        <div class="applying_passport_no_class">
            <label>Do you have a passport?</label><br>
            <label>
                <input type="radio" class="form-check-input" name="have_passport" value="Yes" required> Yes
            </label>
            <label>
                <input type="radio" class="form-check-input" name="have_passport" value="No" required> No
            </label><br>

            <label>Were you at least 16 when your most recent passport was issued?</label><br>
            <label>
                <input type="radio" class="form-check-input" name="passport_issued_16" value="Yes" required> Yes
            </label>
            <label>
                <input type="radio" class="form-check-input" name="passport_issued_16" value="No" required> No
            </label><br>

            <label>Was your most recent passport issued less than 15 years ago?</label><br>
            <label>
                <input type="radio" class="form-check-input" name="passport_issued_15" value="Yes" required> Yes
            </label>
            <label>
                <input type="radio" class="form-check-input" name="passport_issued_15" value="No" required> No
            </label><br>

            <label>Is your passport has not been damaged or mutilated, lost or stolen?</label><br>
            <label>
                <input type="radio" class="form-check-input" name="passport_damaged" value="Yes" required> Yes
            </label>
            <label>
                <input type="radio" class="form-check-input" name="passport_damaged" value="No" required> No
            </label><br>

            <label>Is your name changed due to marriage or court order?</label><br>
            <label>
                <input type="radio" class="form-check-input" name="name_changed" value="Yes" required> Yes
            </label>
            <label>
                <input type="radio" class="form-check-input" name="name_changed" value="No" required> No
            </label><br>

            <label for="height_feet">Height Feet</label>
            <select name="height_feet" class="form-select" id="height_feet" required>
                <?php
                $height_feet_arr = array(
                    '' => __( 'Select Feet', 'woocommerce' ),
                    '0' => '0',
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                );

                foreach ($height_feet_arr as $key => $value) { ?>
                    <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
                <?php } ?>
            </select><br>

            <label for="height_inches">Height Inches</label>
            <select name="height_inches" class="form-select" id="height_inches" required>
                <?php
                $height_inch_arr = array(
                    '' => __( 'Select Inches', 'woocommerce' ),
                    '0' => '0',
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                    '11' => '11',
                );

                foreach ($height_inch_arr as $key => $value) { ?>
                    <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
                <?php } ?>
            </select><br>

            <label for="hair_color">Hair Color</label>
            <select name="hair_color" class="form-select" id="hair_color" required>
                <?php
                $hair_color_arr = array(
                    '' => __( 'Select Hair Color', 'woocommerce' ),
                    'black' => __( 'Black', 'woocommerce' ),
                    'blonde' => __( 'Blonde', 'woocommerce' ),
                    'brown' => __( 'Brown', 'woocommerce' ),
                    'red' => __( 'Red', 'woocommerce' ),
                    'gray' => __( 'Gray', 'woocommerce' ),
                    'bald' => __( 'Bald', 'woocommerce' ),
                    'other' => __( 'Other', 'woocommerce' ),
                );

                foreach ($hair_color_arr as $key => $value) { ?>
                    <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
                <?php } ?>
            </select><br>

            <label for="eye_color">Eye Color</label>
            <select name="eye_color" class="form-select" id="eye_color" required>
                <?php
                $eye_color_arr = array(
                    '' => __( 'Select Eye Color', 'woocommerce' ),
                    'amber' => __( 'Amber', 'woocommerce' ),
                    'black' => __( 'Black', 'woocommerce' ),
                    'blue' => __( 'Blue', 'woocommerce' ),
                    'brown' => __( 'Brown', 'woocommerce' ),
                    'gray' => __( 'Gray', 'woocommerce' ),
                    'green' => __( 'Green', 'woocommerce' ),
                    'hazel' => __( 'Hazel', 'woocommerce' ),
                );

                foreach ($eye_color_arr as $key => $value) { ?>
                    <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
                <?php } ?>
            </select><br>

            <h2>Permanent Address Details</h2>

            <label>Is the permanent address the same as mailing address?*</label>
            <label>
                <input type="radio" class="form-check-input" name="same_as_mailing" value="Yes" required> Yes
            </label>
            <label>
                <input type="radio" class="form-check-input" name="same_as_mailing" value="No" required> No
            </label><br>

            <label for="street_address">Street Address (House Number and Street Name)</label>
            <textarea name="street_address" class="form-control" id="street_address" required></textarea><br>

            <label for="address_line2">Address Line 2 (APT/Unit)</label>
            <input type="text" class="form-control" name="address_line2" id="address_line2"><br>

            <label for="permanent_city">City / Town</label>
            <input type="text" class="form-control" name="permanent_city" id="permanent_city" required><br>

            <label for="permanent_state">State</label>
            <select name="permanent_state" class="form-select" id="permanent_state" required> 
                <?php
                $state_arr = array(
                    '' => __( 'Select a state', 'woocommerce' ),
                    'AL' => __( 'Alabama', 'woocommerce' ),
                    'AK' => __( 'Alaska', 'woocommerce' ),
                    'AZ' => __( 'Arizona', 'woocommerce' ),
                    'AR' => __( 'Arkansas', 'woocommerce' ),
                    'CA' => __( 'California', 'woocommerce' ),
                    'CO' => __( 'Colorado', 'woocommerce' ),
                    'CT' => __( 'Connecticut', 'woocommerce' ),
                    'DE' => __( 'Delaware', 'woocommerce' ),
                    'DC' => __( 'District of Columbia', 'woocommerce' ),
                    'FL' => __( 'Florida', 'woocommerce' ),
                    'GA' => __( 'Georgia', 'woocommerce' ),
                    'HI' => __( 'Hawaii', 'woocommerce' ),
                    'ID' => __( 'Idaho', 'woocommerce' ),
                    'IL' => __( 'Illinois', 'woocommerce' ),
                    'IN' => __( 'Indiana', 'woocommerce' ),
                    'IA' => __( 'Iowa', 'woocommerce' ),
                    'KS' => __( 'Kansas', 'woocommerce' ),
                    'KY' => __( 'Kentucky', 'woocommerce' ),
                    'LA' => __( 'Louisiana', 'woocommerce' ),
                    'ME' => __( 'Maine', 'woocommerce' ),
                    'MD' => __( 'Maryland', 'woocommerce' ),
                    'MA' => __( 'Massachusetts', 'woocommerce' ),
                    'MI' => __( 'Michigan', 'woocommerce' ),
                    'MN' => __( 'Minnesota', 'woocommerce' ),
                    'MS' => __( 'Mississippi', 'woocommerce' ),
                    'MO' => __( 'Missouri', 'woocommerce' ),
                    'MT' => __( 'Montana', 'woocommerce' ),
                    'NE' => __( 'Nebraska', 'woocommerce' ),
                    'NV' => __( 'Nevada', 'woocommerce' ),
                    'NH' => __( 'New Hampshire', 'woocommerce' ),
                    'NJ' => __( 'New Jersey', 'woocommerce' ),
                    'NM' => __( 'New Mexico', 'woocommerce' ),
                    'NY' => __( 'New York', 'woocommerce' ),
                    'NC' => __( 'North Carolina', 'woocommerce' ),
                    'ND' => __( 'North Dakota', 'woocommerce' ),
                    'OH' => __( 'Ohio', 'woocommerce' ),
                    'OK' => __( 'Oklahoma', 'woocommerce' ),
                    'OR'  => __( 'Oregon', 'woocommerce' ),
                    'PA' => __( 'Pennsylvania', 'woocommerce' ),
                    'RI' => __( 'Rhode Island', 'woocommerce' ),
                    'SC' => __( 'South Carolina', 'woocommerce' ),
                    'SD' => __( 'South Dakota', 'woocommerce' ),
                    'TN' => __( 'Tennessee', 'woocommerce' ),
                    'TX' => __( 'Texas', 'woocommerce' ),
                    'UT' => __( 'Utah', 'woocommerce' ),
                    'VT' => __( 'Vermont', 'woocommerce' ),
                    'VA' => __( 'Virginia', 'woocommerce' ),
                    'WA' => __( 'Washington', 'woocommerce' ),
                    'WV' => __( 'West Virginia', 'woocommerce' ),
                    'WI' => __( 'Wisconsin', 'woocommerce' ),
                    'WY' => __( 'Wyoming', 'woocommerce' ),
                );

                foreach ($state_arr as $key => $value) { ?>
                    <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
                <?php } ?>
            </select><br>

            <label for="postal_code">Postal Code</label>
            <input type="number" class="form-control" name="postal_code" id="postal_code" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==5) return false;" required><br>
        </div>    

        <button type="submit" class="btn btn-primary" id="applicant_submit">Submit</button>
    </form>
    <?php
}
add_action('woocommerce_order_details_after_customer_details', 'namechange_custom_thankyou_form', 11);

// Save applicant form field.
function namechange_save_custom_applicant_form() {
    if(isset($_POST['action'])) {
        if (isset($_POST['order_id']) && $_POST['action'] === "save_custom_applicant_form") {
            $order_id = intval($_POST['order_id']);

            // Save each field as order meta
            update_post_meta($order_id, '_applicant_dob', sanitize_text_field($_POST['applicant_dob']));
            update_post_meta($order_id, '_applicant_sex', sanitize_text_field($_POST['applicant_sex']));
            update_post_meta($order_id, '_city', sanitize_text_field($_POST['city']));
            update_post_meta($order_id, '_state_or_country', sanitize_text_field($_POST['state_or_country']));
            update_post_meta($order_id, '_parent1_name', sanitize_text_field($_POST['parent1_name']));
            update_post_meta($order_id, '_parent2_name', sanitize_text_field($_POST['parent2_name']));
            update_post_meta($order_id, '_change_dl', sanitize_text_field($_POST['change_dl']));
            update_post_meta($order_id, '_dl_state', sanitize_text_field($_POST['dl_state']));
            update_post_meta($order_id, '_applying_passport', sanitize_text_field($_POST['applying_passport']));
            update_post_meta($order_id, '_have_passport', sanitize_text_field($_POST['have_passport']));
            update_post_meta($order_id, '_passport_issued_16', sanitize_text_field($_POST['passport_issued_16']));
            update_post_meta($order_id, '_passport_issued_15', sanitize_text_field($_POST['passport_issued_15']));
            update_post_meta($order_id, '_passport_damaged', sanitize_text_field($_POST['passport_damaged']));
            update_post_meta($order_id, '_name_changed', sanitize_text_field($_POST['name_changed']));
            update_post_meta($order_id, '_height_feet', sanitize_text_field($_POST['height_feet']));
            update_post_meta($order_id, '_height_inches', sanitize_text_field($_POST['height_inches']));
            update_post_meta($order_id, '_hair_color', sanitize_text_field($_POST['hair_color']));
            update_post_meta($order_id, '_eye_color', sanitize_text_field($_POST['eye_color']));
            update_post_meta($order_id, '_same_as_mailing', sanitize_text_field($_POST['same_as_mailing']));
            update_post_meta($order_id, '_street_address', sanitize_text_field($_POST['street_address']));
            update_post_meta($order_id, '_address_line2', sanitize_text_field($_POST['address_line2']));
            update_post_meta($order_id, '_permanent_city', sanitize_text_field($_POST['permanent_city']));
            update_post_meta($order_id, '_permanent_state', sanitize_text_field($_POST['permanent_state']));
            update_post_meta($order_id, '_postal_code', sanitize_text_field($_POST['postal_code']));
            update_post_meta($order_id, 'applicant_details_submitted', true); // detail submitted

            wp_send_json_success(
                array(
                    'message' => __('Applicant form submitted successfully.', 'woocommerce')
                )
            );
        } else {
            wp_send_json_error(
                array(
                    'message' => __('There is an error to submitting the form. Please try again later.', 'woocommerce')
                )
            );
        }
    }
    wp_die();
}
add_action('wp_ajax_save_custom_applicant_form', 'namechange_save_custom_applicant_form');
add_action('wp_ajax_nopriv_save_custom_applicant_form', 'namechange_save_custom_applicant_form');
