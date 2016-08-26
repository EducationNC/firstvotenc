<?php

if ( false === ($master = get_transient('master_election_' . $election_id))) {

  // Get ID of master election
  $master_election = get_post_meta( $election_id, '_cmb_election', true );
  $precinct = get_blog_details();
  $precinct_id = substr($precinct->path, 4, -1);

  switch_to_blog(1);
    // Get ID of election from Civic Info API
    $master['election_id'] = get_post_meta( $master_election, '_cmb_election', true );

    // Get address of this precinct
    $loc = array();
    $loc[] = get_post_meta($precinct_id, '_cmb_address_1', true);
    $loc[] = get_post_meta($precinct_id, '_cmb_address_2', true);
    $loc[] = get_post_meta($precinct_id, '_cmb_city', true);
    $loc[] = get_post_meta($precinct_id, '_cmb_state', true);
    $loc[] = get_post_meta($precinct_id, '_cmb_zip', true);
    $master['address'] = implode(', ', $loc);

  restore_current_blog();

  set_transient('master_election_' . $election_id, $master, 6 * HOUR_IN_SECONDS);

}