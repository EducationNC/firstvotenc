<?php get_template_part('templates/components/header'); ?>

<div class="container">
  <div class="row">
    <main class="main">
      <?php if (!have_posts()) : ?>
        <div class="alert alert-warning">
          <?php _e('Sorry, no results were found.', 'sage'); ?>
        </div>
        <?php get_search_form(); ?>
      <?php endif; ?>

      <?php while (have_posts()) : the_post(); ?>
        <?php get_template_part('templates/layouts/block-post', 'side'); ?>
      <?php endwhile; ?>
    </main>
  </div><!-- /.content -->
</div><!-- /.container -->

<nav class="post-nav container">
  <?php wp_pagenavi(); ?>
</nav>
