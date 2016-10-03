<?php

use Roots\Sage\CMB;

$form = CMB\get_election_form();

?>
<div class="container add-election">
  <div class="content">
    <main class="main">
      <?php
      $output = "";

      // Get any submission errors
      if ( ( $error = $form->prop( 'submission_error' ) ) && is_wp_error( $error ) ) {
        $output .= '<h3>' . sprintf( 'There was an error in the submission: %s', '<strong>'. $error->get_error_message() .'</strong>' ) . '</h3>';
      }

      // Display metabox on page (changing save button text)
      $output .= cmb2_get_metabox_form( $form, 'fake-oject-id', array( 'save_button' => 'Add Election' ) );

      echo $output;
      ?>
    </main>
  </div>
</div>
