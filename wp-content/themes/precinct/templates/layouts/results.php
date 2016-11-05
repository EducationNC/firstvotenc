<script src="http://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript" src="http://code.highcharts.com/modules/data.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<?php

include(locate_template('/lib/fields-exit-poll.php'));
include(locate_template('/lib/fields-ballot-init.php'));


// Function to count votes
function count_votes($election_id, $contest_title, $option) {
  $cast_ballots = new WP_Query([
    'post_type' => 'ballot',
    'posts_per_page' => -1,
    'meta_query' => [
      [
        'key' => $contest_title,
        'value' => $option
      ],
      [
        'key' => '_cmb_election_id',
        'value' => $election_id
      ]
    ],
    'fields' => 'ids'
  ]);

  return $cast_ballots->posts;
}

// Count how many pollees responded a certain way
function count_pollees($election_id, $ep_question, $option, $ballot_id = NULL) {
  $ballot_match = array();

  if (!empty($ballot_id)) {
    $ballot_match = [
      'key' => '_cmb_ballot_id',
      'value' => $ballot_id,
      'compare' => 'IN'
    ];
  }

  $exit_polls = new WP_Query([
    'post_type' => 'exit-poll',
    'posts_per_page' => -1,
    'meta_query' => [
      [
        [
          'key' => $ep_question,
          'value' => $option
        ],
        [
          'key' => '_cmb_election_id',
          'value' => $election_id
        ]
      ],
      $ballot_match
    ],
    'fields' => 'ids'
  ]);

  return $exit_polls->posts;
}



/**
 * Don't do this for precincts where the ballot was created but never customized
 *
 */
if (!is_array($included_races))
  return false;


/**
 * Create array of all contests on ballot
 * Loop through contests, custom contets, issue-based questions
 *
 */
if (false === ($all_contests = get_transient('all_contests'))) {
  $all_contests = array();

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

        $all_contests[$ballot_section->section][$key] = [
          'title' => $race->ballot_title,
          'sanitized_title' => '_cmb_ballot_' . $sanitized_title
        ];

        foreach ($race->candidates as $can) {
          $all_contests[$ballot_section->section][$key]['candidates'][] = [
            'name' => str_replace(['<', '>'], ['&lt;', '&gt;'], $can->ballotName),
            'party' => $can->party
          ];
        }
      }
    }
  }

  // Loop through custom contests
  foreach ($custom as $contest) {
    if (!empty($contest['title'])) {
      $all_contests[$contest['section']][] = [
        'title' => $contest['title'],
        'sanitized_title' => '_cmb_ballot_' . sanitize_title($contest['title'])
      ];
    }
  }

  // Loop through issue-based questions
  $k = 0;
  foreach ($issues as $question) {
    if (!empty($question)) {
      $all_contests['Issues'][$k] = [
        'title' => $question['title'],
        'sanitized_title' => '_cmb_ballot_' . sanitize_title($question['title']) . '-' . $k,
        'question' => $question['question']
      ];

      if (empty($question['options'])) {
        $all_contests['Issues'][$k]['options'] = ['Yes', 'No'];
      } else {
        $all_contests['Issues'][$k]['options'] = $question['options'];
      }
      $k++;
    }
  }

  set_transient('all_contests', $all_contests, 1 * HOUR_IN_SECONDS);
}


/**
 * Create array of all the votes, plus exit poll answers
 *
 */
if (false === ($votes = get_transient('save_votes'))) {
  $ballots = new WP_Query([
    'post_type' => 'ballot',
    'posts_per_page' => -1
  ]);

  if ($ballots->have_posts()) : while ($ballots->have_posts()) : $ballots->the_post();
    $ballot_id = get_the_id();
    $ballot_responses = get_post_custom();

    unset($ballot_responses['_edit_lock']);

    $pollees = count_pollees($election_id, '_cmb_ballot_id', $ballot_id);

    foreach ($pollees as $pollee) {
      foreach ($ep_fields as $field) {
        $exit_poll_answers[$field['id']] = get_post_meta($pollee, $field['id'], true);
      }
    }

    $votes[$ballot_id] = array_merge($ballot_responses, $exit_poll_answers);

  endwhile; endif; wp_reset_postdata();

  set_transient('save_votes', $votes, 1 * HOUR_IN_SECONDS);
}


/**
 * Tabulate results in different ways:
 *
 * Results for each contest
 * Results broken down by exit poll response
 * Participation by exit poll response
 *
 */
// delete_transient('save_all_results');
if (false === ($save_all_results = get_transient('save_all_results'))) {

  $results = array();
  $tabulated_results = array();
  $participation = array();

  foreach ($ep_fields as $ep_field) {
    // CSV column headers for exit poll
    $columns_eps[] = $ep_field['name'];

    foreach ($ep_field['options'] as $ep_key => $ep_option) {
      // Participation by exit poll responses
      $pollees = count_pollees($election_id, $ep_field['id'], $ep_key);
      $participation[$ep_field['name']][$ep_key] = sizeof($pollees);
    }
  }

  // Separate by section
  foreach ($all_contests as $s_key => $section) {

    $columns_contests = array();
    foreach ($section as $contest) {
      // CSV column headers for contests
      $columns_contests[] = $contest['title'];

      // Tabulate results for each contest
      if (!empty($contest['candidates'])) {
        // Include title in tabulated results array
        $tabulated_results[$s_key][$contest['sanitized_title']]['title'] = $contest['title'];

        foreach ($contest['candidates'] as $c_key => $candidate) {
          $ballots = count_votes($election_id, $contest['sanitized_title'], $candidate);

          // Results without exit poll breakdown
          $tabulated_results[$s_key][$contest['sanitized_title']]['results'][$c_key] = $candidate;
          $tabulated_results[$s_key][$contest['sanitized_title']]['results'][$c_key]['votes'] = sizeof($ballots);

          // Results broken down by exit poll
          foreach ($ep_fields as $ep_field) {
            foreach ($ep_field['options'] as $ep_key => $ep_option) {
              $pollees = count_pollees($election_id, $ep_field['id'], $ep_key, $ballots);

              // Under each candidate
              $tabulated_results[$s_key][$contest['sanitized_title']]['results'][$c_key]['exit_polls'][$ep_field['id']]['title'] = $ep_field['name'];
              $tabulated_results[$s_key][$contest['sanitized_title']]['results'][$c_key]['exit_polls'][$ep_field['id']]['results'][$ep_key] = sizeof($pollees);

              // Candidates under each exit poll
              $tabulated_results[$s_key][$contest['sanitized_title']]['exit_polls'][$ep_field['id']]['title'] = $ep_field['name'];
              $tabulated_results[$s_key][$contest['sanitized_title']]['exit_polls'][$ep_field['id']]['results'][$c_key]['name'] = $candidate['name'];
              $tabulated_results[$s_key][$contest['sanitized_title']]['exit_polls'][$ep_field['id']]['results'][$c_key]['party'] = $candidate['party'];
              $tabulated_results[$s_key][$contest['sanitized_title']]['exit_polls'][$ep_field['id']]['results'][$c_key]['votes'][$ep_key] = sizeof($pollees);
            }
          }
        }
      }

      // Tabulate issue-based question results
      if (!empty($contest['question'])) {
        // Include question and title in tabulated results array
        $tabulated_results[$s_key][$contest['sanitized_title']]['question'] = $contest['question'];
        $tabulated_results[$s_key][$contest['sanitized_title']]['title'] = $contest['title'];

        // Tabulate results for each issue question
        foreach ($contest['options'] as $o_key => $option) {
          $ballots = count_votes($election_id, $contest['sanitized_title'], $option);

          // Results without exit poll breakdown
          $tabulated_results[$s_key][$contest['sanitized_title']]['results'][$o_key]['name'] = $option;
          $tabulated_results[$s_key][$contest['sanitized_title']]['results'][$o_key]['votes'] = sizeof($ballots);

          // Results broken down by exit poll
          foreach ($ep_fields as $ep_field) {
            foreach ($ep_field['options'] as $ep_key => $ep_option) {
              $pollees = count_pollees($election_id, $ep_field['id'], $ep_key, $ballots);
              $tabulated_results[$s_key][$contest['sanitized_title']]['results'][$o_key]['exit_polls'][$ep_field['id']]['title'] = $ep_field['name'];
              $tabulated_results[$s_key][$contest['sanitized_title']]['results'][$o_key]['exit_polls'][$ep_field['id']]['results'][$ep_key] = sizeof($pollees);
            }
          }
        }
      }
    }


    // Convert votes array to results array so each row is in consistent column order
    $results[$s_key]['columns'] = array_merge($columns_contests, $columns_eps);
    // foreach ($votes as $v_key => $vote) {
    //   $results[$s_key]['results'][$v_key] = array();
    //
    //   // Add vote results to array
    //   foreach ($section as $contest) {
    //     if (is_array($vote[$contest['sanitized_title']])) {
    //       $results[$s_key]['results'][$v_key][] = implode(', ', $vote[$contest['sanitized_title']]);
    //     }
    //   }
    //
    //   // Add exit poll answers to results array
    //   foreach ($ep_fields as $ep_field) {
    //     $results[$s_key]['results'][$v_key][] = $vote[$ep_field['id']];
    //   }
    // }
  }

  $save_all_results = [
    'results' => $results,
    'tabulated' => $tabulated_results,
    'participation' => $participation
  ];

  set_transient('save_all_results', $save_all_results, 1 * HOUR_IN_SECONDS);
}



/**
 * Loop through each tabulated result and create Highchart visualization
 *
 */
?>
<p><a href="<?php echo add_query_arg('results', 'all', get_the_permalink()); ?>">General Election Results</a></p>
<h3>Explore results by contest</h3>
<ul>
  <?php
  $page_title = '';
  foreach ($all_contests as $section) {
    foreach ($section as $contest) {
      echo '<li><a href="' . add_query_arg('results', $contest['sanitized_title'], get_the_permalink()) . '">' . $contest['title'] . '</a></li>';
      if ($_GET['results'] == $contest['sanitized_title']) {
        $page_title = 'Contest Results: ' . $contest['title'];
      }
    }
  }
  ?>
</ul>
<h3>Explore results by exit poll</h3>
<ul>
  <?php
  foreach ($ep_fields as $ep_field) {
    echo '<li><a href="' . add_query_arg('results', $ep_field['id'], get_the_permalink()) . '">' . $ep_field['name'] . '</a></li>';
    if ($_GET['results'] == $ep_field['id']) {
      $page_title = 'Exit Poll Results: ' . $ep_field['name'];
    }
  }
  if (empty($page_title)) {
    $page_title = 'Precinct Results';
  }
  ?>
</ul>

<?php
echo '<h2>' . $page_title . '</h2>';

if (stristr($_GET['results'], '_cmb_ballot_') === false) {
  foreach ($save_all_results['tabulated'] as $s_key => $section) {
    echo '<h3>' . $s_key . '</h3>';

    foreach ($section as $race => $results) {
      echo '<div id="' . $race . '" style="width:600px; height:400px;"></div>';
      ?>

      <script type="text/javascript">
        var options = {
          chart: {
            renderTo: '<?php echo $race; ?>',
            defaultSeriesType: 'bar'
          },
          title: {
            text: "<?php if (!empty($results['question'])) { echo $results['question']; } else { echo $results['title']; } ?>"
          },
          xAxis: {
            categories: [
              <?php
              // Exit poll results
              if ($_GET['results'] !== 'all' && !empty($_GET['results'])) {
                $categories = array_keys($results['results'][0]['exit_polls'][$_GET['results']]['results']);
                echo '"' . implode('", "', $categories) . '"';
              // General election results
              } else {
                foreach ($results['results'] as $result) {
                  $xAxis[] = $result['name'];
                }
                // echo '"Votes"';
                echo '"' . implode('", "', $xAxis) . '"';
              }
              ?>
            ]
          },
          yAxis: {
            title: {
              text: 'Votes'
            }
          },
          // legend: {
          //     reversed: true
          // },
          // plotOptions: {
          //     series: {
          //         stacking: 'percent'
          //     }
          // },
          series: [
            <?php
            // $data = array_reverse($results['results']);
            $data = $results['results'];
            foreach ($data as $result) { ?>
              {
                name: '<?php echo $result['name']; ?>',
                data: [
                  <?php
                  if ($_GET['results'] !== 'all' && !empty($_GET['results'])) {
                    $numbers = $result['exit_polls'][$_GET['results']]['results'];
                    echo implode(', ', $numbers);
                  } else {
                    echo $result['votes'];
                  } ?>
                ],
                className: '<?php echo sanitize_title($result['party']); ?>',
                animation: false
              },
            <?php } ?>
          ]
        };

        var chart = new Highcharts.Chart(options);
      </script>
      <?php
    }
  }
} else {
  foreach ($save_all_results['tabulated'] as $s_key => $section) {
    foreach ($section as $race => $data) {
      if ($_GET['results'] == $race) {
        // foreach ($data['exit_polls'] as $ep_key => $exit_poll) {
        foreach ($ep_fields as $ep_field) {
          /*echo '<div class="data-table" id="' . $ep_field['id'] . '"></div>';
          ?>
          <script type="text/javascript">
            google.charts.load('current', {'packages':['table']});
            google.charts.setOnLoadCallback(drawTable);

            function drawTable() {
              var data = new google.visualization.arrayToDataTable([
                <?php*/
          echo '<table class="table table-responsive table-results">';
                $i = 1;
                $labels = array();
                $rows = array();

                $labels[] = $ep_field['name'];
                foreach ($ep_field['options'] as $ep_key => $ep_option) {
                  foreach ($data['results'] as $results) {
                    if ($i == 1) {
                      $labels[] = str_replace('&lt;br /&gt;', '<br />', $results['name']);
                    }
                    $rows[$ep_key][] = $results['exit_polls'][$ep_field['id']]['results'][$ep_key];
                  }
                  $i++;
                }

                echo '<thead><tr><th class="col-xs-' . 12/sizeof($labels) . '">' . implode('</th><th>', $labels) . '</th></tr></thead>';
                foreach ($rows as $ep => $row) {
                  echo '<tr><td>' . $ep . '</td><td>' . implode('</td><td>', $row) . '</td></tr>';
                }
              /*  ?>
              ], false);

              var table = new google.visualization.Table(document.getElementById('<?php echo $ep_field['id']; ?>'));

              table.draw(data);
            }
          </script>
          <?php
          /*
          echo '<div id="' . $ep_key . '" style="width:600px; height:400px;"></div>';
          ?>
          <script type="text/javascript">
            var options = {
              chart: {
                renderTo: '<?php echo $ep_key; ?>',
                defaultSeriesType: 'heatmap'
              },
              title: {
                text: "<?php echo $exit_poll['title']; ?>"
              },
              xAxis: {
                categories: [
                  <?php
                  $xAxis = array_keys($exit_poll['results'][0]['votes']);
                  echo '"' . implode('", "', $xAxis) . '"';
                  ?>
                ]
              },
              yAxis: {
                // title: {
                //   text: 'Votes'
                // }
                categories: [
                  <?php
                  foreach ($exit_poll['results'] as $candidate) {
                    $yAxis[] = $candidate['name'];
                  }
                  echo '"' . implode('", "', $yAxis) . '"';
                  ?>
                ]
              },
              legend: {
                  reversed: true
              },
              // plotOptions: {
              //     series: {
              //         stacking: 'normal'
              //     }
              // },
              series: [
                {
                  name: 'Votes',
                  data: [
                    <?php
                    $candidates = array_reverse($exit_poll['results']);
                    foreach ($candidates as $candidate) {
                      $numbers = $candidate['votes'];
                      $dataset[] = '[' . implode(', ', $numbers) . ']';
                    }
                    echo implode(', ', $dataset);
                    ?>
                  ],
                  // className: '<?php echo sanitize_title($candidate['party']); ?>',
                  animation: false
                }
              ]
            };

            var chart = new Highcharts.Chart(options);
          </script>
          <?php*/
        }
      }
    }
  }
}

echo '<pre>';
// print_r($votes);
// print_r($results);
print_r($save_all_results['tabulated']);
// print_r($participation);
echo '</pre>';
