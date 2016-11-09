<?php
// add_action('wp_ajax_nopriv_co-count', 'do_count' );
// add_action('wp_ajax_do-count', 'do_count');
//
// function do_count() {
//
//   $nonce = $_POST['countNonce'];
//   $paged = $_POST['page'];

  // check to see if the submitted nonce matches with the
  // generated nonce we created earlier
  // if ( ! wp_verify_nonce( $nonce, 'count-ajax-nonce' ) )
  //   die( 'Busted!');

  // Set up statewide election results array
  include(locate_template('/lib/fields-statewide-races.php'));
  $election_contests = array();
  $election_results = array();

  $uploads = network_site_url('wp-content/uploads');

  require_once(ABSPATH . 'wp-admin/includes/file.php');
  $upload_save = get_home_path() . 'wp-content/uploads';
  $blog_id = get_current_blog_id();

  // $paged = 5;

  // $i = $_POST['start'];
  // $batch_size = 100;
  // $max = $i + $batch_size;

  // If this is a recount, delete all local count data and start over
  // Otherwise, append this data to existing statewide data
  // if ($i == 0) {
    // Remove this site's results from global results
    // $saved_er = array(
    //   json_decode(file_get_contents($uploads . '/election_results-104-1.json'), true),
    //   json_decode(file_get_contents($uploads . '/election_results-104-2.json'), true),
    //   json_decode(file_get_contents($uploads . '/election_results-104-3.json'), true),
    //   json_decode(file_get_contents($uploads . '/election_results-104-4.json'), true),
    //   json_decode(file_get_contents($uploads . '/election_results-104-5.json'), true)
    // );
    // $clean_er = json_decode(file_get_contents($uploads . '/election_results_clean.json'), true);
    // echo count($clean_er) . '<br />'
    // foreach ($saved_er as $er) {
    //   echo count($er) . '<br />';
    // }
    // var_dump($clean_er);
    // file_put_contents($uploads['basedir'] . '/election_contests.json', '');
    // file_put_contents($uploads['basedir'] . '/election_results.json', '');
  // }

    //
    // include(locate_template('/lib/fields-exit-poll.php'));
    // include(locate_template('/lib/fields-ballot-init.php'));
    //
    // // Only do this for precincts where the ballot was created AND customized
    // if (is_array($included_races)) {
    //   $precinct_contests = precinct_contests($ballot_data, $included_races, $custom, $issues);
    //   $election_results = precinct_votes($blog_id, $election_id, $statewide_races, $ep_fields, $precinct_contests, $election_results, $paged);
    //
    //   // foreach ($precinct_contests as $pc) {
    //   //   $election_contests = array_merge($election_contests, $pc);
    //   // }
    // }

    // echo '<pre>';
    // var_dump($election_results);
    // echo '</pre>';

    // $new_er = array_merge(array_values($clean_er), array_values($saved_er[0]), array_values($saved_er[1]), array_values($saved_er[2]), array_values($saved_er[3]), array_values($saved_er[4]));
    // file_put_contents(
    //   $upload_save . '/election_results_full.json',
    //   json_encode($new_er)
    // );

  // Iterate through all sites in batches
  /*if(is_multisite()){
    global $wpdb;
    $blogs = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->blogs WHERE spam = '%d' AND deleted = '%d' and archived = '%d' and public='%d'", 0, 0, 0, 0));
    if(!empty($blogs)){

      // How many blogs do we need to go through?
      $totalItems = count($blogs);
      if ($max > $totalItems) {
        $max = $totalItems;
      }

      // Iterate in batches to prevent timeout
      for (; $i < $max; $i++) {
        $blog = $blogs[$i];
        switch_to_blog($blog->blog_id);
        $details = get_blog_details($blog->blog_id);
        $q = new WP_Query([
          'posts_per_page' => -1,
          'post_type' => 'election'
        ]);
        if ($q->have_posts()) : while ($q->have_posts()) : $q->the_post();

          include(locate_template('/lib/fields-exit-poll.php'));
          include(locate_template('/lib/fields-ballot-init.php'));

          // Only do this for precincts where the ballot was created AND customized
          if (is_array($included_races)) {

            $precinct_contests = precinct_contests($ballot_data, $included_races, $custom, $issues);
            $election_results = precinct_votes($blog->blog_id, $election_id, $statewide_races, $ep_fields, $precinct_contests, $election_results);

            foreach ($precinct_contests as $pc) {
              $election_contests = array_merge($election_contests, $pc);
            }

          }

        endwhile; endif; wp_reset_postdata();
        restore_current_blog();

      }
    }
  }

  // Update all contests and all results
  $saved_ec = json_decode(file_get_contents($uploads['basedir'] . '/election_contests.json'), true);
  if (is_array($saved_ec)) {
    $new_ec = array_merge($saved_ec, $election_contests);
  } else {
    $new_ec = $election_contests;
  }
  file_put_contents(
    $uploads['basedir'] . '/election_contests.json',
    json_encode($new_ec)
  );

  $saved_er = json_decode(file_get_contents($uploads['basedir'] . '/election_results.json'), true);
  if (is_array($saved_er)) {
    $new_er = array_merge(array_values($saved_er), array_values($election_results));
  } else {
    $new_er = $election_results;
  }
  file_put_contents(
    $uploads['basedir'] . '/election_results.json',
    json_encode($new_er)
  );

  // Output
  header('Content-Type: application/json');
  echo json_encode([
    'total' => $totalItems,
    'start' => $i,
    'ec' => count($new_ec),
    'er' => count($new_er)
  ]);

  exit;
}*/

// Function to count votes
// function count_votes($election_id, $contest_title, $option) {
//   $cast_ballots = new WP_Query([
//     'post_type' => 'ballot',
//     'posts_per_page' => -1,
//     'meta_query' => [
//       [
//         'key' => $contest_title,
//         'value' => $option
//       ],
//       [
//         'key' => '_cmb_election_id',
//         'value' => $election_id
//       ]
//     ],
//     'fields' => 'ids'
//   ]);
//
//   return $cast_ballots->posts;
// }

// Count how many pollees responded a certain way
// function count_pollees($election_id, $ep_question, $option, $ballot_id = NULL) {
//   $ballot_match = array();
//
//   if (!empty($ballot_id)) {
//     $ballot_match = [
//       'key' => '_cmb_ballot_id',
//       'value' => $ballot_id,
//       'compare' => 'IN'
//     ];
//   }
//
//   $exit_polls = new WP_Query([
//     'post_type' => 'exit-poll',
//     'posts_per_page' => -1,
//     'meta_query' => [
//       [
//         [
//           'key' => $ep_question,
//           'value' => $option
//         ],
//         [
//           'key' => '_cmb_election_id',
//           'value' => $election_id
//         ]
//       ],
//       $ballot_match
//     ],
//     'fields' => 'ids'
//   ]);
//
//   return $exit_polls->posts;
// }


/**
 * Create array of all contests on ballot
 * Loop through contests, custom contets, issue-based questions
 *
 */
function precinct_contests($ballot_data, $included_races, $custom, $issues) {
  $precinct_contests = array();

  // Loop through contests
  foreach ($ballot_data as $ballot_section) {
    foreach ($ballot_section->races as $key => $race) {

      $key = array_search($race->ballot_title, $included_races);
      if ($key !== FALSE && $race->votes_allowed > 0) {
        if (!empty($race->seat)) {
          $sanitized_title = sanitize_title($race->ballot_title . '-' . $race->seat);
        } else {
          $sanitized_title = sanitize_title($race->ballot_title);
        }

        $precinct_contests[$ballot_section->section]['_cmb_ballot_' . $sanitized_title] = [
          'title' => $race->ballot_title,
          'district' => $race->district,
          'sanitized_title' => '_cmb_ballot_' . $sanitized_title,
          'number' => $race->votes_allowed
        ];

        foreach ($race->candidates as $can) {
          if ($ballot_section->section == 'Partisan Offices') {
            $details = [
              'name' => str_replace(['<br />', '(', ')', ', Jr'], [' & ', '"', '"', ' Jr'], $can->ballotName),
              'party' => str_replace([' Party', 'Democratic'], ['', 'Democrat'], $can->party)
            ];
          } else {
            $details = [
              'name' => str_replace(['<br />', '(', ')', ', Jr'], [' & ', '"', '"', ' Jr'], $can->ballotName)
            ];
          }

          $precinct_contests[$ballot_section->section]['_cmb_ballot_' . $sanitized_title]['candidates'][] = $details;
        }
      }
    }
  }

  // Loop through custom contests
  foreach ($custom as $contest) {
    if (!empty($contest['title'])) {
      $sanitized_title = '_cmb_ballot_' . sanitize_title($contest['title']);
      $precinct_contests[$contest['section']][$sanitized_title] = [
        'title' => $contest['title'],
        'sanitized_title' => $sanitized_title,
        'number' => $contest['votes_allowed']
      ];

      $candidates = explode("\n", str_replace("\r", "", $contest['candidates']));
      foreach ($candidates as $c_key => $candidate) {
        // Get party
        preg_match('/\(([A-Za-z0-9 ]+?)\)/', $candidate, $party);

        if (!empty($party[0])) {
          $precinct_contests[$contest['section']][$sanitized_title]['candidates'][$c_key]['party'] = $party[1];

          $candidate = str_replace($party[0], '', $candidate);
        }

        $precinct_contests[$contest['section']][$sanitized_title]['candidates'][$c_key]['name'] = $candidate;
      }
    }
  }

  // Loop through issue-based questions
  $k = 0;
  foreach ($issues as $question) {
    if (!empty($question)) {
      $sanitized_title = '_cmb_ballot_' . sanitize_title($question['title']) . '-' . $k;
      $precinct_contests['Issues'][$sanitized_title] = [
        'title' => $question['title'],
        'sanitized_title' => $sanitized_title,
        'question' => $question['question']
      ];

      if (empty($question['options'])) {
        $precinct_contests['Issues'][$sanitized_title]['options'] = ['Yes', 'No'];
      } else {
        $precinct_contests['Issues'][$sanitized_title]['options'] = $question['options'];
      }
      $k++;
    }
  }

  // update_option('precinct_contests', json_encode($precinct_contests));

  return $precinct_contests;
}


/**
 * Create array of all the votes, plus exit poll answers
 *
 */
function precinct_votes($blog_id, $election_id, $statewide_races, $ep_fields, $precinct_contests, $election_results, $paged = 1) {
  // Headers for all contests
  foreach ($precinct_contests as $s_key => $section) {
    foreach ($section as $contest) {
      $columns_contests[] = $contest['sanitized_title'];
    }
  }

  // Headers for exit polls + participation numbers by exit poll
  foreach ($ep_fields as $ep_field) {
    $columns_eps[] = $ep_field['id'];

    // foreach ($ep_field['options'] as $ep_key => $ep_option) {
    //   // Participation by exit poll responses
    //   $pollees = count_pollees($election_id, $ep_field['id'], $ep_key);
    //   $participation[$ep_field['id']][$ep_key] = sizeof($pollees);
    // }
  }

  // Create final column headers
  // $columns = array_merge(['blog_id'], $columns_contests, $columns_eps);
  // $precinct_votes[] = $columns;

  // Make rows for each vote
  $ballots = new WP_Query([
    'post_type' => 'ballot',
    'posts_per_page' => 1000,
    'paged' => $paged
  ]);

  if ($ballots->have_posts()) : while ($ballots->have_posts()) : $ballots->the_post();
    $ballot_id = get_the_id();

    // Get ballot results
    $ballot_responses = get_post_custom();
    $row_votes = array('blog_id' => $blog_id);
    foreach ($columns_contests as $contest) {
      if (isset($ballot_responses[$contest])) {
        $row_votes[$contest] = str_replace(['&lt;br /&gt;', '(', ')', ', Jr'], [' & ', '"', '"', ' Jr'], $ballot_responses[$contest][0]);
      } else {
        $row_votes[$contest] = NULL;
      }
    }

    // Add to statewide results array
    $row_votes_state = array('blog_id' => $blog_id);
    foreach ($statewide_races as $contest) {
      if (isset($ballot_responses[$contest])) {
        $row_votes_state[$contest] = str_replace(['&lt;br /&gt;', '(', ')', ', Jr'], [' & ', '"', '"', ' Jr'], $ballot_responses[$contest][0]);
      } else {
        $row_votes_state[$contest] = NULL;
      }
    }

    // // Get exit poll result for this voter
    $exit_poll = new WP_Query([
      'post_type' => 'exit-poll',
      'posts_per_page' => 1,
      'meta_query' => [
        [
          'key' => '_cmb_ballot_id',
          'value' => $ballot_id
        ]
      ],
      'fields' => 'ids'
    ]);

    $pollee = $exit_poll->posts;

    if (isset($pollee[0])) {
      $ep_responses = get_post_custom($pollee[0]);

      foreach ($columns_eps as $ep) {
        $row_votes[$ep] = $ep_responses[$ep][0];
        $row_votes_state[$ep] = $ep_responses[$ep][0];
      }
    }

    $precinct_votes[] = $row_votes;
    $election_results[] = $row_votes_state;
  endwhile; endif; wp_reset_postdata();

  // update_option('precinct_votes', json_encode($precinct_votes));

  return $election_results;
}
