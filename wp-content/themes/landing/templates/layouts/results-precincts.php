<?php

$uploads = wp_upload_dir();
$results = json_decode(file_get_contents($uploads['basedir'] . '/election_results.json'), true);
$blog_ids = array_column($results, 'blog_id');
$blog_ids_unique = array_unique($blog_ids);

?>
<div class="panel">
  <div class="panel-heading"><h2 class="h3">Map of participating schools</h2></div>
  <div class="panel-body">
    <div class="entry-content-asset">
      <iframe src="https://www.google.com/maps/d/u/0/embed?mid=1erNunewLx3L_Z4bBNmPAjXC8Pa0" width="640" height="480"></iframe>
    </div>
  </div>
</div>

<div class="table-responsive panel">
  <div class="panel-heading"><h2 class="h3">Explore results by precinct</h2></div>
  <div class="panel-body">
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
                  <td><a href="<?php the_permalink(); ?>" target="_blank"><?php echo $details->blogname; ?></a></td>
                  <td><?php echo count(array_keys($blog_ids, $blog_id)); ?> </td>
                </tr>
              <?php }
            endwhile; endif; wp_reset_postdata();
          restore_current_blog();
        } ?>
      </tbody>
    </table>
  </div>
</div>
