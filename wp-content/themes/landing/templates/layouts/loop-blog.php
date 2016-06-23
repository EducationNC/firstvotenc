<?php
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$blog = new WP_Query([
  'post_type' => 'post',
  'posts_per_page' => '10',
  'paged' => $paged
]);
?>

<?php if (!$blog->have_posts()) : ?>
  <div class="alert alert-warning">
    Sorry, no results were found.
  </div>
  <?php get_search_form(); ?>
<?php endif; ?>

<?php while ($blog->have_posts()) : $blog->the_post(); ?>
  <?php get_template_part('templates/layouts/block', 'post-side'); ?>
<?php endwhile; ?>

<?php if ($blog->max_num_pages > 1) : ?>
  <nav class="post-nav">
    <?php wp_pagenavi(['query' => $blog]); ?>
  </nav>
<?php endif; ?>
