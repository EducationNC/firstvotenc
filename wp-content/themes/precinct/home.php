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
            <p><em>You're invited to</em></p>
            <h3 style="margin-top: 0;">First Vote and the 2016 Elections: Engaging the Future Electorate</h3>
            <h4>A Free Webinar for Teachers</h4>
            <p>Monday, September 26, 2016<br />
              4:00 pm - 5:00 pm ET</p>
            <p>Join us to learn how to use First Vote, a one-of-a-kind online simulation election platform, in your classroom. This webinar will provide an overview of the First Vote project, including implementation ideas, training on customizing your school's online ballot, instruction on utilizing the exit poll data for post-election analysis, and a summary of the adaptable curricular resources.</p>
            <p>While First Vote NC is designed for public and charter high school students, all educators are welcome to attend the webinar, utilize the materials and participate in a modified simulation election.</p>
            <p>
              <a class="btn btn-primary" href="http://r20.rs6.net/tn.jsp?f=001csrsONODIz19dVm4E5DxAJOfyEdIc5g1IEDL-2mFjSnS6vG8qewGi0vPKQCIlon7rF8kbpGVG5pLNhdfG6ZOUbVvCcwZanS4nGT9-msZ76yeKu5jznIGnrLvY6fHrLAmZwKwyooe4h_dOYTWyH36Pg_SImGknhHHcvdzo8O3qxF_nd-s676aGoukHp0KJlR0qk3cqFXyPX6pYZ2eWvRSz5VQj3A3MwL9Gnxr0V1DK_7h4B2tbDH-paigVtqtU9BU84xINXXm7o1VkKVPnPdgV95RNwTTugVLEL4eN6HmQMs=&c=HCLwE839wXF_DvQ18ocFVD92-EAcVpvMlNLh5KJcBDBBKmQUER403Q==&ch=yvEMmb4yxNzOtY2T-gkWVO-_FLRzBRDqHP6vI1rukQQJOIbXeWRZBA==" target="_blank">
                Register Now
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
