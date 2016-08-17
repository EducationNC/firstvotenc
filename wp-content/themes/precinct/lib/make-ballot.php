<?php

namespace Roots\Sage\CMB;

/**
 * Register the form and fields for our front-end submission form
 */
add_action( 'cmb2_init', function() {

  $prefix = '_cmb_';

  $ballot = new_cmb2_box([
    'id'           => 'voter_ballot_form',
    'object_types' => array( 'ballot' ),
    'hookup'       => false,
    'save_fields'  => false,
  ]);

  $ballot->add_field( array(
    'name' => 'Races',
    'type' => 'text',
    'id'   => 'races',
    // Add the name of your function to override the default row render method
    'render_row_cb' => __NAMESPACE__ . '\\make_races_cb'
  ) );

});

/**
 * Gets the front-end-post-form cmb instance
 *
 * @return CMB2 object
 */
function get_voter_ballot_object() {
  $metabox_id = 'voter_ballot_form';
  $object_id = 'fake-oject-id'; // since post ID will not exist yet, just need to pass it something
  return cmb2_get_metabox( $metabox_id, $object_id );
}


/**
 * Manually render a field.
 *
 * @param  array      $field_args Array of field arguments.
 * @param  CMB2_Field $field      The field object
 */
function make_races_cb($field_args, $field) {

  // Election atts and properties
  include( locate_template( '/lib/transient-election.php' ) );
  $election = json_decode(json_encode($result), true);  // Convert result from objects to arrays
  $races = get_post_meta($election_id, '_cmb_included_races', true);
  $referenda = get_post_meta($election_id, '_cmb_included_referenda', true);

  // Create hidden field that includes value of election_id
  if (get_post_type() == 'election') {
    echo '<input type="hidden" name="_cmb_election_id" id="_cmb_election_id" value="' . $election_id . '" />';
  }

  // Manually render each field for races
  foreach ($races as $race) {
    // Find this race in the election data
    $x = array_search($race, array_column($election['contests'], 'office'));

    // Get list of candidates for each race
    $candidates = $election['contests'][$x]['candidates'];
    ?>
    <div class="cmb-row cmb2-id-<?php echo sanitize_title($race); ?>">
      <h3><?php echo $race; ?></h3>
      <?php if (get_post_type() == 'election') { ?>

        <p>(You may vote for X)</p>
        <ul class="cmb2-radio-list cmb2-list">
          <?php
          $i = 0;
          foreach ($candidates as $c) {
            ?>
            <li>
              <label for="<?php echo sanitize_title($race) . $i; ?>">
                <input type="radio" class="cmb2-option" name="_cmb_ballot_<?php echo sanitize_title($race); ?>" id="<?php echo sanitize_title($race) . $i; ?>" value="<?php echo $c['name']; ?>">
                <?php echo $c['name']; ?>
                <br />
                <span class="small"><?php echo $c['party']; ?></span>
              </label>
            </li>
            <?php
            $i++;
          } ?>
        </ul>

      <?php } elseif (get_post_type() == 'ballot') { ?>

        <input type="text" name="_cmb_ballot_<?php echo sanitize_title($race); ?>" value="<?php echo esc_html(get_post_meta(get_the_id(), '_cmb_ballot_' . sanitize_title($race), true)); ?>" disabled="disabled" />

      <?php } ?>
    </div>
    <?php
  }
}

/**
 * Handles form submission on save. Redirects if save is successful, otherwise sets an error message as a cmb property
 *
 * @return void
 */
add_action( 'cmb2_after_init', function() {
  // If no form submission, bail
  if ( empty( $_POST ) || ! isset( $_POST['submit-cmb'], $_POST['object_id'] ) ) {
  	return false;
  }

  // Get CMB2 metabox object
  $ballot = get_voter_ballot_object();

  // Set post_data for saving new post
  $post_data = array(
    'post_author' => 1, // Admin
    'post_status' => 'publish',
    'post_type'   => 'ballot'
  );

  // Check security nonce
  if ( ! isset( $_POST[ $ballot->nonce() ] ) || ! wp_verify_nonce( $_POST[ $ballot->nonce() ], $ballot->nonce() ) ) {
  	return $ballot->prop( 'submission_error', new WP_Error( 'security_fail', __( 'Security check failed.' ) ) );
  }

  // Create the new post
  $new_vote_id = wp_insert_post( $post_data, true );

  // Update title to the ID
  wp_update_post([
    'ID'           => $new_vote_id,
    'post_title'   => $new_vote_id
  ]);

  // If we hit a snag, update the user
  if ( is_wp_error( $new_vote_id ) ) {
  	return $ballot->prop( 'submission_error', $new_vote_id );
  }

  // Loop through post data and save sanitized data to post-meta
  foreach ( $_POST as $key => $value ) {
    if( substr($key, 0, 5) == '_cmb_' ) {
    	if ( is_array( $value ) ) {
    		$value = array_filter( $value );
    		if( ! empty( $value ) ) {
    			update_post_meta( $new_vote_id, $key, esc_html($value) );
    		}
    	} else {
    		update_post_meta( $new_vote_id, $key, esc_html($value) );
    	}
    }
  }

  /*
   * Redirect back to the form page with a query variable with the new post ID.
   * This will help double-submissions with browser refreshes
   */
  wp_redirect( esc_url_raw( add_query_arg( 'post_submitted', $new_vote_id ) ) );
  exit;
} );
