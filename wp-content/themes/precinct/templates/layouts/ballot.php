<?php

use Roots\Sage\Assets;
use Roots\Sage\CMB;

$precinct_name = get_bloginfo('name');
$precinct_id = substr( strrchr( get_bloginfo('url'), '/nc-' ), 4 );
?>

<img class="cross-left" src="<?php echo Assets\asset_path('images/ballot-cross.png'); ?>" srcset="<?php echo Assets\asset_path('images/ballot-cross@2x.png'); ?> 2x" alt="" />
<img class="cross-right" src="<?php echo Assets\asset_path('images/ballot-cross.png'); ?>" srcset="<?php echo Assets\asset_path('images/ballot-cross@2x.png'); ?> 2x" alt="" />

<div class="ballot-head row">
  <div class="col-md-6">
    <strong>First Vote NC<br />
      <?php echo $precinct_name; ?><br />
      <?php echo date('F j, Y', get_post_meta(get_the_id(), '_cmb_voting_day', true)); ?>
    </strong>
  </div>

  <div class="col-md-6 text-right h2">
    <?php echo 'G' . $precinct_id; ?>
  </div>
</div>

<div class="ballot-wrap">
  <div class="row ballot-wrap-head">
    <div class="col-md-4">
      <div class="row">
        <div class="col-sm-3">
          A
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="row">
        <div class="col-sm-3">
          B
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="row">
        <div class="col-sm-3">
          C
        </div>
      </div>
    </div>
  </div>

  <div class="row ballot-inst">
    <div class="col-md-9">
      <h6>Ballot Marking Instructions:</h6>
      <ol>
        <li>Completely fill in the oval to the left of each selection of your choice as shown.</li>
        <li>For the purposes of this simulation election, at least one selection for every contest is required. If you do not wish to cast a vote for a particular contest, mark "No Selection."</li>
      </ol>
    </div>

    <div class="col-md-3">
      <img class="example" src="<?php echo Assets\asset_path('images/ballot-example.png'); ?>" srcset="<?php echo Assets\asset_path('images/ballot-example@2x.png'); ?> 2x" alt="" />
    </div>
  </div>

  <?php
  $ballot = CMB\get_voter_ballot_object();

  $output = "";

  // Get any submission errors
  if ( ( $error = $ballot->prop( 'submission_error' ) ) && is_wp_error( $error ) ) {
    $output .= '<h3>' . sprintf( 'There was an error in the submission: %s', '<strong>'. $error->get_error_message() .'</strong>' ) . '</h3>';
  }

  // If the post was submitted successfully, notify the user.
  if ( isset( $_GET['post_submitted'] ) && ( $post = get_post( absint( $_GET['post_submitted'] ) ) ) ) {
    $output .= '<h3>Thank you, your vote has been submitted</h3>';
  } else {
    // Display metabox on page (changing save button text)
    $output .= cmb2_get_metabox_form( $ballot, 'fake-oject-id', array( 'save_button' => 'Cast Ballot' ) );
  }
  echo $output;
  ?>

  <div class="ballot-footer">
    <div class="col-md-4">
      <div class="row">
        <div class="col-sm-3">
          A
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="row">
        <div class="col-sm-3">
          B
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="row">
        <div class="col-sm-3">
          C
        </div>
      </div>
    </div>
  </div>
</div>
