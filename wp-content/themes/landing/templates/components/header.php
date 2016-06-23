<?php

use Roots\Sage\Titles;

$image_id = get_post_thumbnail_id();
$featured_image_lg = wp_get_attachment_image_src($image_id, 'large');

if (!is_singular('post')) {
?>

<header class="page-header photo-overlay" style="background-image: url('<?php echo $featured_image_lg[0]; ?>')">
  <div class="article-title-overlay">
    <div class="container">
      <div class="row">
        <div class="col-md-10 col-md-push-1 col-lg-push-0">
          <h1 class="entry-title"><?= Titles\title(); ?></h1>
        </div>
      </div>
    </div>
  </div>
</header>

<?php } ?>
