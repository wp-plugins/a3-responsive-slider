<?php
class A3_Responsive_Slider_Preview
{
	
	public static function a3_slider_preview() {
		check_ajax_referer( 'a3-slider-preview', 'security' );
		$request = $_REQUEST;
		//var_dump( $request );
		
		$slider_html = '';
		if ( isset( $request['view'] ) && $request['view'] == 'list' ) {
			if ( isset( $request['slider_id'] ) ) {
				$slider_html = A3_Responsive_Slider_Display::a3_responsive_slider( $request['slider_id'] );
			}
		} else {
			extract( $request );
			if ( is_array( $photo_galleries ) && count( $photo_galleries ) > 0 ) {
				if ( ! isset( $slider_settings['is_auto_start'] ) ) $slider_settings['is_auto_start'] = 0;
				if ( ! isset( $slider_settings['data-cycle-tile-vertical'] ) ) $slider_settings['data-cycle-tile-vertical'] = 'false';
				if ( ! isset( $slider_settings['is_2d_effects'] ) ) $slider_settings['is_2d_effects'] = 1;
				if ( ! isset( $slider_settings['kb_is_auto_start'] ) ) $slider_settings['kb_is_auto_start'] = 0;
				
				// Youtube support
				if ( ! isset( $slider_settings['support_youtube_videos'] ) ) $slider_settings['support_youtube_videos'] = 0;
				if ( ! isset( $slider_settings['is_yt_auto_start'] ) ) $slider_settings['is_yt_auto_start'] = 'false';
				if ( ! isset( $slider_settings['is_yt_auto_stop'] ) ) $slider_settings['is_yt_auto_stop'] = 'false';
			
				$slide_items =array();
				foreach ( $photo_galleries['image'] as $key => $images ) {
					if ( trim( $images ) != '' ) {
						$my_item = new stdClass();
						$my_item->img_url = trim( $images );
						$my_item->video_url = '';
						$my_item->is_video = 0;
						$my_item->img_title = trim( $photo_galleries['title'][$key] );
						$my_item->img_description = trim( $photo_galleries['text'][$key] );
						$my_item->img_link = trim( $photo_galleries['link'][$key] );
						$slide_items[] = $my_item;
					}
				}
				$slider_html = A3_Responsive_Slider_Display::dispay_slider( $slide_items, $slider_template, array(), $slider_settings );
			}
		}
		
		echo '<div style="text-align:center; padding:10px 20px 20px;">';
		echo $slider_html;
		echo '</div>';
?>
		<script>
		(function($) {
		$(document).ready(function() {
			$('.cycle-slideshow').cycle();
		});
		})(jQuery);
		</script>
<?php
			
		die();
	}	

}
?>