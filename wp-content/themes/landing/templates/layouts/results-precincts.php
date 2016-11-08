<?php

use Roots\Sage\Extras;

$uploads = wp_upload_dir();
$results = json_decode(file_get_contents($uploads['basedir'] . '/election_results.json'), true);
$contests = json_decode(file_get_contents($uploads['basedir'] . '/election_contests.json'), true);

$type = $_GET['results'];

$blog_ids = array_unique(array_column($results, 'blog_id'));

locate_template('/lib/google-auth.php', true, true);
$api_key = google_api_key();

// echo '<pre>';
// print_r($blog_ids);
// echo '</pre>';
?>

<div class="table-responsive panel">
  <table class="table sortable">
    <thead>
      <tr>
        <th scope="col">Precinct</th>
        <th scope="col">County</th>
        <th scope="col">Votes</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($blog_ids as $id) {
        $details = get_blog_details($id);
        switch_to_blog($id);
          $q = new WP_Query(['posts_per_page' => 1, 'post_type' => 'election']);
          if($q->have_posts()): while($q->have_posts()): $q->the_post();
            if ($details->blogname !== 'North Carolina') { ?>
              <tr>
                <td><a href="<?php echo get_the_permalink(); ?>&results=general" target="_blank"><?php echo $details->blogname; ?></a></td>
                <?php
            		// Get geocoded address
            		$api_get = wp_remote_get('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode(str_replace(' - ', ', ', $details->blogname)) . '&key=' . $api_key);

            		if ( ! is_wp_error( $api_get ) ) {
            			$result = json_decode($api_get['body']);

                  foreach ($result->results[0]->address_components as $c) {
                    if (in_array('administrative_area_level_2', $c->types)) {
                      echo '<td>' . $c->short_name . '</td>';
                    }
                  }
                  // echo '<td>'.$result->results->
                  // echo '<td colspan="2">';
                  // var_dump($result);
                  // echo '</td>';
            		} else {
                  echo '<td>';
            			echo $api_get->get_error_message();
                  echo '</td>';
            		}
                ?>
                <td>
                  <?php
                  $n = new WP_Query([
                    'post_type' => 'ballot',
                    'posts_per_page' => -1,
                    'meta_query' => [
                      [
                        'key' => '_cmb_election_id',
                        'value' => get_the_id()
                      ]
                    ]
                  ]);
                  echo $n->found_posts;
                  ?>
                </td>
              </tr>
            <?php }
          endwhile; endif; wp_reset_postdata();
        restore_current_blog();
      } ?>
    </tbody>
  </table>
</div>
