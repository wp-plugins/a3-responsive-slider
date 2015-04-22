<?php
/*
Plugin Name: a3 Responsive Slider
Description: Create unlimited robust and flexible responsive image sliders. Insert them by shortcode from the text editor on any post, custom post type or page or add widget. Auto Mobile touch swipe and a fully customizable skin.
Version: 1.1.5
Author: a3 Revolution
Author URI: http://www.a3rev.com/
Requires at least: 3.8
Tested up to: 4.1
License: GPLv2 or later
	Copyright © 2011 A3 Revolution Software Development team
	A3 Revolution Software Development team
	admin@a3rev.com
	PO Box 1170
	Gympie 4570
	QLD Australia
*/
?>
<?php
define('A3_RESPONSIVE_SLIDER_FILE_PATH', dirname(__FILE__));
define('A3_RESPONSIVE_SLIDER_DIR_NAME', basename(A3_RESPONSIVE_SLIDER_FILE_PATH));
define('A3_RESPONSIVE_SLIDER_FOLDER', dirname(plugin_basename(__FILE__)));
define('A3_RESPONSIVE_SLIDER_NAME', plugin_basename(__FILE__));
define('A3_RESPONSIVE_SLIDER_URL', untrailingslashit(plugins_url('/', __FILE__)));
define('A3_RESPONSIVE_SLIDER_DIR', WP_CONTENT_DIR . '/plugins/' . A3_RESPONSIVE_SLIDER_FOLDER);
define('A3_RESPONSIVE_SLIDER_JS_URL', A3_RESPONSIVE_SLIDER_URL . '/assets/js');
define('A3_RESPONSIVE_SLIDER_EXTENSION_JS_URL', A3_RESPONSIVE_SLIDER_URL . '/assets/js/cycle2-extensions');
define('A3_RESPONSIVE_SLIDER_CSS_URL', A3_RESPONSIVE_SLIDER_URL . '/assets/css');
define('A3_RESPONSIVE_SLIDER_IMAGES_URL', A3_RESPONSIVE_SLIDER_URL . '/assets/images');
if (!defined("A3_RESPONSIVE_SLIDER_PRO_VERSION_URI")) define("A3_RESPONSIVE_SLIDER_PRO_VERSION_URI", "http://a3rev.com/shop/a3-responsive-slider/");

include ('admin/admin-ui.php');
include ('admin/admin-interface.php');

include ('classes/a3-rslider-functions.php');
include ('admin/classes/a3-rslider-edit.php');

include ('admin/admin-pages/admin-slider-skins-page.php');
include ('admin/admin-pages/admin-card-skin-page.php');
include ('admin/admin-pages/admin-templates_widget-page.php');
include ('admin/admin-pages/admin-templates_mobile-page.php');

include ('admin/admin-init.php');
include ('admin/less/sass.php');

include ('classes/data/a3-rslider-data.php');
include ('classes/a3-rslider-custom-post.php');
include ('classes/a3-rslider-duplicate.php');
include ('classes/a3-rslider-hook-filter.php');
include ('classes/a3-rslider-display.php');
include ('shortcodes/class-rslider-shortcodes.php');
include ('widgets/class-rslider-widgets.php');

include ('preview/a3-rslider-preview.php');

include ('admin/a3-rslider-admin.php');

register_activation_hook(__FILE__, 'a3_rslider_activated');
?>
