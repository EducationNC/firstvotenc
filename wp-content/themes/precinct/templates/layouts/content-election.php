<article <?php post_class(); ?>>
  <header>
    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
  </header>
  <div class="entry-summary">
    <?php
    /**
     * Customize ballot settings -- for teachers
     */
     echo $_GET['edit'];
     if ( ! isset($_GET['edit'])) {
       get_template_part('/templates/layouts/ballot');

       return false;
     }

    /**
     * Check if the user has permissions to edit elections
     */
    if ( ! current_user_can( 'edit_posts' ) ) {
        return __( 'You do not have permissions to edit this post.', 'lang_domain' );
    }

    cmb2_metabox_form( '_cmb_ballot', $election_id );
    ?>

  </div>
</article>
