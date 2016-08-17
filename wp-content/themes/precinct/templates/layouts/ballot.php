<?php

use Roots\Sage\CMB;

$ballot = CMB\get_voter_ballot_object();

$output = "";

// Get any submission errors
if ( ( $error = $ballot->prop( 'submission_error' ) ) && is_wp_error( $error ) ) {
  $output .= '<h3>' . sprintf( 'There was an error in the submission: %s', '<strong>'. $error->get_error_message() .'</strong>' ) . '</h3>';
}

// If the post was submitted successfully, notify the user.
if ( isset( $_GET['post_submitted'] ) && ( $post = get_post( absint( $_GET['post_submitted'] ) ) ) ) {
  $output .= '<h3>Thank you, your vote has been submitted</h3>';
} else {
  // Display metabox on page (changing save button text)
  $output .= cmb2_get_metabox_form( $ballot, 'fake-oject-id', array( 'save_button' => 'Cast Ballot' ) );
}
echo $output;
