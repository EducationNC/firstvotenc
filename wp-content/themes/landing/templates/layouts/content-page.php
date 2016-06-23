<?php
the_content();

if (is_page('donate')) {
  get_template_part('templates/components/donately');
} elseif (is_page('subscribe')) {
  echo '<script type="text/javascript" src="//app.icontact.com/icp/loadsignup.php/form.js?c=871299&l=6783&f=4460"></script>';
}
?>
