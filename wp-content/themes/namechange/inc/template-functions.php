<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package namechange
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function namechange_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'namechange_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function namechange_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'namechange_pingback_header' );

/**
 * Button Group For Clone
 */
function button_group($field_name) {
    if (!empty($field_name) && is_array($field_name)) {
        $button_link = '';
        $button_link_type = $field_name['button_link'];
        $internal_link = $field_name['button_internal_link'];
        $external_link = $field_name['button_external_link'];
        if (($button_link_type == 'button_internal_link') && !empty($internal_link)) {
            $button_link = namechange_external_link($internal_link, false);
        } elseif (($button_link_type == 'button_external_link') && !empty($external_link)) {
            $button_link = namechange_external_link($external_link, true);
        }
        if (!empty($button_link)) {
            return $button_link;
        } else {
            return '';
        }
    } else {
        return;
    }
}

function namechange_external_link($link = null, $target = null) {
    if (empty($link)) {
        return;
    }
    $href_link = null;
    if (!empty($link) && $link != null) {
        if ($link == '#') {
            $href_link = $link;
            $target = '';
        } else {
            $url = trim($link);
            if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
                $href_link = "http://" . $url;
            } else {
                $href_link = trim($link);
            }
        }
    }
    if ($target == true) {
        return 'href="' . $href_link . '" target="_blank"';
    } else {
        return 'href="' . $href_link . '"';
    }
}

/**
 * Set acf option page
 */
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(
        array(
            'page_title' => 'Theme General Settings',
            'menu_title' => 'Theme Settings',
            'menu_slug' => 'theme-general-settings',
            'capability' => 'edit_posts',
            'redirect' => false
        )
    );
}

/**
 * Allowed mime types
 */
function namechange_custom_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    $mimes['webp'] = 'image/webp';
    return $mimes;
}
add_filter('upload_mimes', 'namechange_custom_mime_types');

/**
 * Custom name validation in cf7
 */
function namechange_custom_name_validation_filter($result, $tag) {
    if ("your_name" == $tag->name) {
        $name = isset($_POST[$tag->name]) ? $_POST[$tag->name] : '';
        if ($name != "" && !preg_match("/^[a-zA-Z ]*$/", $name)) {
            $result->invalidate($tag, "Please Enter Your valid name.");
        }
    }
    return $result;
}
add_filter('wpcf7_validate_text', 'namechange_custom_name_validation_filter', 20, 2);
add_filter('wpcf7_validate_text*', 'namechange_custom_name_validation_filter', 20, 2);
