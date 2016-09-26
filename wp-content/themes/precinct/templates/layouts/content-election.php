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
    // if ( ! current_user_can( 'edit_posts' ) ) {
    //     return __( 'You do not have permissions to edit this post.', 'lang_domain' );
    // }

    cmb2_metabox_form( '_cmb_election', get_the_id(), ['save_button' => 'Save Election'] );

    echo '<a id="btn-preview-ballot" class="btn btn-default" href="' . get_permalink() . '">Preview Ballot</a>';
    ?>

  </div>
</article>
