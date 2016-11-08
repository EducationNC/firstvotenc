<?php

$uploads = network_site_url('wp-content/uploads');
$results = json_decode(file_get_contents($uploads . '/election_results.json'), true);
$blog_ids = array_column($results, 'blog_id');
$blog_ids_unique = array_unique($blog_ids);

// echo '<pre>';
// print_r($blog_ids);
// echo '</pre>';
// delete_transient('precinct_results_table');
if ( false === ( $precinct_results_table = get_transient( 'precinct_results_table' ) ) ) {
  ob_start(); ?>
    <div class="table-responsive panel">
      <table class="table sortable">
        <thead>
          <tr>
            <th scope="col">Precinct</th>
            <th scope="col">Votes</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($blog_ids_unique as $blog_id) {
            $details = get_blog_details($blog_id);
            switch_to_blog($blog_id);
              $q = new WP_Query(['posts_per_page' => 1, 'post_type' => 'election']);
              if($q->have_posts()): while($q->have_posts()): $q->the_post();
                if ($details->blogname !== 'North Carolina') { ?>
                  <tr>
                    <td><a href="<?php echo get_the_permalink(); ?>?results=general" target="_blank"><?php echo $details->blogname; ?></a></td>
                    <td><?php echo count(array_keys($blog_ids, $blog_id)); ?> </td>
                  </tr>
                <?php }
              endwhile; endif; wp_reset_postdata();
            restore_current_blog();
          } ?>
        </tbody>
      </table>
    </div>
    <?php
  $precinct_results_table = ob_get_clean();
  set_transient('precinct_results_table', $precinct_results_table, 1 * DAY_IN_SECONDS);
}

echo $precinct_results_table;
