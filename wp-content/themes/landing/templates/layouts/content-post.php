<?php
$page = get_query_var('page');

$comments_open = comments_open();

$image_id = get_post_thumbnail_id();
$featured_image_src = wp_get_attachment_image_src($image_id, 'full');
$featured_image_lg = wp_get_attachment_image_src($image_id, 'large');

$prefix = '_cdi_';
$featured_image_align = get_post_meta(get_the_ID(), $prefix . 'featured_image_alignment', true);
?>

<article <?php post_class('article'); ?>>

  <?php if (has_post_thumbnail() && $featured_image_align == 'hero') { ?>
    <header class="entry-header hero-image">
      <div class="photo-overlay">
        <div class="parallax-img hidden-xs" style="background-image:url('<?php echo $featured_image_src[0]; ?>')"></div>
        <img class="visible-xs-block" src="<?php echo $featured_image_lg[0]; ?>" />

        <?php if ( ! empty($title_overlay) ) { ?>
          <img class="title-image-overlay" src="<?php echo $title_overlay['url']; ?>" alt="<?php the_title(); ?>" />
          <h1 class="entry-title hidden"><?php the_title(); ?></h1>
        <?php } ?>

        <div class="article-title-overlay">
          <?php if ( empty($title_overlay) ) { ?>
            <div class="container">
              <div class="row">
                <div class="col-md-8 col-centered">
                  <h1 class="entry-title"><?php the_title(); ?></h1>
                </div>
              </div>
            </div>
          <?php } ?>

          <div class="container-fluid">
            <div class="row">
              <div class="col-xs-12 text-right caption hidden-xs no-bottom-margin">
                <?php
                $thumb_id = get_post_thumbnail_id();
                $thumb_post = get_post($thumb_id);
                echo $thumb_post->post_excerpt;
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <div class="container">
      <div class="row">
        <div class="col-md-8 col-centered">
          <?php get_template_part('templates/components/entry-meta'); ?>
        </div>
      </div>
    </div>
  <?php } else { ?>
    <header class="entry-header container">
      <div class="row">
        <div class="col-md-8 col-centered">
          <h1 class="entry-title"><?php the_title(); ?></h1>
          <?php get_template_part('templates/components/entry-meta'); ?>
        </div>
      </div>
    </header>
  <?php } ?>

  <div class="entry-content">
    <div class="container">
      <div class="row">
        <div class="col-md-7 col-centered">
          <?php
          if (has_post_thumbnail() && $featured_image_align !== 'hero') {
            echo '<div class="alignnone no-top-margin">';
            the_post_thumbnail('large');
            $thumb_id = get_post_thumbnail_id();
            $thumb_post = get_post($thumb_id);

            if ($thumb_post->post_excerpt) {
              ?>
              <div class="caption extra-bottom-margin">
                <?php echo $thumb_post->post_excerpt; ?>
              </div>
              <?php
            }
            echo '</div>';
          }

          the_content();

          wp_link_pages(
            array(
              'before' => '<nav class="page-nav"><p><span class="pages">Skip to page:</span>',
              'after' => '</p></nav>'
            )
          );
          ?>
        </div>
      </div>
    </div>
  </div>

  <footer class="container print-no">
    <?php if ($comments_open == 1) { ?>
      <div class="row">
        <div class="col-md-7 col-md-push-2point5">
          <?php comments_template('templates/components/comments'); ?>
        </div>
      </div>
    <?php } ?>

    <?php get_template_part('templates/layouts/block', 'recommended'); ?>
  </footer>
</article>

<?php get_template_part('templates/components/social-share'); ?>
