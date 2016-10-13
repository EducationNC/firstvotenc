<section class="precinct">
  <div class="container">
    <div class="row extra-bottom-margin">
      <div class="col-md-7 col-centered">
        <?php
        // Get upcoming election
        $election = new WP_Query([
          'posts_per_page' => 1,  // Just get most recent. But in the future we'll need to change the election dates to be stored with timestamps so we can query them
          'post_type' => 'election'
        ]);

        if ($election->have_posts()) : while ($election->have_posts()) : $election->the_post();
          $early_voting = strtotime(get_post_meta(get_the_id(), '_cmb_early_voting', true));
          $election_day = strtotime(get_post_meta(get_the_id(), '_cmb_voting_day', true));
          ?>

          <h3><?php the_title(); ?></h3>
          <p>
            <strong>Early voting:</strong>
            <?php echo date('F j, Y', $early_voting); ?> -
            <?php echo date('F j, Y', strtotime('-1 day', $election_day)); ?>
          </p>
          <p><strong>Election day:</strong> <?php echo date('F j, Y', $election_day); ?></p>
          <p><strong>Poll hours:</strong> 7:30am - 7:30pm</p>

          <p><a class="btn btn-default" href="<?php the_permalink(); ?>">View sample ballot</a></p>

          <?php
        endwhile; endif; wp_reset_postdata();
        ?>
      </div>
    </div>
  </div>
</section>
