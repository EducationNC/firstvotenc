<?php

use Roots\Sage\Assets;

?>
<section class="jumbotron">
  <div class="parallax-img hidden-xs" style="background-image:url('<?php echo Assets\asset_path('images/banner.jpg'); ?>')"></div>
  <div class="container">
    <img class="visible-xs-block" src="<?php echo Assets\asset_path('images/banner-xs.jpg'); ?>" alt="" />
    <h2 class="h1">Engage Today.<br /><em>Impact Tomorrow.</em></h2>
    <p>Keeping civics real &amp; making it meaningful.</p>
    <p><a class="btn btn-primary btn-lg" href="/sign-up" role="button">Teachers, Get your free toolkit</a></p>
  </div>
</section>

<section class="white-bg">
  <div class="container text-center partners">
    <span class="sr-only">In Partnership With</span>
    <a href="https://www.ednc.org" target="_blank"><img src="<?php echo Assets\asset_path('images/ednc.png'); ?>" srcset="<?php echo Assets\asset_path('images/ednc@2x.png'); ?> 2x" alt="EdNC" /></a>
    <a href="http://humanities.unc.edu/civics/" target="_blank"><img src="<?php echo Assets\asset_path('images/CECLogo.png'); ?>" srcset="<?php echo Assets\asset_path('images/CECLogo@2x.png'); ?> 2x" alt="NC Civic Education Consortium" /></a>
    <a href="http://www.ncpublicschools.org/" target="_blank"><img src="<?php echo Assets\asset_path('images/NCDPI.png'); ?>" srcset="<?php echo Assets\asset_path('images/NCDPI@2x.png'); ?> 2x" alt="Public Schools of North Carolina" /></a>
    <a href="https://turbovote.org/" target="_blank"><img src="<?php echo Assets\asset_path('images/TurboVote_Logo.png'); ?>" srcset="<?php echo Assets\asset_path('images/TurboVote_Logo@2x.png'); ?> 2x" alt="Turbo Vote" /></a>
  </div>
</section>

<section class="gray-bg overview">
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-centered text-center">
        <p class="h2 bottom-margin-important">Launching this fall for the 2016 elections.</p>
      </div>
    </div>
    
    <div class="row flex-sm-up">
      <div class="col-sm-6 col-md-4 col-sm-push-6 col-md-push-7 text-center">
        <img src="<?php echo Assets\asset_path('images/standards-aligned.svg'); ?>" alt="Standards Aligned" />
      </div>
      <div class="col-sm-6 col-md-4 col-sm-pull-6 col-md-pull-2">
        <p>First Vote NC is a free project-based initiative designed around the North Carolina Essential Standards. The <a href="http://humanities.unc.edu/civics/" target="_blank">NC Civic Education Consortium</a> is developing a toolkit including an implementation guide, lesson plans, and additional resources, giving you the structure and flexibility to bring this program into your classroom and school.</p>
      </div>
    </div>

    <div class="row flex-sm-up">
      <div class="col-sm-6 col-md-4 col-md-push-1 text-center">
        <img src="<?php echo Assets\asset_path('images/user-friendly.svg'); ?>" alt="User Friendly" />
      </div>
      <div class="col-sm-6 col-md-4 col-md-push-2">
        <p>All you need is any device connected to the internet &mdash; computer, tablet, or smartphone. Simply register your school to participate and the simulation election platform built by <a href="https://www.ednc.org" target="_blank">EdNC</a> will automatically generate a customizable ballot, all based on your school’s address.</p>
      </div>
    </div>

    <div class="row flex-sm-up">
      <div class="col-sm-6 col-md-4 col-sm-push-6 col-md-push-7 text-center">
        <img src="<?php echo Assets\asset_path('images/project-based.svg'); ?>" alt="Project Based" />
      </div>
      <div class="col-sm-6 col-md-4 col-sm-pull-6 col-md-pull-2">
        <p>First Vote NC is designed to tie in with the <em>American History: Founding Principles, Civics and Economics</em> course. It teaches real-world knowledge and higher order thinking as it asks students to reflect, solve problems, answer complex questions, work with others, lead, and produce a public product.</p>
      </div>
    </div>

    <div class="row flex-sm-up">
      <div class="col-sm-6 col-md-4 col-md-push-1 text-center">
        <img src="<?php echo Assets\asset_path('images/bigger-picture.svg'); ?>" alt="Bigger Picture" />
      </div>
      <div class="col-sm-6 col-md-4 col-md-push-2">
        <p>Graphically illustrated election results and downloadable data sets provide an easy and fun way for students to analyze the data &mdash; helping students look at themselves, their school, and their peers across North Carolina in the context of elections and political issues.</p>
      </div>
    </div>
  </div>
</section>

<section class="white-bg">
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-centered text-center">
        <p class="h2 bottom-margin-important">It isn’t enough to just register &mdash; it isn’t even enough to just vote. Our aim is to create an informed and involved citizenry.</p>
        <?php echo apply_filters('the_content', '[embed]https://www.youtube.com/watch?v=wMTtdvbtQ5c[/embed]'); ?>
        <p class="caption extra-bottom-margin">Thanks to the future voters at Enloe High School in Raleigh, NC for this video.</p>
        <p><a class="btn btn-primary btn-lg" href="/sign-up" role="button">Get Started Today</a></p>
      </div>
    </div>
  </div>
</section>
