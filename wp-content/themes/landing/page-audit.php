<table class="table table-responsive">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Precinct</th>
      <th scope="col">School</th>
      <th scope="col">Election</th>
      <th scope="col">Date</th>
      <th scope="col">Teachers</th>
    </tr>
  </thead>
  <tbody>
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
                        ?>
                        <tr>
                          <td><?php echo $i; ?></td>
                          <td><a href="<?php echo get_site_url(); ?>" target="_blank"><?php echo $details->path; ?></a></td>
                          <td><?php echo $details->blogname; ?></td>
                          <td><a href="<?php echo get_the_permalink(); ?>" target="_blank"><?php echo get_the_title(); ?></a></td>
                          <td><?php echo get_the_time('F j, Y g:ia e'); ?></td>
                          <td>
                            <?php
                            $u = get_users([$blog->blog_id]);
                            foreach ($u as $user) {
                              echo $user->user_email . '<br />';
                            }
                            ?>
                          </td>
                        </tr>
                        <?php
                        $i++;
                    }
                }
                wp_reset_query();
                restore_current_blog();
            }
        }
    }
    ?>
  </tbody>
</table>
