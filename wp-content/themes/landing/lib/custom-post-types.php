<?php

namespace Roots\Sage\CPT;

add_action( 'init', function() {
	register_post_type( 'resource',
		array('labels' => array(
				'name' => 'Resources',
				'singular_name' => 'Resource',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Resource',
				'edit' => 'Edit',
				'edit_item' => 'Edit Resource',
				'new_item' => 'New Resource',
				'view_item' => 'View Resource',
				'search_items' => 'Search Resources',
				'not_found' =>  'Nothing found in the Database.',
				'not_found_in_trash' => 'Nothing found in Trash',
				'parent_item_colon' => ''
			), /* end of arrays */
			'public' => true,
			'exclude_from_search' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'menu_position' => 8,
			//'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png',
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array( 'title', 'editor', 'revisions'),
			'has_archive' => 'resource-center',
			'rewrite' => true,
			'query_var' => true
		)
	);
});

register_taxonomy( 'resource-type',
	array('resource'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
	array('hierarchical' => true,     /* if this is true it acts like categories */
		'labels' => array(
			'name' => __( 'Resource Types' ),
			'singular_name' => __( 'Resource Type' ),
			'search_items' =>  __( 'Search Resource Types' ),
			'all_items' => __( 'All Resource Types' ),
			'parent_item' => __( 'Parent Resource Type' ),
			'parent_item_colon' => __( 'Parent Resource Type:' ),
			'edit_item' => __( 'Edit Resource Type' ),
			'update_item' => __( 'Update Resource Type' ),
			'add_new_item' => __( 'Add New Resource Type' ),
			'new_item_name' => __( 'New Resource Type Name' )
		),
		'show_ui' => true,
		'query_var' => true
	)
);


/**
 * Modify queries on specific templates
 */
add_action('pre_get_posts', function($query) {
	if ($query->is_post_type_archive('resource')) {
		$query->set('posts_per_page', -1);
		$query->set('orderby', 'title');
		$query->set('order', 'ASC');
	}
	if ($query->is_tax('resource-type')) {
		// resource-type should query the resource CPT
		$query->set('post_type', 'resource');
		$query->set('posts_per_page', -1);
		$query->set('orderby', 'title');
		$query->set('order', 'ASC');
	}
});


/**
 * Add columns to admin screen
 */
add_filter( 'manage_resource_posts_columns', function($columns) {
	$new_columns['cb'] = 'cb';
	$new_columns['title'] = 'Title';
	$new_columns['resource-type'] = 'Resource Type';
	$new_columns['date'] = 'Date';

	$columns = $new_columns;
	return $columns;
}, 10, 1);

add_filter( 'manage_resource_posts_custom_column', function($column_name, $id) {
	if ( 'resource-type' == $column_name ) {
		echo get_the_term_list($id, 'resource-type', '', ', ', '');
	}
}, 10, 2);
