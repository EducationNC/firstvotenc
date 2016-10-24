<?php
$i = 1;
if(is_multisite()){
    global $wpdb;
    $blogs = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->blogs WHERE spam = '0' AND deleted = '0' and archived = '0' and public='0'"));
    if(!empty($blogs)){
        ?><?php
        foreach($blogs as $blog){
            switch_to_blog($blog->blog_id);
            $details = get_blog_details($blog->blog_id);
            $q = new WP_query([
              'posts_per_page' => -1,
              'post_type' => 'election'
            ]);
            if($q->have_posts()){
                while($q->have_posts()){
                    $q->the_post();
                    echo $i . '<br />';
                    echo 'ID: <a href="' . get_site_url() . '" target="_blank">' . $details->path . '</a><br />';
                    echo 'Name: ' . $details->blogname . '<br />';
                    echo 'Election: ' . get_the_title() . '<br />';
                    echo 'Date created: ' . get_the_time('F j, Y g:ia') . '<br />';

                    $u = get_users([$blog->blog_id]);
                    foreach ($u as $user) {
                      echo $user->user_email . '<br />';
                    }
                    $i++;
                }
            }
            wp_reset_query();
            restore_current_blog();
        }
    }
}
