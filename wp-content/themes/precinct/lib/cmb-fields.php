<?php

namespace Roots\Sage\CMB;


add_action( 'cmb2_init', function() {
	$prefix = '_cmb_';

	/**
	 * Election options
	 */
	$cmb_election_box = new_cmb2_box([
		'id'           => $prefix . 'election',
		'title'        => 'Election Options',
		'object_types' => array( 'election' ),
		'context'      => 'normal',
		'priority'     => 'high',
	]);

	$cmb_election_box->add_field([
		'name' => 'Election',
		'id' => $prefix . 'election',
		'type' => 'select',
		'options_cb' => __NAMESPACE__ . '\\fvnc_elections_cb',
		'description' => 'Select an election to use for your simulation election.'
	]);

	$cmb_election_box->add_field([
		'name' => 'Election Date',
		'id' => $prefix . 'election_date',
		'type' => 'text_date',
		'attributes' => ['disabled' => 'disabled']
	]);

	$early_vote_default = get_post_meta($prefix . 'election_date', true);

	$cmb_election_box->add_field([
		'name' => 'Early Voting Begins',
		'id' => $prefix . 'early_voting',
		'type' => 'text_date',
		'default' => $early_vote_default	// TODO: THIS IS NOT WORKING
	]);

	$cmb_election_box->add_field([
    'name' => 'Races',
    'desc' => 'Check races to include in this election.',
    'id' => $prefix . 'included_races',
    'type' => 'multicheck',
		'select_all_button' => true,
    'options_cb' => __NAMESPACE__ . '\\races_cb'
	]);

	$cmb_election_box->add_field([
    'name' => 'Referenda',
    'desc' => 'Check referenda to include in this election.',
    'id' => $prefix . 'included_referenda',
    'type' => 'multicheck',
		'select_all_button' => true,
    'options_cb' => __NAMESPACE__ . '\\referenda_cb'
	]);


	/**
	 * Ballot votes
	 */

 	$cmb_ballot_box = new_cmb2_box([
 		'id'           => $prefix . 'ballot',
 		'title'        => 'Ballot',
 		'object_types' => array( 'ballot' ),
 		'context'      => 'normal',
 		'priority'     => 'high',
 	]);

	$cmb_ballot_box->add_field([
		'name' => 'Election',
		'id' => $prefix . 'election_id',
		'type' => 'select',
		'options_cb' => __NAMESPACE__ . '\\ballot_election_cb',
		'attributes' => ['disabled' => 'disabled'],
		'column' => [
			'position' => 2,
			'name' => 'Election'
		]
	]);

  $cmb_ballot_box->add_field([
		'id'   => $prefix . 'races',
    'name' => 'Races',
    'type' => 'text',
    // Add the name of your function to override the default row render method
    'render_row_cb' => __NAMESPACE__ . '\\make_races_cb'
  ]);
});


/**
 * Callback function that gets the master elections from the main site as options
 *
 */
function fvnc_elections_cb($field) {
	// Save original post data in variable
	global $post;
	$original = $post;

	// Switch to main site to query master elections
  switch_to_blog(1);

		$elections = new \WP_Query([
			'post_type' => 'election',
			'posts_per_page' => -1
		]);

		$term_options = [ false => 'Select one' ];

		if ($elections->have_posts()) : while ($elections->have_posts()) : $elections->the_post();
			$term_options[ get_the_id() ] = get_the_title();
		endwhile; endif; wp_reset_postdata();

	restore_current_blog();

	// Reset post data (because wp_reset_postdata() isn't doing the trick)
	$post = $original;

	return $term_options;
}

function ballot_election_cb($field) {
	$elections = get_posts([
		'post_type' => 'election',
		'posts_per_page' => -1
	]);

	$term_options = [ false => 'Cannot cast ballot' ];

	foreach ($elections as $election) {
		$term_options[$election->ID] = $election->post_title;
	}

	return $term_options;
}


		function get_election_info() {
			include( locate_template( '/lib/transient-election.php' ) );
		}
		add_action( 'cmb2_before_post_form__cmb_election', __NAMESPACE__ . '\\get_election_info' );


			/**
			 * Callback function that lists races on the ballot
			 *
			 */
			function races_cb($field) {
				$contests = get_post_meta(get_the_id(), '_cmb_contests', true);

				foreach($contests as $contest) {
					if ($contest->type !== 'Referendum') {
						$options[$contest->office] = $contest->office;
					}
				}

				return $options;
			}

				/**
				 * Callback function that lists referenda on the ballot
				 *
				 */
				function referenda_cb($field) {
					$contests = get_post_meta(get_the_id(), '_cmb_contests', true);

					foreach($contests as $contest) {
						if ($contest->type == 'Referendum') {
							$options[$contest->referendumTitle] = $contest->referendumTitle;
						}
					}

					return $options;
				}
