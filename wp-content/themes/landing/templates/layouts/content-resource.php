<article <?php post_class(); ?>>
  <header>
    <h2 class="entry-title"><?php the_title(); ?></h2>
    <?php
    $cats = wp_get_post_terms(get_the_id(), 'resource-type');
    foreach ($cats as $cat) {
      echo '<p class="entry-meta">' . $cat->name . '</p> ';
    }
    ?>
  </header>
  <div class="entry-summary">
    <?php the_content(); ?>
  </div>
</article>
