(function($) {
$(function(){

	a3_RSlider_Frontend = {

		setHeightProportional: function () {
			$(document).find( '.a3-rslider-container' ).each( function() {
				var slider_id = $(this).attr( 'slider-id' );
				var max_height = $(this).attr( 'max-height' );
				var width_of_max_height = $(this).attr( 'width-of-max-height' );
				var is_responsive = $(this).attr( 'is-responsive' );
				var is_tall_dynamic = $(this).attr( 'is-tall-dynamic' );
				if ( is_responsive == '1' && is_tall_dynamic == '1' ) {

					var a3_rslider_container_width = $(this).width();
					var width_of_max_height = parseInt( width_of_max_height );
					var a3_rslider_container_height = parseInt( max_height );
					if( width_of_max_height > a3_rslider_container_width ) {
						var ratio = width_of_max_height / a3_rslider_container_width;
						a3_rslider_container_height = a3_rslider_container_height / ratio;
					}
					$(this).find( '.a3-cycle-slideshow' ).css({ height: a3_rslider_container_height });

				}
			});
		},

		clickPauseResumEvent: function () {
			$(document).on( 'cycle-paused', '.a3-cycle-slideshow', function( event, opts ) {
				$(this).find( '.cycle-pause' ).hide();
				$(this).find( '.cycle-play' ).show();
			});
			$(document).on( 'cycle-resumed', '.a3-cycle-slideshow', function( event, opts ) {
				$(this).find( '.cycle-pause' ).show();
				$(this).find( '.cycle-play' ).hide();
			});
		}
	}

	a3_RSlider_Frontend.clickPauseResumEvent();

	//a3_RSlider_Frontend.setHeightProportional();
	$( window ).resize(function() {
		//a3_RSlider_Frontend.setHeightProportional();
	});

	if($.fn.lazyLoadXT !== undefined) {
		function removeLazyHidden(){
			var myVar = setInterval( function(){
				$(".cycle-pre-initialized").find('.a3-cycle-lazy-hidden').remove();
				clearInterval(myVar);
			}, 700 );
		}
		$(".a3-cycle-slideshow img.a3-rslider-image").on('lazyload', function(){
			$(this).parents('.a3-cycle-slideshow').on( 'cycle-pre-initialize', function( event, opts ) {
				$(this).parent().addClass('cycle-pre-initialized');
				removeLazyHidden();
			});
			$(this).parents('.a3-cycle-slideshow').cycle();
		}).lazyLoadXT();
	}
});
})(jQuery);
