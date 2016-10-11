<?php

use Roots\Sage\Wrapper;

// Requires users to be logged in to see pages
if ( !is_user_logged_in() && !is_singular('election') ) {
  wp_redirect(network_site_url('/teacher-login'));
  exit;
}

$class = '';
if ( isset($_GET['post_submitted']) ) {
  $class = 'exit-poll';
} elseif ( isset($_GET['edit']) || isset($_GET['add']) ) {
  $class = 'edit-election';
} elseif ( is_singular('election') && !isset($_GET['edit']) ) {
  $class = 'ballot';
}
?>

<!doctype html>
<html <?php language_attributes(); ?>>
  <?php get_template_part('templates/layouts/head'); ?>
  <body <?php body_class($class); ?>>
    <!--[if IE]>
      <div class="alert alert-warning">
        <?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'sage'); ?>
      </div>
    <![endif]-->
    <?php
      do_action('get_header');
      get_template_part('templates/layouts/header');
    ?>
    <div class="wrap clearfix" role="document">
      <?php include Wrapper\template_path(); ?>
    </div><!-- /.wrap -->
    <?php
      do_action('get_footer');
      get_template_part('templates/layouts/footer');
      wp_footer();
    ?>
  </body>
</html>
