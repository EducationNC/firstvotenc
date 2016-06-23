<?php

use Roots\Sage\Assets;

?>
<div class="grid flex">
  <?php while (have_posts()) : the_post(); ?>
    <article <?php post_class('block-post'); ?>>
      <a class="mega-link" href="<?php the_permalink(); ?>"></a>
      <header class="entry-header">
        <h4 class="post-title"><?php the_title(); ?></h4>
        <?php
        $cats = wp_get_post_terms(get_the_id(), 'resource-type');
        foreach ($cats as $cat) {
          echo '<span class="meta">' . $cat->name . '</span> ';
        }
        ?>
      </header>
    </article>
  <?php endwhile; ?>
</div>
