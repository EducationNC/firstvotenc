<?php

use Roots\Sage\Setup;
use Roots\Sage\Titles;

?>
<header class="page-header container">
  <div class="row">
    <h1 class="entry-title">
      <?= Titles\title(); ?>
      <?php if (isset($_GET['contest'])) { ?>
        <small><a class="btn btn-sm btn-default btn-small" href="<?php echo remove_query_arg('contest'); ?>">Back to all results</a></small>
      <?php } ?>
    </h1>

    <?php if (isset($_GET['contest'])) {
      $race = $_GET['contest'];
      $contests = json_decode(get_option('election_contests'), true);
      echo '<h2>' . $contests[$race]['title'] . '</h2>';
    } ?>
  </div>
</header>

<div class="container">
  <?php
    if (isset($_GET['contest'])) {
      get_template_part('templates/layouts/results', 'contest');
    } else {
      get_template_part('templates/layouts/results');
    }
  ?>
</div><!-- /.container -->
