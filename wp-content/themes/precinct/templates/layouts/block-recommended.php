<?php

use Roots\Sage\Extras;

if (function_exists('get_field')) {
  $recommended = get_field('recommended_articles');
  }
$original_post = $post;
if (!empty($recommended)) {
  // set this to only display first one for now.
  // TODO: add some way to have more than 1 recommended article
  $post = $recommended[0];
} else {
  // previous post by same author
  $post = Extras\get_adjacent_author_post(true);
  // TODO: check if this even exists and fallback to recent post from category?
}

if (!empty($post)) {
  setup_postdata($post);
  ?>
  <div class="row">
    <div class="col-md-7 col-md-push-2point5">
      <h2>Recommended read</h2>
      <?php get_template_part('templates/layouts/block-overlay'); ?>
    </div>
  </div>
  <?php
  wp_reset_postdata();
}
$post = $original_post;
?>
