<?php

use Roots\Sage\Assets;

?>
<section class="challenge-approach-impact">
  <div class="slanted-photos">
    <div class="challenge">
      <img class="background" src="<?php echo Assets\asset_path('images/warehouse.jpg'); ?>" />
      <div class="wrap-content">
        <div class="h2">
          <span class="skew">The Challenge</span>
        </div>
        <div class="hover">
          <div class="shape"></div>
          <ul>
            <li><img src="<?php echo Assets\asset_path('images/people.svg'); ?>" /><strong>1 in 6 adults</strong> and <strong>1 in 4 children</strong> live in poverty in North Carolina.</li>
            <li><img src="<?php echo Assets\asset_path('images/loans.svg'); ?>" /><strong>Little investment</strong> in communities and loans are <strong>difficult to obtain</strong>.</li>
            <li><img src="<?php echo Assets\asset_path('images/unemployment.svg'); ?>" /><strong>High unemployment rate</strong> in rural NC and many communities have <strong>no voice in Raleigh</strong>.</li>
          </ul>
        </div>
        <div class="solid-notch">
          Too many communities have been left behind in North Carolina. <strong>We must address these challenges.</strong>
        </div>
      </div>
    </div>

    <div class="approach">
      <img class="background" src="<?php echo Assets\asset_path('images/puzzle.jpg'); ?>" />
      <div class="wrap-content">
        <div class="h2">
          <span class="skew">Our Approach</span>
        </div>
        <div class="hover">
          <ul>
            <li><img src="<?php echo Assets\asset_path('images/leadership.svg'); ?>" />Programs that nurture <strong>community-based leadership</strong> and stimulate <strong>social innovation</strong>.</li>
            <li><img src="<?php echo Assets\asset_path('images/policy.svg'); ?>" /><strong>Advocating for better policy</strong> at both the statewide and local government levels.</li>
            <li><img src="<?php echo Assets\asset_path('images/capital.svg'); ?>" />Providing access to the <strong>capital</strong> needed to finance the development of <strong>strong, vibrant and inclusive</strong> communities.</li>
          </ul>
        </div>
        <div class="solid-notch">
          We work with our partners across the state to <strong>transform their communities</strong> and <strong>increase access to opportunity</strong> for all North Carolinians.
        </div>
      </div>
    </div>
  </div>

  <div class="gradient-bg">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center extra-bottom-margin">
          <div class="h2">
            <span class="skew">Our Impact</span>
          </div>
        </div>
      </div>

      <div class="row impact-blocks">
        <div class="col-md-4">
          <div class="row overlap">
            <div class="col-xs-6 col-md-12">
              <div class="notch-img">
                <div class="notch-inner">
                  <img src="<?php echo Assets\asset_path('images/leadership.jpg'); ?>" />
                </div>
              </div>
            </div>

            <div class="col-xs-6 col-md-12">
              <div class="circle-stat">
                <div class="spacer"></div>
                <div class="circle">
                  <div class="wrap-content">
                    <span class="stat">208</span>
                    youth served through leadership program
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="row overlap">
            <div class="col-xs-6 col-md-12">
              <div class="circle-stat">
                <div class="spacer"></div>
                <div class="circle">
                  <div class="wrap-content">
                    <span class="stat">$68M</span>
                    in capital investments since inception
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xs-6 col-md-12">
              <div class="notch-img">
                <div class="notch-inner">
                  <img src="<?php echo Assets\asset_path('images/investment.jpg'); ?>" />
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="row overlap">
            <div class="col-xs-6 col-md-12">
              <div class="notch-img">
                <div class="notch-inner">
                  <img src="<?php echo Assets\asset_path('images/public-policy.jpg'); ?>" />
                </div>
              </div>
            </div>

            <div class="col-xs-6 col-md-12">
              <div class="circle-stat">
                <div class="spacer"></div>
                <div class="circle">
                  <div class="wrap-content">
                    <span class="stat">50K</span>
                    citizens became home owners &amp; learned financial skills
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="whats-new">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="striped-line">
          <div class="h2">What's New</div>
        </div>
      </div>
    </div>

    <div class="row">
      <?php
      $news = new WP_Query([
        'posts_per_page' => 2,
      ]);

      if ($news->have_posts()) : while ($news->have_posts()) : $news->the_post();
        echo '<div class="col-md-6">';
        get_template_part('templates/layouts/block-overlay');
        echo '</div>';
      endwhile; endif; wp_reset_query();
      ?>
    </div>

    <div class="row">
      <div class="col-md-12">
        <script src="//assets.juicer.io/embed.js" type="text/javascript"></script>
        <link href="//assets.juicer.io/embed.css" media="all" rel="stylesheet" type="text/css" />
        <ul class="juicer-feed" data-feed-id="ncinitiative"><h1 class="referral"><a href="https://www.juicer.io">Powered by Juicer</a></h1></ul>
      </div>
    </div>
  </div>
</section>

<section class="data-tracker">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="striped-line"></div>

        <div class="data-banner has-notch">
          <div class="title-top">Community Development</div>
          <div class="title-bottom">Data Tracker</div>
          <img class="data-bars" src="<?php echo Assets\asset_path('images/data-bars.png'); ?>" />
          <a class="mega-link" href="/data/"></a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="stronger-together">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="striped-line"></div>

        <div class="st-banner has-notch">
          <div class="house">
            <img src="<?php echo Assets\asset_path('images/st-house.jpg'); ?>" />
          </div>
          <img class="st-logo" src="<?php echo Assets\asset_path('images/stronger-together-logo.png'); ?>" />
          <a class="mega-link" href="/nc-housing-coalition/"></a>
        </div>
      </div>
    </div>
  </div>
</section>
