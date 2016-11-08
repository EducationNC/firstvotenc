<?php

$uploads = wp_upload_dir();
$results = json_decode(file_get_contents($uploads['basedir'] . '/election_results.json'), true);

$blog_ids = array_unique(array_column($results, 'blog_id'));

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
          <?php foreach ($blog_ids as $id) {
            $details = get_blog_details($id);
            switch_to_blog($id);
              $q = new WP_Query(['posts_per_page' => 1, 'post_type' => 'election']);
              if($q->have_posts()): while($q->have_posts()): $q->the_post();
                if ($details->blogname !== 'North Carolina') { ?>
                  <tr>
                    <td><a href="<?php echo get_the_permalink(); ?>&results=general" target="_blank"><?php echo $details->blogname; ?></a></td>
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
    <?php
  $precinct_results_table = ob_get_clean();
  set_transient('precinct_results_table', $precinct_results_table, 1 * DAY_IN_SECONDS);
}

echo $precinct_results_table;
