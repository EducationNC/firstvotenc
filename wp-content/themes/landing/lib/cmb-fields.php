<?php

namespace Roots\Sage\CMB;

add_action( 'cmb2_init', function() {

	$prefix = '_cdi_';

	$cmb_featured_image = new_cmb2_box( array(
		'id'           => $prefix . 'featured_image_settings',
		'title'        => 'Featured Image Settings',
		'object_types' => array( 'post' ),
		'context'      => 'side',
		'priority'     => 'low',
	) );

	$cmb_featured_image->add_field( array(
		// 'name' => 'Featured Image Alignment',
		'id' => $prefix . 'featured_image_alignment',
		'type' => 'select',
		'options' => array(
			'contained' => 'Default',
			'hero' => 'Full-Width',
			'none' => 'Hidden'
		)
	) );

});
