<?php
class A3_Responsive_Slider_Hook_Filter
{

	public static function include_frontend_script() {
		global $wp_scripts;

		$_upload_dir = wp_upload_dir();

		$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

		wp_register_style( 'a3_responsive_slider_styles', A3_RESPONSIVE_SLIDER_CSS_URL . '/cycle.css' );

		if ( file_exists( $_upload_dir['basedir'] . '/sass/a3_responsive_slider'.$suffix.'.css' ) )
			wp_register_style( 'a3_rslider_template1', $_upload_dir['baseurl'] . '/sass/a3_responsive_slider'.$suffix.'.css' );

		wp_enqueue_script('jquery');
		wp_register_script( 'a3-cycle2-script', A3_RESPONSIVE_SLIDER_JS_URL . '/jquery.cycle2'. $suffix .'.js', array('jquery'), '2.1.2' );
		wp_register_script( 'a3-cycle2-center-script', A3_RESPONSIVE_SLIDER_EXTENSION_JS_URL . '/jquery.cycle2.center'. $suffix .'.js', array('jquery'), '2.1.2' );
		wp_register_script( 'a3-cycle2-caption2-script', A3_RESPONSIVE_SLIDER_EXTENSION_JS_URL . '/jquery.cycle2.caption2'. $suffix .'.js', array('jquery'), '2.1.2' );
		wp_register_script( 'a3-cycle2-swipe-script', A3_RESPONSIVE_SLIDER_EXTENSION_JS_URL . '/jquery.cycle2.swipe'. $suffix .'.js', array('jquery'), '2.1.2' );

		require_once A3_RESPONSIVE_SLIDER_DIR . '/includes/mobile_detect.php';
		$device_detect = new A3_RSlider_Mobile_Detect();
		if ( ! $device_detect->isMobile() ) {
			wp_register_script( 'a3-cycle2-flip-script', A3_RESPONSIVE_SLIDER_EXTENSION_JS_URL . '/jquery.cycle2.flip'. $suffix .'.js', array('jquery'), '2.1.2' );
			wp_register_script( 'a3-cycle2-scrollVert-script', A3_RESPONSIVE_SLIDER_EXTENSION_JS_URL . '/jquery.cycle2.scrollVert'. $suffix .'.js', array('jquery'), '2.1.2' );
			wp_register_script( 'a3-cycle2-shuffle-script', A3_RESPONSIVE_SLIDER_EXTENSION_JS_URL . '/jquery.cycle2.shuffle'. $suffix .'.js', array('jquery'), '2.1.2' );
			wp_register_script( 'a3-cycle2-tile-script', A3_RESPONSIVE_SLIDER_EXTENSION_JS_URL . '/jquery.cycle2.tile'. $suffix .'.js', array('jquery'), '2.1.2' );
			wp_register_script( 'a3-cycle2-ie-fade-script', A3_RESPONSIVE_SLIDER_EXTENSION_JS_URL . '/jquery.cycle2.ie-fade'. $suffix .'.js', array('jquery'), '2.1.2' );
			$wp_scripts->add_data( 'a3-cycle2-ie-fade-script', 'conditional', 'IE' );

			wp_register_script( 'a3-rslider-frontend', A3_RESPONSIVE_SLIDER_JS_URL . '/a3-rslider-frontend.js', array('jquery') );

		} else {
			wp_register_script( 'a3-rslider-frontend', A3_RESPONSIVE_SLIDER_JS_URL . '/a3-rslider-frontend-mobile.js', array('jquery') );
		}

		if ( is_admin() ) return;

		global $post;
		$our_shortcode = 'a3_responsive_slider';
		// Check if a3_responsive_slider shortcode is in the content
		if ( $post && has_shortcode( $post->post_content, $our_shortcode ) ) {
			preg_match_all( '/' . get_shortcode_regex() . '/s', $post->post_content, $matches, PREG_SET_ORDER );
			if ( ! empty( $matches ) && is_array( $matches ) && count( $matches ) > 0 ) {
				foreach ( $matches as $shortcode ) {
					if ( $our_shortcode === $shortcode[2] ) {
						$attr = shortcode_parse_atts( $shortcode[3] );
						$my_attr = shortcode_atts( array(
			 							'id' 				=> 0
									), $attr );
						$slider_id = $my_attr['id'];
						if ( $slider_id > 0 ) {
							$slider_data = get_post( $slider_id );
							if ( $slider_data == NULL ) return '';
							$have_slider_id = get_post_meta( $slider_id, '_a3_slider_id' , true );
							if ( $have_slider_id < 1 ) return '';

							$slider_settings =  get_post_meta( $slider_id, '_a3_slider_settings', true );
							$slider_template = 'template-1';

							extract( $slider_settings );
							$slider_transition_data 		= A3_Responsive_Slider_Functions::get_slider_transition( $slider_transition_effect, $slider_settings );
							$fx 							= $slider_transition_data['fx'];

							$templateid = 'template1';

							global ${'a3_rslider_'.$templateid.'_global_settings'};
							$enable_slider_touch = ${'a3_rslider_'.$templateid.'_global_settings'}['enable_slider_touch'];

							$script_settings = array(
								'fx'       => $fx,
								'caption2' => false,
								'swipe'    => ( $enable_slider_touch == 1 ) ? true : false,
								'video'    => false,
					    	);

					    	self::enqueue_frontend_script( $script_settings );
						}
					}
				}
			}
		}

	}

	public static function enqueue_frontend_script( $script_settings = array() ) {

		if ( count( $script_settings ) <= 0 || is_admin() ){
			$script_settings = array(
				'fx'       => 'fade',
				'caption2' => true,
				'swipe'    => true,
				'video'    => false,
	    	);
		}

		wp_enqueue_style( 'a3_responsive_slider_styles' );

		wp_enqueue_style( 'a3_rslider_template1' );

		wp_enqueue_script( 'a3-cycle2-script' );

		require_once A3_RESPONSIVE_SLIDER_DIR . '/includes/mobile_detect.php';
		$device_detect = new A3_RSlider_Mobile_Detect();
		if ( ! $device_detect->isMobile() ) {
			if ( in_array( $script_settings['fx'], array( 'random', 'flipHorz', 'flipVert' ) ) ) {
				wp_enqueue_script( 'a3-cycle2-flip-script' );
			}
			if ( in_array( $script_settings['fx'], array( 'random', 'scrollHorz', 'scrollVert' ) ) ) {
				wp_enqueue_script( 'a3-cycle2-scrollVert-script' );
			}
			if ( in_array( $script_settings['fx'], array( 'random', 'shuffle' ) ) ) {
				wp_enqueue_script( 'a3-cycle2-shuffle-script' );
			}
			if ( in_array( $script_settings['fx'], array( 'random', 'tileSlide', 'tileBlind' ) ) ) {
				wp_enqueue_script( 'a3-cycle2-tile-script' );
			}
			if ( in_array( $script_settings['fx'], array( 'random', 'fade', 'fadeout' ) ) ) {
				wp_enqueue_script( 'a3-cycle2-ie-fade-script' );
			}
		}

		wp_enqueue_script( 'a3-rslider-frontend' );

		wp_enqueue_script( 'a3-cycle2-center-script' );
		if ( $script_settings['caption2'] ) {
			wp_enqueue_script( 'a3-cycle2-caption2-script' );
		}
		if ( $device_detect->isMobile() && $script_settings['swipe'] ){
			wp_enqueue_script( 'a3-cycle2-swipe-script' );
		}
	}

	public static function add_google_fonts() {
		global $a3_responsive_slider_fonts_face;

		$google_fonts = array( );

		$templateid = 'template1';

			global ${'a3_rslider_'.$templateid.'_title_settings'};
			global ${'a3_rslider_'.$templateid.'_caption_settings'};
			global ${'a3_rslider_'.$templateid.'_readmore_settings'};
			global ${'a3_rslider_'.$templateid.'_shortcode_settings'};

			extract( ${'a3_rslider_'.$templateid.'_title_settings'} );
			extract( ${'a3_rslider_'.$templateid.'_caption_settings'} );

			$google_fonts[] = $title_font['face'];
			$google_fonts[] = $caption_font['face'];

				extract( ${'a3_rslider_'.$templateid.'_readmore_settings'} );
				$google_fonts[] = $readmore_link_font['face'];
				$google_fonts[] = $readmore_bt_font['face'];

				extract( ${'a3_rslider_'.$templateid.'_shortcode_settings'} );
				$google_fonts[] = $shortcode_description_font['face'];


		if ( count( $google_fonts ) > 0 ) $a3_responsive_slider_fonts_face->generate_google_webfonts( $google_fonts );
	}

	public static function include_customized_style() {
		include( A3_RESPONSIVE_SLIDER_DIR. '/includes/customized_style.php' );
	}

	public static function include_admin_add_script() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_style('thickbox');

		wp_enqueue_script("jquery-ui-sortable");
		wp_enqueue_script("jquery-ui-draggable");

		wp_enqueue_style( 'galleries-style', A3_RESPONSIVE_SLIDER_CSS_URL.'/admin_slider.css' );
		wp_enqueue_script( 'galleries-script', A3_RESPONSIVE_SLIDER_JS_URL.'/admin_slider.js' );
	}

	public static function a3_wp_admin() {
		wp_enqueue_style( 'a3rev-wp-admin-style', A3_RESPONSIVE_SLIDER_CSS_URL . '/a3_wp_admin.css' );
	}

	public static function admin_sidebar_menu_css() {
		wp_enqueue_style( 'a3rev-responsive-slider-admin-sidebar-menu-style', A3_RESPONSIVE_SLIDER_CSS_URL . '/admin_sidebar_menu.css' );
	}

	public static function plugin_extension() {
		$html = '';
		$html .= '<a href="http://a3rev.com/shop/" target="_blank" style="float:right;margin-top:5px; margin-left:10px; clear:right;" ><div class="a3-plugin-ui-icon a3-plugin-ui-a3-rev-logo"></div></a>';
		$html .= '<h3>'.__('Thanks for choosing to install the a3 Responsive Slider Lite.', 'a3_responsive_slider').'</h3>';
		$html .= '<h3>'.__('What is the Yellow border sections about?', 'a3_responsive_slider').'</h3>';
		$html .= '<p>'.__('Inside the Yellow border you will see the settings for the a3 Responsive Slider Pro version plugin. You can see the settings but they are not active.', 'a3_responsive_slider' ).'</p>';

		$html .= '<h3 style="margin-bottom:5px;">* <a href="'.A3_RESPONSIVE_SLIDER_PRO_VERSION_URI.'" target="_blank">'.__('a3 Responsive Slider Pro', 'a3_responsive_slider').'</a></h3>';
		$html .= '<p>';
		$html .= '* '.__('Activates Youtube Video Slides.', 'a3_responsive_slider' ).'<br />';
		$html .= '* '.__('Activates Ken Burns transition Effect.', 'a3_responsive_slider' ).'<br />';
		$html .= '* '.__('Activates the 2nd custom Slider Skin.', 'a3_responsive_slider' ).'<br />';
		$html .= '* '.__('Activates the custom Card Skin.', 'a3_responsive_slider' ).'<br />';
		$html .= '* '.__('Activates the custom Widget Skin.', 'a3_responsive_slider' ).'<br />';
		$html .= '* '.__('Activates the custom Touch Mobile Skin.', 'a3_responsive_slider' ).'<br />';
		$html .= '* '.__('Access to the plugins a3rev support forum.', 'a3_responsive_slider' );
		$html .= '</p>';

		$html .= '<p>'.__("If you are trailing the Pro version must:", 'a3_responsive_slider').'<br />';
		$html .= '<ul style="padding-left:10px;">';
		$html .= '<li>1. '.__('DEACTIVATE the Lite BEFORE installing and activating another version.', 'a3_responsive_slider').'</li>';
		$html .= '<li>2. '.__("If you don't you will get a FATAL ERROR.", 'a3_responsive_slider').'</li>';
		$html .= '<li>3. '.__('All data - sliders, settings and activations will be present in the newly activated version.', 'a3_responsive_slider').'</li>';
		$html .= '<li>4. '.__('WARNING - If you DELETE this plugin BEFORE you activate another version of the slider, all slider settings will be lost.', 'a3_responsive_slider').'</li>';
		$html .= '</ul>';
		$html .= '</p>';

		$html .= '<h3>'.__('More a3rev Free WordPress plugins', 'a3_responsive_slider').'</h3>';
		$html .= '<p>';
		$html .= '<ul style="padding-left:10px;">';
		$html .= '<li>* <a href="https://wordpress.org/plugins/a3-lazy-load/" target="_blank">'.__('a3 Lazy Load', 'a3_responsive_slider').'</a> &nbsp;&nbsp;&nbsp; <sup>*</sup>'.__( 'New Plugin' , 'a3_responsive_slider' ).'</li>';
		$html .= '<li>* <a href="https://wordpress.org/plugins/a3-portfolio/" target="_blank">'.__('a3 Portfolio', 'a3_responsive_slider').'</a> &nbsp;&nbsp;&nbsp; <sup>*</sup>'.__( 'New Plugin' , 'a3_responsive_slider' ).'</li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/wp-email-template/" target="_blank">'.__('WP Email Template', 'a3_responsive_slider').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/contact-us-page-contact-people/" target="_blank">'.__('Contact Us Page - Contact People', 'a3_responsive_slider').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/page-views-count/" target="_blank">'.__('Page View Count', 'a3_responsive_slider').'</a></li>';
		$html .= '</ul>';
		$html .= '</p>';


		return $html;
	}

	public static function plugin_extra_links($links, $plugin_name) {
		if ( $plugin_name != A3_RESPONSIVE_SLIDER_NAME) {
			return $links;
		}
		$links[] = '<a href="http://docs.a3rev.com/user-guides/plugins-extensions/wordpress/a3-responsive-slider/" target="_blank">'.__('Documentation', 'a3_responsive_slider').'</a>';
		$links[] = '<a href="https://a3rev.com/forums/forum/wordpress-plugins/a3-responsive-slider/" target="_blank">'.__('Support', 'a3_responsive_slider').'</a>';
		return $links;
	}

}
?>
