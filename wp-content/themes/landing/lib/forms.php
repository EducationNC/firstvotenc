<?php

namespace Roots\Sage\Forms;

/*
 * Preprocess function for live sign up form
 *
 */
add_filter('caldera_preprocess_live', function($data){
  global $domain;

  // Get Precinct ID
  $school = get_page_by_path($data['school_name'], OBJECT, 'precinct');

  // Create new precinct site based on school code
  $school_id = wpmu_create_blog($domain, '/nc-' . $school->ID . '/', $school->post_title, 1);

  if (is_wp_error($school_id)) {
    // If this school is already registered, set role to contributor.
    $role = 'contributor';
  } else {

    $userdata = array(
      'user_login'  =>  $data['email_address'],
      'user_email'  =>  $data['email_address'],
      'user_pass'   =>  wp_generate_password(),
      'first_name'  =>  $data['first_name'],
      'last_name'   =>  $data['last_name']
    );

    $role = 'editor';

    // Create new account for user
    $user_id = wp_insert_user( $userdata );

    if ( ! is_wp_error( $user_id ) ) {
      // Add custom user meta
      add_user_meta( $user_id, 'classes', $data['what_do_you_teach'], true );

      // Move user to correct precinct
      remove_user_from_blog($user_id, get_current_site()->blog_id); // remove user from main blog.
      add_user_to_blog( $school_id, $user_id, $role );
      update_user_meta( $user_id, 'primary_blog', $school_id );

      // TODO: Add note to check email
    } else {
      // If user already registered, delete blog that was created in step 1 and return error message
      wpmu_delete_blog($school_id, TRUE);

      return array(
        'type' => 'error',
        'note'	=> $user_id->get_error_message()
      );
    }
  }
});
