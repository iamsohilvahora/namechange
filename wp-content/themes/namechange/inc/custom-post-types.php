<?php
// Testimonial CPT
function namechange_custom_testimonial_func() {
	$labels = array(
		'name'               => _x( 'Testimonial', 'post type general name' ),
		'singular_name'      => _x( 'Testimonial', 'post type singular name' ),
		'menu_name'          => _x( 'Testimonial', 'admin menu'),
		'name_admin_bar'     => _x( 'Testimonial', 'add new on admin bar' ),
		'add_new'            => _x( 'Add New Testimonial', 'testimonial' ),
		'add_new_item'       => __( 'Add New Testimonial'),
		'new_item'           => __( 'New Testimonial'),
		'edit_item'          => __( 'Edit Testimonial'),
		'view_item'          => __( 'View Testimonial'),
		'all_items'          => __( 'All Testimonial'),
		'search_items'       => __( 'Search Testimonial'),
		'parent_item_colon'  => __( 'Parent Testimonial:'),
		'not_found'          => __( 'No Testimonial found.'),
		'not_found_in_trash' => __( 'No Testimonial found in Trash.' )
	);
	$args = array(
		'labels'            	=> $labels,
	 	'public' 				=> false,
        'has_archive' 			=> false,
        'show_ui' 				=> true,
        'query_var' 			=> true,
        'show_in_rest' 			=> false,
        'supports' 				=> array( 'title', 'editor', 'thumbnail' ),
        'show_admin_column' 	=> true,
        'exclude_from_search' 	=> true,
        'show_in_nav_menus'     => true,
        'show_in_admin_bar'     => true,
        'show_in_menu'          => true,
        'can_export' 			=> true,
        'publicly_queryable'    => false,
        'hierarchical' 			=> false,
        'capability_type' 		=> 'post',
        'menu_position' 		=> 10,
        'menu_icon' 			=> 'dashicons-testimonial',
        'rewrite' 				=> array( 'slug' => 'testimonial', 'with_front' => true, 'pages' => true, 'feeds' => false ),
	);
	register_post_type( 'testimonial', $args );
}
add_action( 'init', 'namechange_custom_testimonial_func' );
