<?php

use Roots\Sage\Assets;

?>

<?php get_template_part('templates/components/header'); ?>

<section class="precinct-admin">
  <div class="container">
    <div class="row">
      <div class="col-md-6">

        <?php
        $election = new WP_Query([
          'post_type' => 'election',
          'posts_per_page' => -1
        ]);

        if ($election->have_posts()) : ?>

          <table class="table table-hover table-condensed">
            <thead>
              <tr>
                <th scope="col" class="h3">Simulation Elections</th>
                <th scope="col">Dates</th>
              </tr>
            </thead>

            <tbody>

            <?php while ($election->have_posts()): $election->the_post(); ?>

              <tr>
                <th scope="row">
                  <a href="<?php the_permalink(); ?>?edit"><?php the_title(); ?></a><br />
                  <span class="small"><a href="<?php the_permalink(); ?>?edit">Edit</a> | <a href="<?php the_permalink(); ?>">Preview Ballot</a> | <a href="#">Delete</a></span>
                </th>
                <td>11/01/16 - 11/08/16</td>
              </tr>

            <?php endwhile; ?>

            </tbody>
          </table>

          <a class="btn btn-default" href="#">Add Election</a>

        <?php else : ?>

          <div class="well well-sm">
            <p><em>No simulation elections have been created for your precinct yet.</em></p>
            <p>
              <a class="btn btn-default" href="">
                Add simulation election
              </a>
            </p>
          </div>

          <!--<div class="h3">Simulation Elections</div>

          <div class="well well-sm">
            <p><em>No simulation elections have been created for your precinct.</em></p>

            <p><a href="#">Add Simulation Election</a></p>
          </div>-->

        <?php endif; wp_reset_postdata(); ?>
      </div>

      <div class="col-md-6">

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
  </div>
</section>
