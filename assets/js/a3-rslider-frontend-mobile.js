(function($) {
$(function(){
	if($.fn.lazyLoadXT !== undefined) {
		function removeLazyHidden(){
			var myVar = setInterval( function(){
				$(".cycle-pre-initialized").find('div.a3-cycle-lazy-hidden').remove();
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
