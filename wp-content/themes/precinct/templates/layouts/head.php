<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="alternate" type="application/rss+xml" title="<?php echo get_bloginfo('name'); ?> Feed" href="<?php echo esc_url(get_feed_link()); ?>">
  <?php
  if (is_post_type_archive('data') || is_singular('data-viz')) {
    get_template_part('templates/components/social-meta', 'data-viz');
  }
  ?>

  <?php wp_head(); ?>

  <?php
  if (!is_user_logged_in()) {
    get_template_part('templates/components/analytics');
  }
  ?>
</head>
