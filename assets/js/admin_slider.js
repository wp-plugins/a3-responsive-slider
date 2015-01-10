// JavaScript Document
jQuery(document).ready(function() {
	
	jQuery('.show_hide').click(function(){
		if(jQuery('.galleries_collapse_section').is(':hidden')){
			jQuery(this).removeClass('show_hide_down');
			jQuery(this).addClass('show_hide_up');
			jQuery('.galleries_collapse_section').show();
		}
		else{
			jQuery(this).removeClass('show_hide_up');
			jQuery(this).addClass('show_hide_down');
			jQuery('.galleries_collapse_section').hide();
		}
	});
		
	jQuery("#galleries-table").sortable({
		axis: 'y',
		containment: 'parent',
		handle: '.galleries-move',
		items: 'tr',
		cursor: 'crosshair'  
	});
	
	
	function randomStringCycle() {
		var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
		var string_length = 8;
		var randomstring = '';
		for (var i=0; i<string_length; i++) {
			var rnum = Math.floor(Math.random() * chars.length);
			randomstring += chars.substring(rnum,rnum+1);
		}
		return randomstring;
	}
	function add_new_image_row(){
		randomStringCycle();
		var rowCount = jQuery('#galleries-table tr').length;
		rowCount += '_'+randomStringCycle();
		var row = '<tr><td><div class="image-wrapper"><a class="browse_upload galleries-image-'+rowCount+'-container" alt="galleries-image-'+rowCount+'" title="Add an Image" href="#"><span class="icon-slider-add-new-image"></span></a><input type="hidden" name="photo_galleries[image]['+rowCount+']" value="" id="galleries-image-'+rowCount+'-hidden"></div><div class="data-wrapper"><div class="title-wrapper"><label for="galleries-title-'+rowCount+'">Title</label><input type="text" name="photo_galleries[title]['+rowCount+']" value="" id="galleries-title-'+rowCount+'" class="galleries-title"></div><div class="link-wrapper"><label for="galleries-link-">Link URL</label><input type="text" name="photo_galleries[link]['+rowCount+']" value="" id="galleries-link-'+rowCount+'" class="galleries-link"></div><div class="text-wrapper"><label for="galleries-text-'+rowCount+'">Caption</label><textarea id="galleries-text-'+rowCount+'" name="photo_galleries[text]['+rowCount+']" class="galleries-text"></textarea></div></div></td><td><a href="#" class="icon-move galleries-move" title="Reorder Galleries Items"><span></span></a> <a href="#" class="icon-delete galleries-delete-cycle" title="Delete Item"><span></span></a></td></tr>';
		jQuery("#galleries-table tbody").append(row);
	}
	
	function add_new_yt_row(){
		randomStringCycle();
		var rowCount = jQuery('#galleries-table tr').length;
		rowCount += '_'+randomStringCycle();
		var row = '<tr class="galleries-yt-row"><td><div class="image-wrapper"><label class="galleries-add-new-yt-container" for="galleries-youtube-url-'+rowCount+'"><span class="icon-slider-add-new-yt"></span></label><input type="hidden" name="photo_galleries[image]['+rowCount+']" value="" id="galleries-image-'+rowCount+'-hidden"></div><div class="data-wrapper"><div class="title-wrapper"><label for="galleries-title-'+rowCount+'">Title</label><input type="text" name="photo_galleries[title]['+rowCount+']" value="" id="galleries-title-'+rowCount+'" class="galleries-title"></div><div class="link-wrapper"><label for="galleries-youtube-url-'+rowCount+'">Youtube Code</label><input type="text" name="photo_galleries[video_url]['+rowCount+']" value="" id="galleries-youtube-url-'+rowCount+'" class="galleries-link"><span class="description" style="white-space:nowrap">Example: RBumgq5yVrA</span></div><div class="link-wrapper"><label for="galleries-link-'+rowCount+'">Link URL</label><input type="text" name="photo_galleries[link]['+rowCount+']" value="" id="galleries-link-'+rowCount+'" class="galleries-link"></div><div class="text-wrapper"><label for="galleries-text-'+rowCount+'">Caption</label><textarea id="galleries-text-'+rowCount+'" name="photo_galleries[text]['+rowCount+']" class="galleries-text"></textarea></div></div></td><td><a href="#" class="icon-move galleries-move" title="Reorder Galleries Items"><span></span></a> <a href="#" class="icon-delete galleries-delete-cycle" title="Delete Item"><span></span></a></td></tr>';
		jQuery("#galleries-table tbody").append(row);
	}
	
	jQuery('.add_new_image_row').click(function(){
		add_new_image_row();
		return false;
	});
	
	jQuery('.add_new_yt_row').click(function(){
		add_new_yt_row();
		return false;
	});
	
	jQuery(document).on( 'click', '#galleries-table .galleries-delete-cycle', function(){
		if(confirm("Do you really want to delete this entry?")){
			jQuery(this).parents('tr').fadeOut(400, function(){
				jQuery(this).remove();
			});
		}
		return false;
	});
	
	var formfield = "";
    jQuery(document).on('click', '.browse_upload' ,function() {
    	formfield = jQuery(this).attr('alt');
        tb_show('', 'media-upload.php?type=image&TB_iframe=true');
		return false;
    });
	
    window.send_to_editor = function(html) {
    	hrefurl = jQuery('img','<div>' + html + '</div>').attr('src');
        if( hrefurl.length == 0) {
        	hrefurl = jQuery(html).attr('href'); // We do this to get Links like PDF's
        }
		jQuery('.' + formfield + '-container').html( '<img alt="Add an Image" src="'+hrefurl+'" class="galleries-image">');
		jQuery('#' + formfield + '-hidden').val(hrefurl);
      	tb_remove();
    }
		
});

(function($) {
$(document).ready(function() {
	if ( $("input.is_auto_start:checked").val() == '1') {
		$('.is_auto_start_on').show();
	} else {
		$('.is_auto_start_on').hide();
	}
	
	if ( $("input.kb_is_auto_start:checked").val() == '1') {
		$('.kb_is_auto_start_on').show();
	} else {
		$('.kb_is_auto_start_on').hide();
	}
	
	if ( $("input.is_2d_effects:checked").val() == '0') {
		$('.ken_burns_container').css( {'visibility': 'visible', 'height' : '0', 'overflow' : 'hidden'} );
		$('.2d_effects_container').css( {'visibility': 'hidden', 'height' : '0', 'overflow' : 'hidden'} );
	} else {
		$('.ken_burns_container').css( {'visibility': 'hidden', 'height' : '0', 'overflow' : 'hidden'} );
		$('.2d_effects_container').css( {'visibility': 'visible', 'height' : '0', 'overflow' : 'hidden'} );
	}
	
	if ( $("input.support_youtube_videos:checked").val() == '1') {
		$('#support_youtube_videos_on').css( {'visibility': 'visible', 'height' : '0', 'overflow' : 'hidden'} );
		$('#support_youtube_videos_off').css( {'visibility': 'hidden', 'height' : '0', 'overflow' : 'hidden'} );
		$('.add_new_yt_row').show();
	} else {
		$('#support_youtube_videos_on').css( {'visibility': 'hidden', 'height' : '0', 'overflow' : 'hidden'} );
		$('#support_youtube_videos_off').css( {'visibility': 'visible', 'height' : '0', 'overflow' : 'hidden'} );
		$('.add_new_yt_row').hide();
	}
	
	$(document).on( "a3rev-ui-onoff_checkbox-switch", '.is_auto_start', function( event, value, status ) {
		if ( status == 'true' ) {
			$(".is_auto_start_on").slideDown();
		} else {
			$(".is_auto_start_on").slideUp();
		}
	});
	
	$(document).on( "a3rev-ui-onoff_checkbox-switch", '.kb_is_auto_start', function( event, value, status ) {
		if ( status == 'true' ) {
			$(".kb_is_auto_start_on").slideDown();
		} else {
			$(".kb_is_auto_start_on").slideUp();
		}
	});
	
	$(document).on( "a3rev-ui-onoff_checkbox-switch", '.is_2d_effects', function( event, value, status ) {
		$('.ken_burns_container').hide().css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
		$('.2d_effects_container').hide().css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
		if ( status == 'true' ) {
			$(".ken_burns_container").slideDown();
			$(".2d_effects_container").slideUp();
		} else {
			$(".ken_burns_container").slideUp();
			$(".2d_effects_container").slideDown();
		}
	});
	
	$(document).on( "a3rev-ui-onoff_checkbox-switch", '.support_youtube_videos', function( event, value, status ) {
		$('#support_youtube_videos_on').hide().css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
		$('#support_youtube_videos_off').hide().css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
		if ( status == 'true' ) {
			$("#support_youtube_videos_on").slideDown();
			$("#support_youtube_videos_off").slideUp();
			$('.add_new_yt_row').slideDown();
			$(document).find('.galleries-yt-row').slideDown();
		} else {
			$("#support_youtube_videos_on").slideUp();
			$("#support_youtube_videos_off").slideDown();
			$('.add_new_yt_row').slideUp();
			$(document).find('.galleries-yt-row').slideUp();
		}
	});
	
	/* Apply Sub tab selected script */
	$('div.tabs_section ul.nav-tab-wrapper li a:eq(0)').addClass('current');
	$('div.tabs_section .tab_content:gt(0)').css( {'visibility': 'hidden', 'height' : '0', 'overflow' : 'inherit'} );
	$('div.tabs_section ul.nav-tab-wrapper li a:gt(0)').each(function(){
		if( $(this).attr('class') == 'current') {
			$('div.tabs_section ul.nav-tab-wrapper li a').removeClass('current');
			$(this).addClass('current');
			$('div.tabs_section .tab_content').css( {'visibility': 'hidden', 'height' : '0', 'overflow' : 'inherit'} );
			$('div.tabs_section ' + $(this).attr('href') ).css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
		}
	});
	$('div.tabs_section ul.nav-tab-wrapper li a').click(function(){
		$('div.tabs_section .tab_content').hide().css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
		var clicked = $(this);
		var section = clicked.closest('.tabs_section');
		var target  = clicked.attr('href');
		if ( target == '#image_transition' ) {
			if ( $("input.is_2d_effects:checked").val() == '0') {
				$('.ken_burns_container').css( {'height' : 'auto', 'overflow' : 'inherit'} );
				$('.2d_effects_container').css( {'height' : '0', 'overflow' : 'inherit'} );
			} else {
				$('.ken_burns_container').css( {'height' : '0', 'overflow' : 'inherit'} );
				$('.2d_effects_container').css( {'height' : 'auto', 'overflow' : 'inherit'} );
			}
			if ( $("input.support_youtube_videos:checked").val() == '1') {
				$('#support_youtube_videos_on').css( {'height' : 'auto', 'overflow' : 'inherit'} );
				$('#support_youtube_videos_off').css( {'height' : '0', 'overflow' : 'hidden'} );
			} else {
				$('#support_youtube_videos_on').css( {'height' : '0', 'overflow' : 'hidden'} );
				$('#support_youtube_videos_off').css( {'height' : 'auto', 'overflow' : 'inherit'} );
			}
		}
	
		section.find('a').removeClass('current');
	
		if ( section.find('.tab_content:visible').size() > 0 ) {
			section.find('.tab_content:visible').fadeOut( 100, function() {
				section.find( target ).fadeIn('fast');
			});
		} else {
			section.find( target ).fadeIn('fast');
		}
	
		clicked.addClass('current');
		$('.last_tab').val( target );
	
		return false;
	});
	
});
})(jQuery);