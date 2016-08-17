<?php

namespace Roots\Sage\Setup;

use Roots\Sage\Assets;

/**
 * Theme setup
 */
function setup() {
  // Enable features from Soil when plugin is activated
  // https://roots.io/plugins/soil/
  add_theme_support('soil-clean-up');
  add_theme_support('soil-disable-trackbacks');
  add_theme_support('soil-nice-search');

  // Make theme available for translation
  // Community translations can be found at https://github.com/roots/sage-translations
  load_theme_textdomain('sage', get_template_directory() . '/lang');

  // Enable plugins to manage the document title
  // http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
  add_theme_support('title-tag');

  // Register wp_nav_menu() menus
  // http://codex.wordpress.org/Function_Reference/register_nav_menus
  register_nav_menus([
    'primary_navigation' => __('Primary Navigation', 'sage')
  ]);

  // Enable post thumbnails
  // http://codex.wordpress.org/Post_Thumbnails
  // http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size
  // http://codex.wordpress.org/Function_Reference/add_image_size
  add_theme_support('post-thumbnails');

  // Enable post formats
  // http://codex.wordpress.org/Post_Formats
  // add_theme_support('post-formats', ['aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio']);

  // Enable HTML5 markup support
  // http://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
  add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

  // Use main stylesheet for visual editor
  // To add custom styles edit /assets/styles/layouts/_tinymce.scss
  add_editor_style(Assets\asset_path('styles/main.css'));
}
add_action('after_setup_theme', __NAMESPACE__ . '\\setup');

/**
 * Determine which pages should NOT display the sidebar
 */
function display_sidebar() {
  static $display;

  isset($display) || $display = !in_array(true, [
    // The sidebar will NOT be displayed if ANY of the following return true.
    // @link https://codex.wordpress.org/Conditional_Tags
    is_404(),
    is_front_page(),
    is_singular(['post', 'election']),
    is_page(),
    is_search(),
    is_page_template('template-events.php'),
  ]);

  return apply_filters('sage/display_sidebar', $display);
}

/**
 * Theme assets
 */
function assets() {
  wp_enqueue_style('sage/css', Assets\asset_path('styles/main.css'), false, null);

  if (is_single() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  wp_enqueue_script('sage/js', Assets\asset_path('scripts/main.js'), ['jquery'], null, true);
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\assets', 100);

function google_fonts() {
  echo '<link href="//fonts.googleapis.com/css?family=Lato:400,700,400italic|Merriweather:400,400italic,700italic,700" rel="stylesheet" type="text/css">';
}
add_action('wp_head', __NAMESPACE__ . '\\google_fonts');
add_action('embed_head', __NAMESPACE__ . '\\google_fonts');

//
// /**
//  * Make sure WP SEO isn't adding meta tags to the head of data dashboard
//  */
// function remove_yoast_data_dashboard() {
//   if (is_post_type_archive('data') || is_singular('data-viz')) {
//     if (defined('WPSEO_VERSION')) { // Yoast SEO
//       global $wpseo_og;
//       remove_action( 'wpseo_head', array( $wpseo_og, 'opengraph' ), 30 );
//       remove_action( 'wpseo_head', array( 'WPSEO_Twitter', 'get_instance' ), 40 );
//     }
//   }
// }
// add_filter('wp_enqueue_scripts', __NAMESPACE__ . '\\remove_yoast_data_dashboard', 10);
//

/**
 * Assets for embeds
 */
function embed_assets() {
  wp_enqueue_style('sage/css', Assets\asset_path('styles/embed.css'), false, null);
}
add_action('enqueue_embed_scripts', __NAMESPACE__ . '\\embed_assets', 100);


/**
 * Replace default inline embed scripts to remove default share fn code and allow links to open in new tabs
 */
function print_embed_scripts() {
	?>
	<script type="text/javascript">
	 <?php readfile( Assets\asset_path('scripts/wp-embed-template.js') ); ?>
	</script>
	<?php
}
remove_action( 'embed_footer', 'print_embed_scripts' );
add_action( 'embed_footer', __NAMESPACE__ . '\\print_embed_scripts' );
