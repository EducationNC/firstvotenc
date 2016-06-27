<?php

namespace Roots\Sage\Forms;

add_action('caldera_register_user', function( $data ){
  $userdata = array(
    'user_login'  =>  $data['email_address'],
    'user_email'  =>  $data['email_address'],
    'user_pass'   =>  wp_generate_password(),
    'first_name'  =>  $data['first_name'],
    'last_name'   =>  $data['last_name']
  );

  $user_id = wp_insert_user( $userdata ) ;

  //On success
  if ( ! is_wp_error( $user_id ) ) {
    // echo "User created : ". $user_id;
  }
});
