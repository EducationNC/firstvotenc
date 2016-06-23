<?php

use Roots\Sage\Assets;

?>
<footer class="global-footer">
  <div class="striped-line"></div>

  <div class="container">
    <div class="text-center">
      <p class="h4">
        Get the latest news
        <a class="btn btn-skew btn-teal" data-toggle="modal" data-target="#emailSignupModal">Subscribe</a>
        <a class="icon-facebook" href="http://www.facebook.com/ncinitiative" target="_blank" rel="nofollow"></a>
        <a class="icon-twitter" href="http://twitter.com/ncinitiative" target="_blank" rel="nofollow"></a>
        <a class="icon-flickr" href="http://www.flickr.com/photos/ncinitiative/" target="_blank" rel="nofollow"></a>
      </p>

      <p>Copyright &copy; <?php echo date('Y'); ?> &nbsp;|&nbsp; NC Community Development Initiative &nbsp;|&nbsp; 919.828.5655 &nbsp;|&nbsp; <a href="mailto:<?php echo antispambot('info@ncinitiative.org'); ?>"><?php echo antispambot('info@ncinitiative.org'); ?></a></p>

      <p>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo Assets\asset_path('images/nccdi-logo-footer.jpg'); ?>" /></a>
        <a href="http://nchousing.org" target="_blank"><img src="<?php echo Assets\asset_path('images/nchc-logo-footer.jpg'); ?>" /></a>
      </p>
    </div>
  </div>
</footer>

<?php get_template_part('templates/components/email-signup'); ?>
