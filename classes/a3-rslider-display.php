<?php
class A3_Responsive_Slider_Display
{
	public static function a3_responsive_slider( $slider_id = 0 ) {

		$slider_data = get_post( $slider_id );
		if ( $slider_data == NULL ) return '';

		$have_slider_id = get_post_meta( $slider_id, '_a3_slider_id' , true );
		if ( $have_slider_id < 1 ) return '';


		$slider_settings =  get_post_meta( $slider_id, '_a3_slider_settings', true );
		$slider_template = get_post_meta( $slider_id, '_a3_slider_template' , true );

		$slide_items = A3_Responsive_Slider_Data::get_all_images_from_slider_client( $slider_id );

		$templateid = 'template1';

		$slider_template = 'template-1';

		global ${'a3_rslider_'.$templateid.'_dimensions_settings'};

		$dimensions_settings = ${'a3_rslider_'.$templateid.'_dimensions_settings'};

		return A3_Responsive_Slider_Display::dispay_slider( $slide_items, $slider_template, $dimensions_settings, $slider_settings );

	}

	public static function dispay_slider( $slide_items = array(), $slider_template= 'template-1', $dimensions_settings = array() , $slider_settings = array(), $rslider_custom_style = '', $rslider_inline_style = '', $description_html = '' ) {
		global $a3_rslider_template1_global_settings;

		$templateid = 'template1';

		$slider_template = 'template-1';

		global ${'a3_rslider_'.$templateid.'_dimensions_settings'};
		global ${'a3_rslider_'.$templateid.'_title_settings'};
		global ${'a3_rslider_'.$templateid.'_caption_settings'};
		global ${'a3_rslider_'.$templateid.'_readmore_settings'};

		// Detect the slider is viewing on Mobile, if True then Show Slider for Mobile
		if ( ${'a3_rslider_'.$templateid.'_global_settings'}['enable_slider_touch'] == 1 ) {
			require_once A3_RESPONSIVE_SLIDER_DIR . '/includes/mobile_detect.php';
			$device_detect = new A3_RSlider_Mobile_Detect();
			if ( $device_detect->isMobile() ) {
				$is_used_mobile_skin = false;

				require_once A3_RESPONSIVE_SLIDER_DIR . '/classes/a3-rslider-mobile-display.php';
				return A3_Responsive_Slider_Mobile_Display::mobile_dispay_slider( $slide_items, $is_used_mobile_skin , $slider_settings );
			}
		}

		// TEST MOBILE
		//$is_used_mobile_skin = false;
		//if ( ${'a3_rslider_'.$templateid.'_global_settings'}['is_used_mobile_skin'] == 1 ) $is_used_mobile_skin = true;
		//require_once A3_RESPONSIVE_SLIDER_DIR . '/classes/a3-rslider-mobile-display.php';
		//return A3_Responsive_Slider_Mobile_Display::mobile_dispay_slider( $slide_items, $is_used_mobile_skin , $slider_settings );

		if ( is_array( $dimensions_settings ) && count( $dimensions_settings ) > 0 ) {
			extract( $dimensions_settings );
		} else {
			extract( ${'a3_rslider_'.$templateid.'_dimensions_settings'} );
		}
		extract( ${'a3_rslider_'.$templateid.'_title_settings'} );
		extract( ${'a3_rslider_'.$templateid.'_caption_settings'} );
		extract( ${'a3_rslider_'.$templateid.'_readmore_settings'} );

		// Return empty if it does not have any slides
		if ( ! is_array( $slide_items ) || count( $slide_items ) < 1 ) return '';

		extract( $slider_settings );

		$caption_class = '> .cycle-caption-title .cycle-caption';
		$overlay_class = '> .cycle-caption-title .cycle-overlay';
		$caption_fx_out = 'fadeOut';
		$caption_fx_in = 'fadeIn';

		$unique_id = rand( 100, 1000 );

		$enable_slider_touch = ${'a3_rslider_'.$templateid.'_global_settings'}['enable_slider_touch'];

		// Find max height and width of max height for set all images
		$max_height = 0;
		$width_of_max_height = 0;
		/*foreach ( $slide_items as $item ) {

			$image_url = $item->img_url;
			$size = getimagesize( $image_url );
			$height_current = $size[1];

			if ( $height_current > $max_height ) {
				$max_height = $height_current;
				$width_of_max_height = $size[0];
			}
		}*/

		$slider_transition_data 		= A3_Responsive_Slider_Functions::get_slider_transition( $slider_transition_effect, $slider_settings );
		$fx 							= $slider_transition_data['fx'];
		$transition_attributes 			= $slider_transition_data['transition_attributes'];
		$timeout 						= $slider_transition_data['timeout'];
		$speed 							= $slider_transition_data['speed'];
		$delay 							= $slider_transition_data['delay'];

		$dynamic_tall = 'false';
		if ( $is_slider_tall_dynamic == 1 ) $dynamic_tall = 'container';

		$have_image_title = false;
		$have_image_caption = false;

		ob_start();
	?>
	<?php
	$lazy_load = '';
	$lazy_hidden = '';
	if ( ! is_admin() && function_exists( 'a3_lazy_load_enable' ) ) {
		$lazy_load = '-lazyload';
		$lazy_hidden = '<div class="a3-cycle-lazy-hidden lazy-hidden"></div>';
	}
	?>
    <div id="a3-rslider-container-<?php echo $unique_id; ?>" class="a3-rslider-container a3-rslider-<?php echo $slider_template; ?>" slider-id="<?php echo $unique_id; ?>" max-height="<?php echo $max_height; ?>" width-of-max-height="<?php echo $width_of_max_height; ?>" is-responsive="<?php echo $is_slider_responsive; ?>" is-tall-dynamic="<?php echo $is_slider_tall_dynamic; ?>" style=" <?php echo $rslider_custom_style; ?>" >
    	<?php echo $lazy_hidden;?>
    	<div style=" <?php echo $rslider_inline_style; ?>" id="a3-cycle-slideshow-<?php echo $unique_id; ?>" class="cycle-slideshow<?php echo $lazy_load;?> a3-cycle-slideshow <?php if ( $is_slider_tall_dynamic == 1 ) { ?>a3-cycle-slideshow-dynamic-tall<?php } ?>"
        	data-cycle-fx="<?php echo $fx; ?>"
            <?php echo $transition_attributes; ?>

        	data-cycle-timeout=<?php echo $timeout; ?>
            data-cycle-speed=<?php echo $speed; ?>
            data-cycle-delay=<?php echo $delay; ?>
            <?php if ( $enable_slider_touch == 1 ) { ?>
            data-cycle-swipe=true
            <?php } ?>

            data-cycle-prev="> .a3-cycle-controls .cycle-prev"
            data-cycle-next="> .a3-cycle-controls .cycle-next"
            data-cycle-pager="> .cycle-pager-container .cycle-pager-inside .cycle-pager"

            <?php if ( $is_slider_tall_dynamic == 0 ) { ?>
            data-cycle-center-vert=true
            <?php  } ?>
            data-cycle-auto-height=<?php echo $dynamic_tall; ?>
    		data-cycle-center-horz=true

            data-cycle-caption="<?php echo $caption_class; ?>"
            data-cycle-caption-template="{{name}}"
            data-cycle-caption-plugin="caption2"
            data-cycle-caption-fx-out="<?php echo $caption_fx_out; ?>"
            data-cycle-caption-fx-in="<?php echo $caption_fx_in; ?>"

            data-cycle-overlay="<?php echo $overlay_class; ?>"
			data-cycle-overlay-fx-out="<?php echo $caption_fx_out; ?>"
			data-cycle-overlay-fx-in="<?php echo $caption_fx_in; ?>"

            data-cycle-loader=true
        >

			<?php if ( $is_slider_tall_dynamic == 1 ) { ?>
				<?php foreach ( $slide_items as $item ) { ?>
	        		<?php if ( $item->is_video != 1 ) { ?>
						<?php
							$first_img = $item->img_url;
							$_size = getimagesize( $first_img );
						?>
			        	<div class="cycle-sentinel"><img class="cycle-sentinel" style="width:<?php echo $_size[0]; ?>px; max-height:<?php echo $_size[1]; ?>px;" src="<?php echo esc_attr( $item->img_url ); ?>"></div>
						<?php break; ?>
					<?php } ?>
				<?php } ?>
			<?php } ?>

        	<div class="a3-cycle-controls">
            	<span><a href="#" class="cycle-prev"><?php _e( 'Prev', 'a3_responsive_slider' ); ?></a></span>
                <span><a href="#" class="cycle-next"><?php _e( 'Next', 'a3_responsive_slider' ); ?></a></span>
                <span><a href="#" data-cycle-cmd="pause" data-cycle-context="#a3-cycle-slideshow-<?php echo $unique_id; ?>" onclick="return false;" class="cycle-pause" style=" <?php if ( $is_auto_start == 0 ) { echo 'display:none'; } ?>"><?php _e( 'Pause', 'a3_responsive_slider' ); ?></a></span>
                <span><a href="#" data-cycle-cmd="resume" data-cycle-context="#a3-cycle-slideshow-<?php echo $unique_id; ?>" onclick="return false;" class="cycle-play"  style=" <?php if ( $is_auto_start != 0 ) { echo 'display:none'; } ?>"><?php _e( 'Play', 'a3_responsive_slider' ); ?></a></span>
            </div>

            <?php
			// NOT FOR WIDGET & CARD TEMPLATE
			self::get_caption_title();
			?>

        	<div class="cycle-pager-container">
            	<div class="cycle-pager-inside">
            		<div class="cycle-pager-overlay"></div>
                	<div class="cycle-pager"></div>
                </div>
            </div>

		<?php foreach ( $slide_items as $item ) { ?>
		<?php
				if ( $item->is_video == 1 ) continue;
				if ( trim( $item->img_url ) == '' ) continue;

				$img_title = '';
				if ( trim( $item->img_title ) != '' ) {
					$have_image_title = true;
					if ( trim( $item->img_link ) != '' ) {
						if ( stristr( $item->img_link, 'http' ) === FALSE && stristr( $item->img_link, 'https' ) === FALSE )
							$item->img_link = 'http://' . $item->img_link;
						$img_title = '<div class="cycle-caption-text"><a href="'. trim( $item->img_link ) .'">'. trim( stripslashes( $item->img_title ) ) .'</a></div>';
					} else {
						$img_title = '<div class="cycle-caption-text">'.trim( stripslashes( $item->img_title ) ).'</div>';
					}
				}

				$read_more = '';
				if ( trim( $item->img_link ) != '' && $item->show_readmore == 1 ) {
					$read_more_class = 'a3-rslider-read-more-link';
					$read_more_text = $readmore_link_text;
					if ( $readmore_bt_type == 'button' ) {
						$read_more_class = 'a3-rslider-read-more-bt';
						$read_more_text = $readmore_bt_text;
					}
					$read_more_class = 'a3-rslider-read-more '. $read_more_class ;
					$read_more = esc_attr( '<a class="'.$read_more_class.'" href="'. trim( $item->img_link ). '">' . $read_more_text . '</a>' );
				}

				$img_description = '';
				if ( trim( $item->img_description ) != '' ) {
					$have_image_caption = true;
					$img_description = '<div class="cycle-description">' . A3_Responsive_Slider_Functions::limit_words( stripslashes( $item->img_description ), $caption_lenght, '...' ) . ' '. $read_more . '</div>';
				}
		?>

        	<img class="a3-rslider-image" src="<?php echo esc_attr( $item->img_url ); ?>" name="<?php echo esc_attr( $img_title ); ?>" title="" data-cycle-desc="<?php echo esc_attr( $img_description ); ?>"
            style="position:absolute; visibility:hidden; top:0; left:0; <?php if ( trim( $item->img_link ) != '' ) { echo 'cursor:pointer;'; } ?>"
            <?php
				if ( $fx == 'random' ) {
					echo A3_Responsive_Slider_Functions::get_transition_random( $slider_settings );
				}

				if ( trim( $item->img_link ) != '' ) {
					echo ' onclick="window.location=\''.esc_attr( trim( $item->img_link ) ).'\';" ';
				}
			?>
            />
        <?php } ?>
        </div>

        <?php echo $description_html; ?>

    </div>

    <?php
		$slider_output = ob_get_clean();

		$slider_output = str_replace( array("\r\n", "\r", "\n"), '', $slider_output );

		$script_settings = array(
			'fx'       => $fx,
			'caption2' => $have_image_caption,
			'swipe'    => ( $enable_slider_touch == 1 ) ? true : false,
			'video'    => false,
    	);
    	A3_Responsive_Slider_Hook_Filter::enqueue_frontend_script( $script_settings );
    	$slider_output = apply_filters( 'a3_lazy_load_images', $slider_output, false );

		return $slider_output;

	}

	public static function get_caption_title() {
	?>
    		<div class="cycle-caption-title">
				<div class="cycle-caption-container">
                	<div class="cycle-caption-bg"></div>
                    <div class="cycle-caption"></div>
                </div>
                <div class="cycle-overlay-container">
                	<div class="cycle-overlay-bg"></div>
					<div class="cycle-overlay"></div>
                </div>
        	</div>
    <?php
	}

}
?>
