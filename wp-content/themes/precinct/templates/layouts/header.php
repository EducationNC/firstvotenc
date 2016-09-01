<?php

use Roots\Sage\Assets;
use Roots\Sage\Nav;

?>
<header id="header" class="banner">
  <div class="container">
    <div class="navbar-header navbar-default">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo Assets\asset_path('images/logo.png'); ?>" srcset="<?php echo Assets\asset_path('images/logo@2x.png'); ?> 2x" alt="First Vote NC" /></a>
    </div>

    <nav class="navbar collapse navbar-collapse" data-topbar role="navigation" id="navbar-collapse-1">
      <div class="navbar-right">
        <ul class="nav navbar-nav">
          <li class=""><a href="#">Help</a></li>
          <li class="dropdown">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Alisa <span class="caret"></span></button>
            <ul class="dropdown-menu dropdown-menu-right">
              <li><a href="#">Teacher Portal</a></li>
              <li><a href="#">Profile</a></li>
              <li><a href="#">Log Out</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </div>
</header>
