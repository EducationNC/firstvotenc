<?php

namespace Roots\Sage\Forms;

/*
 * Preprocess function for live sign up form
 *
 */
add_filter('caldera_preprocess_live', function($data){
  global $domain;

  // Create id for selected school
  $school_code = substr(md5($data['school_name']), 0, 5); // First 5 chars
  $school_code .= substr(md5($data['school_name']), -5); // Last 5 chars

  // Create new precinct site based on school code
  $school_id = wpmu_create_blog($domain, '/' . $school_code . '/', $data['school_name'], 1);

  if (is_wp_error($school_id)) {
    // If this school is already registered, return error message.
    return array(
      'type' => 'error',
      'note'	=>	'School already registered.'  // TODO: Add contact info for person who is already registered at this school.
    );
  } else {

    $userdata = array(
      'user_login'  =>  $data['email_address'],
      'user_email'  =>  $data['email_address'],
      'user_pass'   =>  wp_generate_password(),
      'first_name'  =>  $data['first_name'],
      'last_name'   =>  $data['last_name']
    );

    // Create new account for user
    $user_id = wp_insert_user( $userdata );

    if ( ! is_wp_error( $user_id ) ) {
      // Add custom user meta
      add_user_meta( $user_id, 'classes', $data['what_do_you_teach'], true );

      // Move user to correct precinct
      remove_user_from_blog($user_id, get_current_site()->blog_id); // remove user from main blog.
      add_user_to_blog( $school_id, $user_id, 'editor' );
      update_user_meta( $user_id, 'primary_blog', $school_id );

      // TODO: Add note to check email
    } else {
      // If user already registered, return error message
      return array(
        'type' => 'error',
        'note'	=> $user_id->get_error_message()
      );
    }
  }
});


/*
 * Preprocess function for pre-release sign up form
 *
 */
add_filter('caldera_preprocess', function($data){
  global $domain;

  // Create id for selected school
  $school_code = substr(md5($data['school_name']), 0, 5); // First 5 chars
  $school_code .= substr(md5($data['school_name']), -5); // Last 5 chars

  // Create new precinct site based on school code
  $school_id = wpmu_create_blog($domain, '/' . $school_code . '/', $data['school_name'], 1);

  if (is_wp_error($school_id)) {
    // If this school is already registered, return error message.
    return array(
      'type' => 'error',
      'note'	=>	'School already registered.'
    );
  } else {

    $userdata = array(
      'user_login'  =>  $data['email_address'],
      'user_email'  =>  $data['email_address'],
      'user_pass'   =>  wp_generate_password(),
      'first_name'  =>  $data['first_name'],
      'last_name'   =>  $data['last_name']
    );

    // Create new account for user
    $user_id = wp_insert_user( $userdata );

    if ( ! is_wp_error( $user_id ) ) {
      // Add custom user meta
      add_user_meta( $user_id, 'classes', $data['what_do_you_teach'], true );

      // Move user to correct precinct
      remove_user_from_blog($user_id, get_current_site()->blog_id); // remove user from main blog.
      add_user_to_blog( $school_id, $user_id, 'editor' );
      update_user_meta( $user_id, 'primary_blog', $school_id );
    } else {
      // If user already registered, return error message
      return array(
        'type' => 'error',
        'note'	=> $user_id->get_error_message()
      );
    }
  }
});
