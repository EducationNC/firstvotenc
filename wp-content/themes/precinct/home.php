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

          <div class="h3">Simulation Elections</div>

          <div class="well well-sm">
            <p><em>No simulation elections have been created for your precinct.</em></p>

            <p><a href="#">Add Simulation Election</a></p>
          </div>

        <?php endif; wp_reset_postdata(); ?>
      </div>

      <div class="col-md-6">

        <?php $officials = get_users(); ?>

        <table class="table table-hover table-condensed">
          <thead>
            <tr>
              <th scope="col" class="h3">Election Officials</th>
              <th scope="col">Class</th>
            </tr>
          </thead>

          <tbody>

            <?php foreach ($officials as $official) : ?>

              <tr>
                <th scope="row">
                  <a href="#"><?php echo $official->display_name; ?></a><br />
                  <span class="small">Precinct Director</span>
                </th>
                <td>Civics &amp; Economics</td>
              </tr>

            <?php endforeach; ?>

          </tbody>
        </table>

      </div>
    </div>
  </div>
</section>

<section class="teacher-resources">
  <section class="container">
    <div class="row">
      <div class="col-md-12">
        <h3>Classroom Resources</h3>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        Implementation guide
      </div>

      <div class="col-md-6">
        <h4>Lesson Plans</h4>

      </div>
    </div>
  </div>
</section>
