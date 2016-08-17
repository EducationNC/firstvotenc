<?php

use Roots\Sage\Assets;

?>
<section class="gray-bg">
  <div class="container">
    <?php
    $election = new WP_Query([
      'post_type' => 'election',
      'posts_per_page' => -1
    ]);

    if ($election->have_posts()) : while ($election->have_posts()): $election->the_post();
      ?>

      <h2><?php the_title(); ?></h2>
      <p><a href="<?php the_permalink(); ?>?edit" class="btn btn-default">Customize Ballot</a></p>

      <?php
    endwhile; endif; wp_reset_postdata();
    ?>
  </div>
</section>
