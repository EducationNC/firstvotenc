<?php

use Roots\Sage\Titles;

$precinct_name = get_bloginfo('name');
$precinct_id = substr( strrchr( get_bloginfo('url'), '/nc-' ), 4 );

if (get_post_type() == 'election' && !isset($_GET['edit']) && !isset($_GET['results']))
  return false;
?>

<header class="page-header">
  <div class="container">
    <?php if (is_singular(['post', 'election'])) { ?>
      <h1 class="entry-title">
        <?= Titles\title(); ?>
        <small>
          <?php echo $precinct_name; ?>
          &nbsp;&nbsp;&nbsp;&nbsp;
          <span class="h6">Precinct ID:</span> <?php echo $precinct_id; ?>
        </small>
      </h1>
    <?php } elseif (isset($_GET['add'])) { ?>
      <h1 class="entry-title">
        Add Simulation Election
        <small>
          <?php echo $precinct_name; ?>
          &nbsp;&nbsp;&nbsp;&nbsp;
          <span class="h6">Precinct ID:</span> <?php echo $precinct_id; ?>
        </small>
      </h1>
    <?php } elseif (is_page('lesson-plans')) { ?>
      <div class="row">
        <div class="col-md-7 col-centered">
          <h1 class="entry-title">Lesson Plans</h1>
        </div>
      </div>
    <?php } else { ?>
      <h1 class="entry-title">
        <?php echo $precinct_name; ?>
        <small><span class="h6">Precinct ID:</span> <?php echo $precinct_id; ?></small>
      </h1>
    <?php } ?>
  </div>
</header>
