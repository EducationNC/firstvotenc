<?php

use Roots\Sage\Assets;

?>

<?php
get_template_part('templates/components/header');

if (isset($_GET['add'])) {
  get_template_part('/templates/layouts/add_election');

  return false;
}
?>

<section class="precinct-admin">
  <div class="container">
    <div class="row extra-bottom-margin">
      <div class="col-md-6">

        <?php
        $election = new WP_Query([
          'post_type' => 'election',
          'posts_per_page' => -1
        ]);
        ?>

        <table class="table table-hover table-condensed">
          <thead>
            <tr>
              <th scope="col" class="h3">Simulation Elections</th>
              <th scope="col">Dates</th>
            </tr>
          </thead>

          <tbody>

          <?php if ($election->have_posts()) : while ($election->have_posts()): $election->the_post();
            if ( current_user_can( 'editor' ) ) { ?>

              <tr>
                <th scope="row">
                  <a href="<?php the_permalink(); ?>?edit"><?php the_title(); ?></a><br />
                  <span class="small"><a href="<?php the_permalink(); ?>?edit">Edit</a> | <a href="<?php the_permalink(); ?>">Preview Ballot</a></span>
                </th>
                <td>
                  <?php echo date('m/d/Y', strtotime(get_post_meta(get_the_id(), '_cmb_early_voting', true))); ?> -
                  <?php echo date('m/d/Y', strtotime(get_post_meta(get_the_id(), '_cmb_voting_day', true))); ?>
                </td>
              </tr>

            <?php } else { ?>

              <tr>
                <th scope="row">
                  <?php the_title(); ?><br />
                  <span class="small"><a href="<?php the_permalink(); ?>">Preview Ballot</a></span>
                </th>
                <td>
                  <?php echo date('m/d/Y', strtotime(get_post_meta(get_the_id(), '_cmb_early_voting', true))); ?> -
                  <?php echo date('m/d/Y', strtotime(get_post_meta(get_the_id(), '_cmb_voting_day', true))); ?>
                </td>
              </tr>

            <?php }
          endwhile; else: ?>

            <tr>
              <td colspan="2">
                <div class="well well-sm">
                  <p><em>No simulation elections have been created for your precinct yet.</em></p>
                </div>
              </td>
            </tr>

          <?php endif; wp_reset_postdata(); ?>
          </tbody>
        </table>

        <?php if ( current_user_can( 'editor' ) ) { ?>
          <a class="btn btn-default" href="?add">Add Simulation Election</a>
        <?php } ?>

      </div>

      <div class="col-md-6">

        <table class="table table-condensed">
          <thead>
            <tr>
              <th scope="col" class="h3">Precinct Districts</th>
            </tr>
          </thead>

          <tbody>
            <tr>
              <td>
                <?php

                if ( false === ($precinct_coords = get_transient('precinct_coords'))) {
                  include(locate_template('/lib/transient-precinct.php'));
                  locate_template('/lib/google-auth.php', true, true);

                  $address = urlencode($master['address']);

                  if (function_exists('google_api_key')) {
                    $api_key = google_api_key();
                    $query_string = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $address . '&key=' . $api_key;
                    $api_get = wp_remote_get($query_string);

                    if ( ! is_wp_error( $api_get ) ) {
                      $response = json_decode($api_get['body']);
                      $coordinates = [
                        'lat' => $response->results[0]->geometry->location->lat,
                        'lng' => $response->results[0]->geometry->location->lng
                      ];
                      $precinct_coords = implode(" ", $coordinates);
                      set_transient('precinct_coords', $precinct_coords, 6 * HOUR_IN_SECONDS);
                    } else {
                      echo $api_get->get_error_message();
                    }
                  } else {
                    echo 'No Google API Key';
                  }
                }

                include(locate_template('/lib/point-in-polygon.php'));

                // Create associative array of county boundaries with county_id as the key
                $counties_csv = array_map('str_getcsv', file(get_template_directory() . '/lib/districts/NC_Counties.txt'));
                $counties_head = array_shift($counties_csv);
                $county_shapes = array();
                print_r($counties_csv);
                foreach ($counties_csv as $row) {
                  $county_shapes[$row[2]][] = $row[0] . ' ' . $row[1];
                }

                $pointLocation = new pointLocation();

                echo $precinct_coords . '<br />';

                foreach ($county_shapes as $county_id => $polygon) {
                  echo "$county_id: " . $pointLocation->pointInPolygon($precinct_coords, $polygon) . "<br />";
                  print_r($polygon);
                }
                ?>
              </td>
            </tr>
          </tbody>
        </table>

        <?php
        // Only show for school-specific precincts
        if (get_bloginfo() !== 'North Carolina') :
          $officials = get_users();
          ?>

          <table class="table table-hover table-condensed">
            <thead>
              <tr>
                <th scope="col" class="h3">Election Officials</th>
                <th scope="col">Class</th>
              </tr>
            </thead>

            <tbody>

              <?php foreach ($officials as $official) : if ($official->ID != 1) : ?>

                <tr>
                  <th scope="row">
                    <a href="mailto:<?php echo $official->user_email; ?>">
                      <?php echo $official->display_name; ?>
                    </a><br />
                    <?php if (user_can($official, 'edit_pages')) { ?>
                      <span class="small">Precinct Director</span>
                    <?php } ?>
                  </th>
                  <td><?php echo get_user_meta($official->ID, 'classes', true); ?></td>
                </tr>

              <?php endif; endforeach; ?>

            </tbody>
          </table>

        <?php endif; ?>

      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <h3>TurboVote for Teachers</h3>
        <div class="entry-content-asset" style="height: 500px;"><iframe src="https://firstvotenc.turbovote.org"></iframe></div>
        <p class="small">Powered by TurboVote: <a href="https://firstvotenc.turbovote.org">register to vote, request absentee ballots, and get election reminders</a></p>
      </div>

      <div class="col-md-6">
        <h3>Informational Webinar</h3>
        <div class="entry-content-asset"><iframe width="560" height="315" src="https://www.youtube.com/embed/_ZYJYFWe8Dg" frameborder="0" allowfullscreen></iframe></div>
        <p>This webinar provides an overview of the First Vote North Carolina project, including implementation ideas, training on customizing your school's online ballot, instruction on utilizing the exit poll data for post-election analysis, and a summary of the adaptable curricular resources.</p>
        <p><strong>Questions?</strong> We're here to help: <a href="mailto:help@firstvotenc.org">help@firstvotenc.org</a></p>
      </div>
    </div>

  </div>
</section>
