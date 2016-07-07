<?php

namespace Roots\Sage\CMB;

locate_template('/lib/google-auth.php', true, true);

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

/**
 * Callback function that gets the master elections from the main site as options
 *
 */
function fvnc_ballot_cb($field_args, $field) {

	// Get ID of master election
	$master_election = get_post_meta( get_the_id(), '_cmb_election', true );
	$precinct = get_blog_details();
	$precinct_id = substr($precinct->path, 4, -1);

	switch_to_blog(1);
		// Get ID of election from Civic Info API
		$election_id = get_post_meta( $master_election, '_cmb_election', true );

		// Get address of this precinct
		$loc = array();
		$loc[] = get_post_meta($precinct_id, '_cmb_address_1', true);
		$loc[] = get_post_meta($precinct_id, '_cmb_address_2', true);
		$loc[] = get_post_meta($precinct_id, '_cmb_city', true);
		$loc[] = get_post_meta($precinct_id, '_cmb_state', true);
		$loc[] = get_post_meta($precinct_id, '_cmb_zip', true);
		$address = implode(', ', $loc);

	restore_current_blog();

	if (function_exists('google_api_key')) {
		$api_key = google_api_key();

		$query_string = 'https://www.googleapis.com/civicinfo/v2/voterinfo?address=' . urlencode($address) . '&electionId=' . $election_id . '&key=' . $api_key;

		// Get available elections from Google's Civic Information API
		$api_get = wp_remote_get($query_string);

		if ( ! is_wp_error( $api_get ) ) {
			$result = json_decode($api_get['body']);

			?>
			<div class="cmb-row">
				<div class="cmb-th">
					<label>Election Date</label>
				</div>

				<div class="cmb-td">
					<?php echo $result->election->electionDay; ?>
				</div>
			</div>
			<?php

			foreach ($result->contests as $contest) {
				?>

				<div class="cmb-row">
					<div class="cmb-th">
						<label>Type</label>
					</div>

					<div class="cmb-td">
						<?php echo $contest->type; ?>
					</div>
				</div>

				<div class="cmb-row">
					<div class="cmb-th">
						<label>Office</label>
					</div>

					<div class="cmb-td">
						<?php echo $contest->office; ?>
					</div>
				</div>

				<?php
			}

			echo '<pre>';
			print_r($result->contests);
			echo '</pre>';
			// $term_options = array(
			// 	false => 'Select one'
			// );
	    // if ( ! empty( $result->elections ) ) {
	    //     foreach ( $result->elections as $election ) {
	    //         $term_options[ $election->id ] = $election->name . ': ' . $election->electionDay;
	    //     }
	    // }
			//
	    // return $term_options;
		} else {
			echo $api_get->get_error_message();
		}
	}
}


add_action( 'cmb2_init', function() {
	$prefix = '_cmb_';

	// Elections
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

	// Ballots
	$cmb_ballot_box = new_cmb2_box([
		'id'           => $prefix . 'ballot',
		'title'        => 'Ballot Options',
		'object_types' => array( 'election' ),
		'context'      => 'normal',
		'priority'     => 'high',
	]);

	$cmb_ballot_box->add_field([
		'name' => 'Ballot',
		'id' => $prefix . 'ballot',
		'type' => 'text',
		'render_row_cb' => __NAMESPACE__ . '\\fvnc_ballot_cb',
	]);

});
