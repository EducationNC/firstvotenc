<?php

use Roots\Sage\Titles;

$precinct_name = get_bloginfo('name');
$precinct_id = substr( strrchr( get_bloginfo('url'), '/nc-' ), 4 );
?>

<header class="page-header gray-bg">
  <div class="container">
    <?php if (is_singular(['post', 'election'])) { ?>
      <div class="row">
        <div class="col-md-6">
          <h2 class="entry-title"><?= Titles\title(); ?></h2>
        </div>

        <div class="col-md-6">
          <div class="row">
            <div class="col-md-6">
              <span class="h6">Precinct Name:</span> <?php echo $precinct_name; ?>
            </div>

            <div class="col-md-6">
              <span class="h6">Precinct ID:</span> <?php echo $precinct_id; ?>
            </div>
        </div>
      </div>
    <?php } else { ?>
      <h1 class="entry-title">
        <?php echo $precinct_name; ?>
        <small>Precinct ID: <?php echo $precinct_id; ?></small>
      </h1>
    <?php } ?>
  </div>
</header>
