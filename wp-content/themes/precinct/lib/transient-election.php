<?php
global $post;

// Determine which election to use
if (get_post_type() == 'election') {
  $election_id = get_the_id();
} elseif (get_post_type() == 'ballot') {
  $election_id = get_post_meta(get_the_id(), '_cmb_election_id', true);
}

// Get precinct data
include(locate_template('/lib/transient-precinct.php'));

// Google API key
locate_template('/lib/google-auth.php', true, true);

// Check for transient first, otherwise get data from Google
if ( false === ($result = get_transient('voterinfo_' . $election_id))) {

  // Use the following when Google Civic Info API has the live data
  if (function_exists('google_api_key')) {
    $api_key = google_api_key();
    $query_string = 'https://www.googleapis.com/civicinfo/v2/voterinfo?address=' . urlencode($master['address']) . '&electionId=' . $master['election_id'] . '&officialOnly=true&returnAllAvailableData=true&key=' . $api_key;
    $api_get = wp_remote_get($query_string);

    if ( ! is_wp_error( $api_get ) ) {
      $result = json_decode($api_get['body']);
    } else {
      echo $api_get->get_error_message();
      $result = false;
    }
  } else {
    echo 'No Google API Key';
    $result = false;
  }

  // Use the following for testing with XML file from CTCL
  // $file = dirname(__FILE__) . '/north_carolina.xml';
  //
  // if (file_exists($file)) {
  //   $xml = simplexml_load_file($file);
  //
  //   $objects = [];
  //
  //   foreach ($xml->children() as $type => $node) {
  //     if ($type == 'CandidateContest' && $node->ElectoralDistrictId == 'ed98729') {
  //       // foreach ($node->children() as $contest) {
  //       // print_r($node);
  //       $objects[] = ['type' => $type, $node];
  //       // }
  //     }
  //   }
  //
  //   print_r($objects);
  //
  // } else {
  //   echo 'Can\'t load file';
  //   $result = false;
  // }

  set_transient('voterinfo_' . $election_id, $result, HOUR_IN_SECONDS);

}

/**
 * Set custom post meta for election date and contests
 */
if (get_post_type() == 'election') {
  update_post_meta($election_id, '_cmb_election_date', $result->election->electionDay);
  update_post_meta($election_id, '_cmb_contests', $result->contests);
}


/**
 * TEMP FUNCTIONS FOR XML FILE
 *
 *
 *
 *
 */
