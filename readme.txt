=== a3 Responsive Slider ===
Contributors: a3rev, A3 Revolution Software Development team
Tags: responsive slider, wordpress image slider, responsive image slider, image gallery
Requires at least: 3.8
Tested up to: 3.9.1
Stable tag: 1.0.0.3
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

A robust and versatile responsive image slider for WordPress.
  
== Description ==

There are hundreds of image sliders for WordPress. We know because we have used a lot and none have ever completely been what we wanted. We built our own because we wanted a versatile and robust responsive slider that we could use in our client website work and more importantly that our clients could easily use on their site.

= Features =

* Fully mobile responsive.
* Touch Swipe support in mobiles.
* Images of any size - scaled to show perfectly no matter what size is uploaded.
* WordPress taxonomy. Manage Sliders (like posts). Folders (like categories)
* Fully Customizable Slider Skin. No coding required.
* Add sliders by Widget.
* Embed sliders by shortcode button on every post, custom post type, pages.
* Slider Shortcode alignment (just like WordPress images)
* Slider Shortcode Dimension settings (Over-Ride Skin Dimension setting)
* Shortcode tracking. See at a glance where each slider is embed by shortcode.
* Remove sliders embedded by shortcode from the Slider Embed tab (removes the shortcode)
* Slider images uploaded to WordPress Media Library. 
* There are 8 different transition effects.
* Transition effects set on each slider.
* Extensively tested on live client sites before release 

= WordPress App style admin interface =

Like WordPress we believe that a3rev plugin users should be able to configure and tweak our plugins settings while on the go, right from their mobile or tablet.  

* 100% compatible with WordPress v3.8.0 admin interface.
* Admin app interface is fully mobile and tablet responsive.

= Lite & Pro Version Admin Interface =

* All a3rev Lite Version plugins have exactly the same admin interface as the Pro Version. 
* Pro Version features and settings are inside of Yellow borders. 
* The Pro Version settings can be set, but they do not save and are not applied to the front end.
* Upgrading to the Pro Version all setting made in the Lite are saved.

= Lite Version Support =

* Lite Version user please post support requests to the plugins WordPress Support forum. 
* We do not watch that forum and rarely visit it. 
* Pro Version Licence gives 'write' access to the a3rev support forum and priority support as part of the License.

>= Premium Support = 
>
>The a3rev team does not provide support for the a3 Responsive Slider plugin on the WordPress.org forums. One on one developer support is available via the plugins a3rev [support forum](https://a3rev.com/forums/forum/wordpress-plugins/a3-responsive-slider/) to people who have purchased a [a3 Responsive Slider Pro](http://a3rev.com/shop/a3-responsive-slider/) plugin Lifetime License. 
>
>The Pro Version has lots of extra features that coupled with developer support might be well worth your investment!

= The Pro Version Additional Features =

* Industry leading - [Full Pro Version](http://a3rev.com/shop/a3-responsive-slider/).
* Immediate access to support from developers on the plugins [a3rev support forum](https://a3rev.com/forums/forum/wordpress-plugins/a3-responsive-slider/).
* Add Youtube Videos to Sliders
* Ken Burns transition Effect
* A 2nd fully customizable Slider Skin.
* Fully customizable Blog Card skin. 
* Fully customizable Widget Skin.
* Fully Customizable Touch Mobile Skin.
* Select Skins to apply to slider from shortcode pop-up
* Select Skins to apply to slider from Widget.
* Set Touch Mobile Skin to auto apply in mobiles for Slider Skin, Widget Skin and card Skin.
* Immediate access to the plugins a3rev support forum.

= Localization =

If you do a translation for your site please send it to us and we'll include it in the plugins language folder and credit you here with the translation and a link to your site.

* English (default) - always included.
*.po file (a3_responsive_slider.po) in languages folder for translations.
* [Go here](http://a3rev.com/contact-us-page/) to send your translation files to us.

= Plugin Resources =

[PRO Version Free Trial](http://a3rev.com/shop/a3-responsive-slider/)

== Installation ==

= Minimum Requirements =

* WordPress 3.8.0
* PHP version 5.2.4 or greater
* MySQL version 5.0 or greater
 
= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don't even need to leave your web browser. To do an automatic install of a3 Responsive Slider, log in to your WordPress admin panel, navigate to the Plugins menu and click Add New. 

In the search field type "a3 responsive slider" and click Search Plugins. Once you have found our plugin you can install it by simply clicking Install Now. After clicking that link you will be asked if you are sure you want to install the plugin. Click yes and WordPress will automatically complete the installation. 

= Manual installation =

The manual installation method involves down loading our plugin and uploading it to your web server via your favourite FTP application.

1. Download the plugin file to your computer and unzip it
2. Using an FTP program, or your hosting control panel, upload the unzipped plugin folder to your WordPress installations wp-content/plugins/ directory.
3. Activate the plugin from the Plugins menu within the WordPress admin.

== Screenshots ==

1. Go to Responsive Slider menu - Slider Skins and create your customize your skin style.
2. Go to Responsive Slider menu - Add New menu and add new slider.
3. View and manage all sliders from the All Sliders menu, just like posts.
4. Add sliders by the a3 Responsive slider widget.
5. Add sliders by shortcode from the Sliders button above the WordPress text editor.

 
== Usage ==

1. Install and activate the plugin

2. Go to a3 Responsive Slider menu on your wp-admin dashboard.

3. Go to Slider Skins and create your own slider skin style.

4. Add New Slider - create your first slider

5. Go to Widgets - find a3 responsive slider widget and apply.

6. Use the Sliders button on post and page text editor to add slider by shortcode.
 
7. Enjoy.


== Changelog ==

= 1.0.0.3 - 2014/07/19 =
* Fix - Changed Mobile_Detect Lib class name to A3_RSlider_Mobile_Detect to prevent conflict with other plugins that use the global class name.
* Credit - Thanks to Flemming Andersen for the access to his site to find and fix the class name conflict.

= 1.0.0.2 - 2014/06/21 = 
* Tweak - Updated chosen js script to latest version 1.1.0 on the a3rev Plugin Framework 
* Tweak - Added support for placeholder feature for input, email , password , text area types 
* Tweak - Updated plugins description text and admin panel yellow sidebar text.

= 1.0.0.1 - 2014/05/24 =
* Tweak - Changed add_filter( 'gettext', array( $this, 'change_button_text' ), null, 2 ); to add_filter( 'gettext', array( $this, 'change_button_text' ), null, 3 );
* Tweak - Update change_button_text() function from ( $original == 'Insert into Post' ) to ( is_admin() && $original === 'Insert into Post' )
* Tweak - Checked and updated for full compatibility with WordPress version 3.9.1
* Fix - Code tweaks to fix a3 Plugins Framework conflict with WP e-Commerce tax rates.

= 1.0.0 - 2014/05/05 =
* First Release of Lite Version.

== Upgrade Notification ==

= 1.0.0.3 =
Update your plugin now for mobile detect class name conflict bug fix

= 1.0.0.2 =
Update now for 2 important framework code tweaks to keep you plugin in tip top running order.

= 1.0.0.1 =
Update now for full compatibility with WordPress 3.9.1 with some a3rev Plugin Framework code tweaks.

= 1.0.0 =
First release
