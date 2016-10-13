<article <?php post_class(); ?>>

  <div class="entry-summary">
    <?php
    // Display exit poll
    if ( isset( $_GET['post_submitted'] ) && ( $post = get_post( absint( $_GET['post_submitted'] ) ) ) ) {
      get_template_part('/templates/layouts/exit-poll');
      return false;
    }

    if ( isset( $_GET['edit'] ) ) {
      // Check if the user has permissions to edit elections
      if ( ! current_user_can( 'editor' ) ) {
        wp_redirect( get_the_permalink() );
        exit;
      }

      // If edit was saved, delete generated ballot and redirect to non-edit page
    	if ( isset( $_POST['object_id'] ) ) {
        update_post_meta( $_POST['object_id'], '_cmb_generated_ballot', '' );
    		$url = esc_url_raw( get_bloginfo('url') );
    		echo "<script type='text/javascript'>window.location.href = '$url';</script>";
    	}

      // Customize ballot settings -- for teachers
      cmb2_metabox_form( '_cmb_election', get_the_id(), ['save_button' => 'Save Election'] );
      return false;
    }

    // Display live ballot
    get_template_part('/templates/layouts/ballot');
    ?>

  </div>
</article>
