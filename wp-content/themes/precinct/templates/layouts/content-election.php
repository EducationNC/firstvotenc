<article <?php post_class(); ?>>

  <div class="entry-summary">
    <?php
    /**
     * Customize ballot settings -- for teachers
     */
     if ( ! isset($_GET['edit'])) {
       get_template_part('/templates/layouts/ballot');

       return false;
     }

    /**
     * Check if the user has permissions to edit elections
     */
    if ( ! current_user_can( 'editor' ) ) {
      wp_redirect( get_bloginfo('url') );
      exit;
    }

    // If edit was saved, delete generated ballot and redirect to non-edit page
  	if ( isset( $_POST['object_id'] ) ) {
      update_post_meta( $_POST['object_id'], '_cmb_generated_ballot', '' );
  		$url = esc_url_raw( get_bloginfo('url') );
  		echo "<script type='text/javascript'>window.location.href = '$url';</script>";
  	}

    cmb2_metabox_form( '_cmb_election', get_the_id(), ['save_button' => 'Save Election'] );
    ?>

  </div>
</article>
