<?php
/* "Copyright 2012 A3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */
/**
 * Slider Addnew Edit
 *
 * Table Of Contents
 *
 * admin_screen_add_edit()
 */
class A3_Responsive_Slider_Edit
{
	
	public static function slider_form_action() {
		if ( ! is_admin() ) return ;
		
		if ( isset( $_POST['bt_create'] ) || isset( $_POST['bt_update'] ) ) {
			$slider_settings = $_POST['slider_settings'];
			if ( ! isset( $slider_settings['is_auto_start'] ) ) $slider_settings['is_auto_start'] = 0;
			if ( ! isset( $slider_settings['data-cycle-tile-vertical'] ) ) $slider_settings['data-cycle-tile-vertical'] = 'false';
			if ( ! isset( $slider_settings['is_2d_effects'] ) ) $slider_settings['is_2d_effects'] = 1;
			if ( ! isset( $slider_settings['kb_is_auto_start'] ) ) $slider_settings['kb_is_auto_start'] = 0;
			
			// Youtube support
			if ( ! isset( $slider_settings['support_youtube_videos'] ) ) $slider_settings['support_youtube_videos'] = 0;
			if ( ! isset( $slider_settings['is_yt_auto_start'] ) ) $slider_settings['is_yt_auto_start'] = 'false';
			if ( ! isset( $slider_settings['is_yt_auto_stop'] ) ) $slider_settings['is_yt_auto_stop'] = 'false';
			
			$slider_name  = trim( strip_tags( addslashes( $_POST['slider_name'] ) ) );
			
			$post_data = array(
				'post_title'	=> $slider_name,
				'post_name'		=> sanitize_title( $slider_name ),
				'post_type'		=> 'a3_slider',
			);
			if ( isset( $_POST['auto_draft'] ) && $_POST['auto_draft'] == 1 ) $post_data['post_status'] = 'publish';
			
			if ( isset( $_POST['post_ID'] ) ) {
				$slider_id = $_POST['post_ID'];
				$post_data['ID'] = $slider_id;
				$slider_id = wp_update_post( $post_data );
			} else {
				$slider_id = wp_insert_post( $post_data );
			}
			
			if ( $slider_id > 0 ) {
				update_post_meta( $slider_id, '_a3_slider_id', $slider_id );
				update_post_meta( $slider_id, '_a3_slider_settings', $slider_settings );
				update_post_meta( $slider_id, '_a3_slider_template', addslashes( $_POST['slider_template'] ) );
				
				if ( isset( $_POST['slider_folders'] ) ) {
					$slider_folders = array_map( 'intval', $_POST['slider_folders'] );
    				$slider_folders = array_unique( $slider_folders );
					wp_set_object_terms( $slider_id, $slider_folders, 'slider_folder' );
				} else {
					wp_set_object_terms( $slider_id, NULL, 'slider_folder' );
				}
				
				$photo_galleries = $_REQUEST['photo_galleries'];
				if ( count( $photo_galleries ) > 0 ) {
					A3_Responsive_Slider_Data::remove_slider_images( $slider_id );
					$order = 0;
					foreach ( $photo_galleries['image'] as $key => $images ) {
						$show_readmore = 0;
						if ( isset( $photo_galleries['show_readmore'][$key] ) ) $show_readmore = 1;
						if ( ! isset( $photo_galleries['video_url'][$key] ) && trim( $images ) != '' ) {
							$order++;
							A3_Responsive_Slider_Data::insert_row_image( $slider_id, trim( $images ), $photo_galleries['link'][$key], $photo_galleries['title'][$key], $photo_galleries['text'][$key], $order, $show_readmore );
						}
					}
				}
				
				wp_redirect( 'post.php?post='.$slider_id.'&action=edit&message=4', 301 );
				exit();
			}
		}
		
	}
	
	public static function admin_screen_add_edit( $post ) {
		global $a3_responsive_slider_admin_interface;
		add_action( 'admin_footer', array( 'A3_Responsive_Slider_Hook_Filter', 'include_admin_add_script' ) );
		add_action( 'admin_footer', array( $a3_responsive_slider_admin_interface, 'admin_script_load' ) );
		add_action( 'admin_footer', array( $a3_responsive_slider_admin_interface, 'admin_css_load' ) );
	?>
    	<div class="a3rev_manager_panel_container">
        	<div class="a3rev_panel_container">
        	<?php 
				$slider_id = get_post_meta( $post->ID, '_a3_slider_id' , true );
				if ( empty( $slider_id ) ) $slider_id = 0;
				self::slider_edit_page( $slider_id );
			?>
        	</div>
        </div>
    <?php
	}
		
	public static function slider_edit_page( $slider_id = 0 ) {
		global $wpdb;
		global $a3_responsive_slider_admin_init;
		
		$message = '';
		if ( isset( $_REQUEST['bt_create'] ) || isset( $_REQUEST['bt_update'] ) ) {
			$slider_name  = trim( strip_tags( addslashes( $_REQUEST['slider_name'] ) ) );
			if ( $slider_name == '' ) {
				$message = '<div class="error"><p>'. __( 'Slider name must not empty','a3_responsive_slider' ) .'</p></div>';
			}
		} elseif ( isset( $_GET['status'] ) && $_GET['status'] == 'slider_updated' ) {
			$message = '<div class="updated" id=""><p>'.__('Slider Successfully updated.', 'a3_responsive_slider').'</p></div>';
		} elseif ( isset( $_GET['status'] ) && $_GET['status'] == 'slider_created' ) {
			$message = '<div class="updated" id=""><p>'.__('Slider Successfully created.', 'a3_responsive_slider').'</p></div>';
		}
		
		$my_title = __( 'Add New Slider', 'a3_responsive_slider' );
		$my_button = __( 'Create', 'a3_responsive_slider' );
		$my_button_act = 'bt_create';
		$slider = false;
		$slider_settings = array();
		if ( $slider_id != 0 ) {
			$my_title = __( 'Edit Slider', 'a3_responsive_slider' );
			$slider = true;
			$my_button = __( 'Update', 'a3_responsive_slider' );
			$my_button_act = 'bt_update';
			$slider_settings =  get_post_meta( $slider_id, '_a3_slider_settings', true );
			$slider_template = get_post_meta( $slider_id, '_a3_slider_template' , true );
		}
        if ( $slider_id == 0 || $slider ) {
		?>
        	<?php echo $message; ?>
			<div style="clear:both;"></div>
            	<?php if ( $slider !== false ) { ?>
        		<input type="hidden" readonly="readonly" value="<?php echo $slider_id; ?>" name="slider_id" id="slider_id" />
                <?php } ?>
				<div class="galleries_list" style="position:relative;">
					<div class="control_galleries_top">
                    	<input type="submit" class="button submit button-primary add_new_yt_row" value="<?php _e( 'Add Video', 'a3_responsive_slider' ); ?>" name="add_new_yt_row" /> 
						<input type="submit" class="button submit button-primary add_new_image_row" value="<?php _e( 'Add Image', 'a3_responsive_slider' ); ?>" name="add_new_image_row" /> 
						<input type="submit" class="submit button slider_preview" value="<?php _e( 'Preview', 'a3_responsive_slider' ); ?>" id="preview_2" title="<?php _e( 'Preview Slider', 'a3_responsive_slider' ); ?>" /> 
						<input type="submit" class="button submit button-primary" value="<?php echo $my_button; ?>" name="<?php echo $my_button_act; ?>" />
        			</div>
                    <div id="tabs" class="tabs_section">
						<ul class="nav-tab-wrapper">
							<li class="nav-tab"><a href="#slider_settings"><?php _e( 'Settings', 'a3_responsive_slider' ); ?></a></li>
							<li class="nav-tab"><a href="#image_transition"><?php _e( 'Transition Effects', 'a3_responsive_slider' ); ?></a></li>
							<li class="nav-tab"><a href="#shuffle_effect"><?php _e( 'Shuffle Effect', 'a3_responsive_slider' ); ?></a></li>
							<li class="nav-tab"><a href="#tile_effect"><?php _e( 'Tile Effect', 'a3_responsive_slider' ); ?></a></li>
						<?php if ( $slider !== false ) { ?>
                        	<li class="nav-tab"><a href="#embed"><?php _e( 'Embed', 'a3_responsive_slider' ); ?></a></li>
                        <?php } ?>
                        </ul>
						<div class="tab_content" id="slider_settings">
                            <div class="a3rev_panel_inner">
                                <table class="form-table"><tbody>
                                    <tr valign="top">
                                        <th class="titledesc" scope="row">
                                            <label for="slider_name"><?php _e( 'Slider Name', 'a3_responsive_slider' ); ?></label>
                                        </th>
                                        <td class="forminp forminp-text">
                                            <input
                                                name="slider_name"
                                                id="slider_name"
                                                type="text"
                                                value="<?php if ( $slider !== false ) echo esc_attr( get_the_title( $slider_id ) ); ?>"
                                                class="a3rev-ui-text"
                                                />
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th class="titledesc" scope="row">
                                            <label for="slider_template"><?php _e( 'Slider Skin', 'a3_responsive_slider' ); ?></label>
                                        </th>
                                        <td class="forminp forminp-select">
                                        <?php $slider_templates = A3_Responsive_Slider_Functions::slider_templates(); ?>
                                        <input type="hidden" name="slider_template" value="template-1"  />
                                        <?php echo $slider_templates['template-1']; ?><br />
                                        <fieldset class="a3_rslider_plugin_meta_upgrade_area_box a3_rslider_plugin_meta_upgrade_area_box_edit_post">
                                        <div class="pro_feature_top_message"><?php echo sprintf( __( 'This Lite Version of the plugin has 1 skin available. Try 
the <a href="%s" target="_blank">Pro Version Free Trail</a> to activate 2nd Slider Skin, Card Skin, Widget Skin and Touch Mobile Skin.', 'a3_responsive_slider' ), A3_RESPONSIVE_SLIDER_PRO_VERSION_URI ); ?></div>
                                        <select
                                            id="slider_template"
                                            style="width:160px;"
                                            class="chzn-select a3rev-ui-select slider_template"
                                            data-placeholder="<?php _e( 'Select Template', 'a3_responsive_slider' ); ?>"
                                            >
                                            <?php
                                            global $a3_rslider_template2_global_settings;
                                            global $a3_rslider_template_card_global_settings;
                                            
                                            foreach ( $slider_templates as $key => $val ) {
                                                if ( $key == 'template-2' && $a3_rslider_template2_global_settings['is_activated'] != 1 ) continue;
                                                elseif  ( $key == 'template-card' && $a3_rslider_template_card_global_settings['is_activated'] != 1 ) continue;
												elseif  ( $key == 'template-mobile' ) continue;
                                                ?>
                                                <option value="" <?php
            
                                                        if ( $slider !== false ) selected( $slider_template, $key );
            
                                                ?>><?php echo $val ?></option>
                                                <?php
                                            }
                                            ?>
                                       	</select>
                                        </fieldset>
                                    	</td>
                                    </tr>
                                    <tr valign="top">
                                        <th class="titledesc" scope="row"><label for="slider_folders"><?php _e( 'Assign to Folders', 'a3_responsive_slider' ); ?></label></th>
                                        <td class="forminp forminp-multiselect">
                                            <select
                                                name="slider_folders[]"
                                                id="slider_folders"
                                                class="chzn-select a3rev-ui-multiselect"
                                                data-placeholder="<?php _e( 'Select Folders', 'a3_responsive_slider' ); ?>"
                                                multiple="multiple"
                                                >
                                            <?php
											$slider_folders = array();
                                            if ( $slider !== false ) {
												$slider_folders_terms = get_the_terms( $slider_id, 'slider_folder' );
												if ( is_array( $slider_folders_terms ) && count( $slider_folders_terms ) > 0 ) {
													foreach ( $slider_folders_terms as $slider_folders_term ) {
														$slider_folders[] = $slider_folders_term->term_id;
													}
												}
											}
											
											$all_folders = get_terms( 'slider_folder', array(
												'hide_empty'	=> false,
											) );
											
											if ( is_array( $all_folders ) && count( $all_folders ) > 0 ) {
                                            	foreach ( $all_folders as $a_folder ) {
                                            ?>
                                                <option value="<?php echo esc_attr( $a_folder->term_id ); ?>" 
                                                <?php if ( $slider !== false ) selected( in_array( $a_folder->term_id, $slider_folders ), true ); ?>
                                                ><?php echo esc_attr( $a_folder->name ); ?></option>
                                            <?php
												}
                                            }
                                            ?>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody></table>
                            </div>
                    	</div>
                        
                        <div class="tab_content" id="image_transition">
                        	<fieldset class="a3_rslider_plugin_meta_upgrade_area_box">
                            <div class="pro_feature_top_message"><?php echo sprintf( __( 'Show Youtube Videos in Slider is an advanced feature Activated in the Pro Version. <a href="%s" target="_blank">Trial the Pro Version</a> for Fee to see if this is a feature you want.', 'a3_responsive_slider' ), A3_RESPONSIVE_SLIDER_PRO_VERSION_URI ); ?></div>
                        	<div class="a3rev_panel_inner">
                            	<h3><?php _e( 'Videos in Slider', 'a3_responsive_slider' ); ?></h3>
                                <table class="form-table"><tbody>
                                	<tr valign="top">
                                        <th class="titledesc" scope="row">
                                            <label for="support_youtube_videos"><?php _e( 'Youtube Videos', 'a3_responsive_slider' ); ?></label>
                                        </th>
                                        <td class="forminp forminp-onoff_checkbox">
                                            <input
                                                name="slider_settings[support_youtube_videos]"
                                                id="support_youtube_videos"
                                                class="a3rev-ui-onoff_checkbox support_youtube_videos"
                                                checked_label="<?php _e( 'ON', 'a3_responsive_slider' ); ?>"
                                                unchecked_label="<?php _e( 'OFF', 'a3_responsive_slider' ); ?>"
                                                type="checkbox"
                                                value="1"
                                                
                                                />
                                        </td>
                                    </tr>
                                </tbody></table>
							</div>
                            
                            <div id="support_youtube_videos_on">
                            	<div class="a3rev_panel_inner">
                                    <table class="form-table"><tbody>
                                        <tr valign="top">
                                            <th class="titledesc" scope="row"><label for="yt_slider_transition_effect"><?php _e( 'Transition Effects', 'a3_responsive_slider' ); ?></label></th>
                                            <td class="forminp forminp-select">
                                                <select
                                                    name="slider_settings[yt_slider_transition_effect]"
                                                    id="yt_slider_transition_effect"
                                                    style="width:160px;"
                                                    class="chzn-select a3rev-ui-select yt_slider_transition_effect"
                                                    data-placeholder="<?php _e( 'Select Effect', 'a3_responsive_slider' ); ?>"
                                                    >
                                                    <?php
                                                    $arr_effect = A3_Responsive_Slider_Functions::yt_slider_transitions_list();
                                                    foreach ( $arr_effect as $key => $val ) {
                                                        ?>
                                                        <option value="<?php echo esc_attr( $key ); ?>" <?php
                    
                                                        ?>><?php echo $val ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </div>
                                <div class="a3rev_panel_inner">
                                	<h3><?php _e( 'Transition Timing', 'a3_responsive_slider' ); ?></h3>
                                    <p class="description"><?php _e( 'Videos slider transitions are manual not auto. Be sure to use a Skin that has Controls activated for manual scroll > Next < Previous.', 'a3_responsive_slider' ); ?></p>
                                    <table class="form-table"><tbody>
                                        <tr valign="top">
                                            <th class="titledesc" scope="row"><label for="yt_slider_speed"><?php _e( 'Transition Effect Speed', 'a3_responsive_slider' ); ?></label></th>
                                            <td class="forminp forminp-slider">
                                                <div class="a3rev-ui-slide-container">
                                                    <div class="a3rev-ui-slide-container-start"><div class="a3rev-ui-slide-container-end">
                                                        <div class="a3rev-ui-slide" id="yt_slider_speed_div" min="1" max="20" inc="1"></div>
                                                    </div></div>
                                                    <div class="a3rev-ui-slide-result-container">
                                                        <input
                                                            readonly="readonly"
                                                            name="slider_settings[yt_slider_speed]"
                                                            id="yt_slider_speed"
                                                            type="text"
                                                            value="1"
                                                            class="a3rev-ui-slider"
                                                            /> <span style="margin-left:5px;" class="description"><?php _e( 'second(s)', 'a3_responsive_slider' ); ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </div>
                       
                                <div class="a3rev_panel_inner">
                                    <h3><?php _e( 'Youtube Settings', 'a3_responsive_slider' ); ?></h3>
                                    <table class="form-table"><tbody>
                                        <tr valign="top">
                                            <th class="titledesc" scope="row">
                                                <label for="is_yt_auto_start"><?php _e( 'Youtube Auto Start', 'a3_responsive_slider' ); ?></label>
                                            </th>
                                            <td class="forminp forminp-onoff_checkbox">
                                                <input
                                                    name="slider_settings[is_yt_auto_start]"
                                                    id="is_yt_auto_start"
                                                    class="a3rev-ui-onoff_checkbox is_yt_auto_start"
                                                    checked_label="<?php _e( 'ON', 'a3_responsive_slider' ); ?>"
                                                    unchecked_label="<?php _e( 'OFF', 'a3_responsive_slider' ); ?>"
                                                    type="checkbox"
                                                    value="true"
                                                    /> <span style="margin-left:5px;" class="description"><?php _e( 'ON to have videos automatically start when they are visible in the slideshow.', 'a3_responsive_slider' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <th class="titledesc" scope="row">
                                                <label for="is_yt_auto_stop"><?php _e( 'Youtube Auto Pause', 'a3_responsive_slider' ); ?></label>
                                            </th>
                                            <td class="forminp forminp-onoff_checkbox">
                                                <input
                                                    name="slider_settings[is_yt_auto_stop]"
                                                    id="is_yt_auto_stop"
                                                    class="a3rev-ui-onoff_checkbox is_yt_auto_stop"
                                                    checked_label="<?php _e( 'ON', 'a3_responsive_slider' ); ?>"
                                                    unchecked_label="<?php _e( 'OFF', 'a3_responsive_slider' ); ?>"
                                                    type="checkbox"
                                                    value="true"
                                                    /> <span style="margin-left:5px;" class="description"><?php _e( 'ON for current slide video to be auto paused when user transition to next slide.', 'a3_responsive_slider' ); ?></span>
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </div>
                                
                                <div style="" class="a3rev_panel_inner " id="">
                                    <h3><?php _e( 'Image Transition Effects', 'a3_responsive_slider' ); ?></h3>
                                    <table class="form-table"><tbody>
                                        <tr valign="top">
                                            <th class="titledesc" scope="row">
                                                <label for="is_2d_effects"><?php _e( 'Effect Type', 'a3_responsive_slider' ); ?></label>
                                            </th>
                                            <td class="forminp forminp-switcher_checkbox">
                                                <input
                                                	name="is_2d_effects"
                                                    id="is_2d_effects"
                                                    class="a3rev-ui-onoff_checkbox is_2d_effects"
                                                    checked_label="<?php _e( 'Ken Burns', 'a3_responsive_slider' ); ?>"
                                                    unchecked_label="<?php _e( '2D Effects', 'a3_responsive_slider' ); ?>"
                                                    type="checkbox"
                                                    value="0"
                                                    />
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </div>
                                
                                <div class="a3rev_panel_inner ken_burns_container">
                                    <div style="" class="a3rev_panel_inner " id="">
                                        <h3><?php _e( 'Ken Burns Effect Settings', 'a3_responsive_slider' ); ?></h3>
                                        <table class="form-table"><tbody>
                                            <tr valign="top">
                                                <th class="titledesc" scope="row"><label for="kb_is_auto_start"><?php _e( 'Ken Burns Transition Method', 'a3_responsive_slider' ); ?></label></th>
                                                <td class="forminp forminp-switcher_checkbox">
                                                    <input
                                                        name="slider_settings[kb_is_auto_start]"
                                                        id="kb_is_auto_start"
                                                        class="a3rev-ui-onoff_checkbox kb_is_auto_start"
                                                        checked_label="<?php _e( 'AUTO', 'a3_responsive_slider' ); ?>"
                                                        unchecked_label="<?php _e( 'MANUAL', 'a3_responsive_slider' ); ?>"
                                                        type="checkbox"
                                                        value="1"
                                                        />
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    </div>
                                    <div class="a3rev_panel_inner kb_is_auto_start_on">
                                        <table class="form-table"><tbody>
                                            <tr valign="top">
                                                <th class="titledesc" scope="row"><label for="kb_slider_delay"><?php _e( 'Auto Start Delay', 'a3_responsive_slider' ); ?></label></th>
                                                <td class="forminp forminp-slider">
                                                    <div class="a3rev-ui-slide-container">
                                                        <div class="a3rev-ui-slide-container-start"><div class="a3rev-ui-slide-container-end">
                                                            <div class="a3rev-ui-slide" id="kb_slider_delay_div" min="0" max="20" inc="1"></div>
                                                        </div></div>
                                                        <div class="a3rev-ui-slide-result-container">
                                                            <input
                                                                readonly="readonly"
                                                                name="slider_settings[kb_slider_delay]"
                                                                id="kb_slider_delay"
                                                                type="text"
                                                                value="0"
                                                                class="a3rev-ui-slider"
                                                                /> <span style="margin-left:5px;" class="description"><?php _e( 'second(s)', 'a3_responsive_slider' ); ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr valign="top">
                                                <th class="titledesc" scope="row"><label for="kb_slider_timeout"><?php _e( 'Time Between Transitions', 'a3_responsive_slider' ); ?></label></th>
                                                <td class="forminp forminp-slider">
                                                    <div class="a3rev-ui-slide-container">
                                                        <div class="a3rev-ui-slide-container-start"><div class="a3rev-ui-slide-container-end">
                                                            <div class="a3rev-ui-slide" id="kb_slider_timeout_div" min="1" max="20" inc="1"></div>
                                                        </div></div>
                                                        <div class="a3rev-ui-slide-result-container">
                                                            <input
                                                                readonly="readonly"
                                                                name="slider_settings[kb_slider_timeout]"
                                                                id="kb_slider_timeout"
                                                                type="text"
                                                                value="4"
                                                                class="a3rev-ui-slider"
                                                                /> <span style="margin-left:5px;" class="description"><?php _e( 'second(s)', 'a3_responsive_slider' ); ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    </div>
                                    
                                    <div class="a3rev_panel_inner">
                                        <table class="form-table"><tbody>
                                            <tr valign="top">
                                                <th class="titledesc" scope="row"><label for="kb_slider_speed"><?php _e( 'Transition Effect Speed', 'a3_responsive_slider' ); ?></label></th>
                                                <td class="forminp forminp-slider">
                                                    <div class="a3rev-ui-slide-container">
                                                        <div class="a3rev-ui-slide-container-start"><div class="a3rev-ui-slide-container-end">
                                                            <div class="a3rev-ui-slide" id="kb_slider_speed_div" min="1" max="20" inc="1"></div>
                                                        </div></div>
                                                        <div class="a3rev-ui-slide-result-container">
                                                            <input
                                                                readonly="readonly"
                                                                name="slider_settings[kb_slider_speed]"
                                                                id="kb_slider_speed"
                                                                type="text"
                                                                value="1"
                                                                class="a3rev-ui-slider"
                                                                /> <span style="margin-left:5px;" class="description"><?php _e( 'second(s)', 'a3_responsive_slider' ); ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    </div>
                                    
                                    <div class="a3rev_panel_inner">
                                        <table class="form-table"><tbody>
                                            <tr valign="top">
                                                <th class="titledesc" scope="row">
                                                    <label for="data-cycle-kbduration"><?php _e( 'Ken Burns Duration', 'a3_responsive_slider' ); ?></label>
                                                </th>
                                                <td class="forminp forminp-slider">
                                                    <div class="a3rev-ui-slide-container">
                                                        <div class="a3rev-ui-slide-container-start"><div class="a3rev-ui-slide-container-end">
                                                            <div class="a3rev-ui-slide" id="data-cycle-kbduration_div" min="1" max="10" inc="1"></div>
                                                        </div></div>
                                                        <div class="a3rev-ui-slide-result-container">
                                                            <input
                                                                readonly="readonly"
                                                                name="slider_settings[data-cycle-kbduration]"
                                                                id="data-cycle-kbduration"
                                                                type="text"
                                                                value="1"
                                                                class="a3rev-ui-slider"
                                                                /> <span style="margin-left:5px;" class="description"><?php _e( 'The number of seconds to duration.', 'a3_responsive_slider' ); ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    </div>
                                    
                                    <div class="a3rev_panel_inner">
                                        <table class="form-table"><tbody>
                                            <tr valign="top">
                                                <th class="titledesc" scope="row">
                                                    <label for="data-cycle-kbzoom"><?php _e( 'Ken Burns Zoom', 'a3_responsive_slider' ); ?></label>
                                                </th>
                                                <td class="forminp forminp-select">
                                                    <select
                                                        name="slider_settings[data-cycle-kbzoom]"
                                                        id="data-cycle-kbzoom"
                                                        style="width:160px;"
                                                        class="chzn-select a3rev-ui-select"
                                                        >
                                                        <?php
                                                        $zoom_options = array(
                                                            'random'		=> __( 'Random', 'a3_responsive_slider' ),
                                                            'zoom-out'		=> __( 'Zoom Out', 'a3_responsive_slider' ),
                                                            'zoom-in'		=> __( 'Zoom In', 'a3_responsive_slider' ),
                                                        );
                                                        
                                                        foreach ( $zoom_options as $key => $val ) {
                                                            ?>
                                                            <option value="<?php echo esc_attr( $key ); ?>"><?php echo $val ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr valign="top">
                                                <th class="titledesc" scope="row">
                                                    <label for="data-cycle-startPos"><?php _e( 'Ken Burns Start Position', 'a3_responsive_slider' ); ?></label>
                                                </th>
                                                <td class="forminp forminp-select">
                                                    <select
                                                        name="slider_settings[data-cycle-startPos]"
                                                        id="data-cycle-startPos"
                                                        style="width:160px;"
                                                        class="chzn-select a3rev-ui-select"
                                                        >
                                                        <?php
                                                        $position_options = array(
                                                            'random'	=> __( 'Random', 'a3_responsive_slider' ),
                                                            'tl'		=> __( 'Top Left', 'a3_responsive_slider' ),
                                                            'tc'		=> __( 'Top Center', 'a3_responsive_slider' ),
                                                            'tr'		=> __( 'Top Right', 'a3_responsive_slider' ),
                                                            'cl'		=> __( 'Center Left', 'a3_responsive_slider' ),
                                                            'cc'		=> __( 'Center Center', 'a3_responsive_slider' ),
                                                            'cr'		=> __( 'Center Right', 'a3_responsive_slider' ),
                                                            'bl'		=> __( 'Bottom Left', 'a3_responsive_slider' ),
                                                            'bc'		=> __( 'Bottom Center', 'a3_responsive_slider' ),
                                                            'br'		=> __( 'Bottom Right', 'a3_responsive_slider' ),
                                                        );
                                                        
                                                        foreach ( $position_options as $key => $val ) {
                                                            ?>
                                                            <option value="<?php echo esc_attr( $key ); ?>"><?php echo $val ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr valign="top">
                                                <th class="titledesc" scope="row">
                                                    <label for="data-cycle-endPos"><?php _e( 'Ken Burns End Position', 'a3_responsive_slider' ); ?></label>
                                                </th>
                                                <td class="forminp forminp-select">
                                                    <select
                                                        name="slider_settings[data-cycle-endPos]"
                                                        id="data-cycle-endPos"
                                                        style="width:160px;"
                                                        class="chzn-select a3rev-ui-select"
                                                        >
                                                        <?php                                                
                                                        foreach ( $position_options as $key => $val ) {
                                                            ?>
                                                            <option value="<?php echo esc_attr( $key ); ?>"><?php echo $val ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    </div>
                                </div>
                            </div>
                            </fieldset>
                            
                            <div>
                                <div style="" class="a3rev_panel_inner " id="">
                                    <h3><?php _e( 'Image Transition Effects', 'a3_responsive_slider' ); ?></h3>
                                    <table class="form-table"><tbody>
                                        <tr valign="top">
                                            <th class="titledesc" scope="row">
                                                <label><?php _e( 'Effect Type', 'a3_responsive_slider' ); ?></label>
                                            </th>
                                            <td class="forminp forminp-switcher_checkbox">
                                            	<input type="hidden" name="slider_settings[is_2d_effects]" value="1"  />
                                                <?php _e( '2D EFFECTS', 'a3_responsive_slider' ); ?>
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </div>
                                <div style="" class="a3rev_panel_inner " id="">
                                    <table class="form-table"><tbody>
                                        <tr valign="top">
                                            <th class="titledesc" scope="row"><label for="slider_transition_effect"><?php _e( '2D Effects', 'a3_responsive_slider' ); ?></label></th>
                                            <td class="forminp forminp-select">
                                                <select
                                                    name="slider_settings[slider_transition_effect]"
                                                    id="slider_transition_effect"
                                                    style="width:160px;"
                                                    class="chzn-select a3rev-ui-select slider_transition_effect"
                                                    data-placeholder="<?php _e( 'Select Effect', 'a3_responsive_slider' ); ?>"
                                                    >
                                                    <?php
                                                    $arr_effect = A3_Responsive_Slider_Functions::slider_transitions_list();
                                                    foreach ( $arr_effect as $key => $val ) {
                                                        ?>
                                                        <option value="<?php echo esc_attr( $key ); ?>" <?php
                    
                                                                if ( $slider !== false && isset( $slider_settings['slider_transition_effect'] ) ) selected( $slider_settings['slider_transition_effect'], $key );
                    
                                                        ?>><?php echo $val ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </div>
                                
                                <div class="a3rev_panel_inner ">
                                    <div style="" class="a3rev_panel_inner" id="">
                                        <h3><?php _e( 'Image Transition Timing', 'a3_responsive_slider' ); ?></h3>
                                        <table class="form-table"><tbody>
                                            <tr valign="top">
                                                <th class="titledesc" scope="row"><label for="is_auto_start"><?php _e( 'Transition Method', 'a3_responsive_slider' ); ?></label></th>
                                                <td class="forminp forminp-switcher_checkbox">
                                                    <input
                                                        name="slider_settings[is_auto_start]"
                                                        id="is_auto_start"
                                                        class="a3rev-ui-onoff_checkbox is_auto_start"
                                                        checked_label="<?php _e( 'AUTO', 'a3_responsive_slider' ); ?>"
                                                        unchecked_label="<?php _e( 'MANUAL', 'a3_responsive_slider' ); ?>"
                                                        type="checkbox"
                                                        value="1"
                                                        <?php if ( $slider !== false && isset( $slider_settings['is_auto_start'] ) ) checked( $slider_settings['is_auto_start'], 1 ); ?>
                                                        />
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    </div>
                                    <div class="a3rev_panel_inner is_auto_start_on">
                                        <table class="form-table"><tbody>
                                            <tr valign="top">
                                                <th class="titledesc" scope="row"><label for="slider_delay"><?php _e( 'Auto Start Delay', 'a3_responsive_slider' ); ?></label></th>
                                                <td class="forminp forminp-slider">
                                                    <div class="a3rev-ui-slide-container">
                                                        <div class="a3rev-ui-slide-container-start"><div class="a3rev-ui-slide-container-end">
                                                            <div class="a3rev-ui-slide" id="slider_delay_div" min="0" max="20" inc="1"></div>
                                                        </div></div>
                                                        <div class="a3rev-ui-slide-result-container">
                                                            <input
                                                                readonly="readonly"
                                                                name="slider_settings[slider_delay]"
                                                                id="slider_delay"
                                                                type="text"
                                                                value="<?php if ( $slider !== false && isset( $slider_settings['slider_delay'] ) ) echo esc_attr( $slider_settings['slider_delay'] ); else echo 0; ?>"
                                                                class="a3rev-ui-slider"
                                                                /> <span style="margin-left:5px;" class="description"><?php _e( 'second(s)', 'a3_responsive_slider' ); ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr valign="top">
                                                <th class="titledesc" scope="row"><label for="slider_timeout"><?php _e( 'Time Between Transitions', 'a3_responsive_slider' ); ?></label></th>
                                                <td class="forminp forminp-slider">
                                                    <div class="a3rev-ui-slide-container">
                                                        <div class="a3rev-ui-slide-container-start"><div class="a3rev-ui-slide-container-end">
                                                            <div class="a3rev-ui-slide" id="slider_timeout_div" min="1" max="20" inc="1"></div>
                                                        </div></div>
                                                        <div class="a3rev-ui-slide-result-container">
                                                            <input
                                                                readonly="readonly"
                                                                name="slider_settings[slider_timeout]"
                                                                id="slider_timeout"
                                                                type="text"
                                                                value="<?php if ( $slider !== false && isset( $slider_settings['slider_timeout'] ) ) echo esc_attr( $slider_settings['slider_timeout'] ); else echo 4; ?>"
                                                                class="a3rev-ui-slider"
                                                                /> <span style="margin-left:5px;" class="description"><?php _e( 'second(s)', 'a3_responsive_slider' ); ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    </div>
                                    
                                    <div class="a3rev_panel_inner">
                                        <table class="form-table"><tbody>
                                            <tr valign="top">
                                                <th class="titledesc" scope="row"><label for="slider_speed"><?php _e( 'Transition Effect Speed', 'a3_responsive_slider' ); ?></label></th>
                                                <td class="forminp forminp-slider">
                                                    <div class="a3rev-ui-slide-container">
                                                        <div class="a3rev-ui-slide-container-start"><div class="a3rev-ui-slide-container-end">
                                                            <div class="a3rev-ui-slide" id="slider_speed_div" min="1" max="20" inc="1"></div>
                                                        </div></div>
                                                        <div class="a3rev-ui-slide-result-container">
                                                            <input
                                                                readonly="readonly"
                                                                name="slider_settings[slider_speed]"
                                                                id="slider_speed"
                                                                type="text"
                                                                value="<?php if ( $slider !== false && isset( $slider_settings['slider_speed'] ) ) echo esc_attr( $slider_settings['slider_speed'] ); else echo 1; ?>"
                                                                class="a3rev-ui-slider"
                                                                /> <span style="margin-left:5px;" class="description"><?php _e( 'second(s)', 'a3_responsive_slider' ); ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    </div>
                                    
                                </div>
                                
                                
							</div>
                        </div>
                        
                        <div class="tab_content" id="shuffle_effect">
                            <div class="a3rev_panel_inner">
                            	<h3><?php _e( 'Shuffle Effect Settings', 'a3_responsive_slider' ); ?></h3>
                                <table class="form-table"><tbody>
                                    <tr valign="top">
                                        <th class="titledesc" scope="row">
                                            <label for="data-cycle-shuffle-left"><?php _e( 'Shuffle Left', 'a3_responsive_slider' ); ?></label>
                                        </th>
                                        <td class="forminp forminp-text">
                                            <input
                                                name="slider_settings[data-cycle-shuffle-left]"
                                                id="data-cycle-shuffle-left"
                                                type="text"
                                                style="width:40px;"
                                                value="<?php if ( $slider !== false && isset( $slider_settings['data-cycle-shuffle-left'] ) ) echo esc_attr( $slider_settings['data-cycle-shuffle-left'] ); else echo 0; ?>"
                                                class="a3rev-ui-text"
                                                /> <span style="margin-left:5px;" class="description">px. <?php _e( "Pixel position relative to the container's left edge to move the slide when transitioning. Set to negative to move beyond the container's left edge.", 'a3_responsive_slider' ); ?></span>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th class="titledesc" scope="row">
                                            <label for="data-cycle-shuffle-right"><?php _e( 'Shuffle Right', 'a3_responsive_slider' ); ?></label>
                                        </th>
                                        <td class="forminp forminp-text">
                                            <input
                                                name="slider_settings[data-cycle-shuffle-right]"
                                                id="data-cycle-shuffle-right"
                                                type="text"
                                                style="width:40px;"
                                                value="<?php if ( $slider !== false && isset( $slider_settings['data-cycle-shuffle-right'] ) ) echo esc_attr( $slider_settings['data-cycle-shuffle-right'] ); else echo 0; ?>"
                                                class="a3rev-ui-text"
                                                /> <span style="margin-left:5px;" class="description">px. <?php _e( "Number of pixels beyond right edge of container to move the slide when transitioning.", 'a3_responsive_slider' ); ?></span>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th class="titledesc" scope="row">
                                            <label for="data-cycle-shuffle-top"><?php _e( 'Shuffle Top', 'a3_responsive_slider' ); ?></label>
                                        </th>
                                        <td class="forminp forminp-text">
                                            <input
                                                name="slider_settings[data-cycle-shuffle-top]"
                                                id="data-cycle-shuffle-top"
                                                type="text"
                                                style="width:40px;"
                                                value="<?php if ( $slider !== false && isset( $slider_settings['data-cycle-shuffle-top'] ) ) echo esc_attr( $slider_settings['data-cycle-shuffle-top'] ); else echo 15; ?>"
                                                class="a3rev-ui-text"
                                                /> <span style="margin-left:5px;" class="description">px. <?php _e( "Number of pixels beyond top edge of container to move the slide when transitioning.", 'a3_responsive_slider' ); ?></span>
                                        </td>
                                    </tr>
                                </tbody></table>
                            </div>
                    	</div>
                        
                        <div class="tab_content" id="tile_effect">
                            <div class="a3rev_panel_inner">
                            	<h3><?php _e( 'Tile Effect Settings', 'a3_responsive_slider' ); ?></h3>
                                <table class="form-table"><tbody>
                                    <tr valign="top">
                                        <th class="titledesc" scope="row">
                                            <label for="data-cycle-tile-count"><?php _e( 'Tile Count', 'a3_responsive_slider' ); ?></label>
                                        </th>
                                        <td class="forminp forminp-slider">
                                        	<div class="a3rev-ui-slide-container">
                                                <div class="a3rev-ui-slide-container-start"><div class="a3rev-ui-slide-container-end">
                                                    <div class="a3rev-ui-slide" id="data-cycle-tile-count_div" min="1" max="20" inc="1"></div>
                                                </div></div>
                                                <div class="a3rev-ui-slide-result-container">
                                                    <input
                                                        readonly="readonly"
                                                        name="slider_settings[data-cycle-tile-count]"
                                                        id="data-cycle-tile-count"
                                                        type="text"
                                                        value="<?php if ( $slider !== false && isset( $slider_settings['data-cycle-tile-count'] ) ) echo esc_attr( $slider_settings['data-cycle-tile-count'] ); else echo 7; ?>"
                                                        class="a3rev-ui-slider"
                                                        /> <span style="margin-left:5px;" class="description"><?php _e( 'The number of tiles to use in the transition.', 'a3_responsive_slider' ); ?></span>
                                                </div>
                                            </div>
                                    	</td>
                                    </tr>
                                    <tr valign="top">
                                        <th class="titledesc" scope="row">
                                            <label for="data-cycle-tile-delay"><?php _e( 'Tile Delay', 'a3_responsive_slider' ); ?></label>
                                        </th>
                                        <td class="forminp forminp-slider">
                                        	<div class="a3rev-ui-slide-container">
                                                <div class="a3rev-ui-slide-container-start"><div class="a3rev-ui-slide-container-end">
                                                    <div class="a3rev-ui-slide" id="data-cycle-tile-delay_div" min="1" max="10" inc="1"></div>
                                                </div></div>
                                                <div class="a3rev-ui-slide-result-container">
                                                    <input
                                                        readonly="readonly"
                                                        name="slider_settings[data-cycle-tile-delay]"
                                                        id="data-cycle-tile-delay"
                                                        type="text"
                                                        value="<?php if ( $slider !== false && isset( $slider_settings['data-cycle-tile-delay'] ) ) echo esc_attr( $slider_settings['data-cycle-tile-delay'] ); else echo 1; ?>"
                                                        class="a3rev-ui-slider"
                                                        /> <span style="margin-left:5px;" class="description"><?php _e( 'The number of seconds to delay each individual tile transition.', 'a3_responsive_slider' ); ?></span>
                                                </div>
                                            </div>
                                    	</td>
                                    </tr>
                                    <tr valign="top">
                                        <th class="titledesc" scope="row">
                                            <label for="data-cycle-tile-vertical"><?php _e( 'Tile Vertical', 'a3_responsive_slider' ); ?></label>
                                        </th>
                                        <td class="forminp forminp-onoff_checkbox">
                                        	<input
                                                name="slider_settings[data-cycle-tile-vertical]"
                                                id="data-cycle-tile-vertical"
                                                class="a3rev-ui-onoff_checkbox"
                                                checked_label="<?php _e( 'ON', 'a3_responsive_slider' ); ?>"
                                                unchecked_label="<?php _e( 'OFF', 'a3_responsive_slider' ); ?>"
                                                type="checkbox"
                                                value="true"
                                                <?php if ( $slider !== false ) checked( $slider_settings['data-cycle-tile-vertical'], 'true' ); ?>
                                                /> <span style="margin-left:5px;" class="description"><?php _e( 'Set to OFF for a horizontal transition.', 'a3_responsive_slider' ); ?></span>
                                        </td>
                                    </tr>
                                </tbody></table>
                            </div>
                    	</div>
                        
                        <?php if ( $slider !== false ) { ?>
                        <!-- Just show for Edit Slider -->
                        <div class="tab_content" id="embed">
                        	<div class="a3rev_panel_inner">
                                <table class="form-table"><tbody>
                                    <tr valign="top">
                                        <th class="titledesc" scope="row">
                                            <label><?php _e( 'Shortcode', 'a3_responsive_slider' ); ?>:</label>
                                        </th>
                                        <td class="forminp forminp-text">
                                        	[a3_responsive_slider id="<?php echo $slider_id; ?>"]
                                    	</td>
                                    </tr>
                                </tbody></table>
                            </div>
                        	<fieldset class="a3_rslider_plugin_meta_upgrade_area_box">
							<?php $a3_responsive_slider_admin_init->upgrade_top_message(true); ?>
                            <div class="a3rev_panel_inner">
                                <table class="form-table"><tbody>
                                    <tr valign="top">
                                        <th class="titledesc" scope="row">
                                            <label><?php _e( 'Template tag', 'a3_responsive_slider' ); ?>:</label>
                                        </th>
                                        <td class="forminp forminp-text">
                                        	&lt;?php echo a3_responsive_slider( <?php echo $slider_id; ?> ); ?&gt;
                                    	</td>
                                    </tr>
                                </tbody></table>
                            </div>
                            
                            <?php
								global $a3_rslider_shortcode; 
								$a3_rslider_shortcode->show_all_posts_use_shortcode_slider( $slider_id ); 
							?>
                            </fieldset>
                    	</div>
                        <script>
						(function($) {
						$(document).ready(function() {
							$(document).on('click', '.a3_slider_remove_shortcode', function() {
								$(this).addClass('removing');
								var remove_object = $(this);
								var post_id = $(this).attr('post-id');
								
								setTimeout( function() {
									$(remove_object).removeClass('removing');
									
										$(remove_object).addClass('icon-removed-success');
										setTimeout( function() {
											$(remove_object).removeClass('icon-removed-success');
											$('.a3_slider_used_on_post_' + post_id ).slideUp();
										}, 2000 );
									
								}, 2000);
							});
						});
						})(jQuery);
						</script>
                        <?php } ?>
                    </div>
       
                    <table id="galleries-table" class="ui-sortable">
						<tbody>
                        <?php
						if ( $slider !== false ) {
							$photo_galleries = A3_Responsive_Slider_Data::get_all_images_from_slider( $slider_id );
							if ( $photo_galleries ) {
								$i = 0;
								foreach ( $photo_galleries as $galleries_item ) {
									$i++;
									A3_Responsive_Slider_Edit::galleries_render_image( $slider_settings, $i, $galleries_item, false );
								}
								
							}
						}
                        A3_Responsive_Slider_Edit::galleries_render_image( $slider_settings, 0, array(), true);
                        ?>
						</tbody>
                    </table>
                    <?php $a3_slider_preview = wp_create_nonce("a3-slider-preview"); ?>
                    <script>
					(function($) {
					$(document).ready(function() {
						$('.slider_preview').click(function(){
							var ajax_url = "<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>";
							var form_data = $('form#post').serialize();
							tb_show( $('form#post').find('#slider_name').val() + ' - <?php _e( 'Slider Preview', 'a3_responsive_slider' ); ?>', ajax_url+'?KeepThis=true&'+form_data+'&action=a3_slider_preview&security=<?php echo $a3_slider_preview; ?>&height=500');
							return false;
						});
					});
					})(jQuery);
                    </script>
                    
        			<input type="hidden" id="url_noimage" value="<?php echo A3_RESPONSIVE_SLIDER_IMAGES_URL.'/noimg385x180.jpg';?>" />
                    
                    <div class="control_galleries_bottom" style="padding-top:5px;">
                    	<input type="submit" class="button submit button-primary add_new_yt_row" value="<?php _e( 'Add Video', 'a3_responsive_slider' ); ?>" name="add_new_yt_row" />
                        <input type="submit" class="button submit button-primary add_new_image_row" value="<?php _e( 'Add Image', 'a3_responsive_slider' ); ?>" name="add_new_image_row" /> 
                        <input type="submit" class="submit button slider_preview" value="<?php _e( 'Preview', 'a3_responsive_slider' ); ?>" id="preview_1" title="<?php _e( 'Preview Slider', 'a3_responsive_slider' ); ?>" /> 
                        <input type="submit" class="button submit button-primary" value="<?php echo $my_button; ?>" name="<?php echo $my_button_act; ?>" />
                    </div>
        		</div>
		<?php } else { ?>
			<p><?php echo sprintf( __( 'There are no Slider yet. You can create new Slider at <a href="%s">here</a>.', 'a3_responsive_slider' ), 'admin.php?page=a3-rslider-add' ); ?></p>
		<?php }
	}
		
	public static function galleries_render_image( $slider_settings, $i = 0, $item = array(), $new = false ) {
		if ( ! is_array( $item ) && $item->video_url != '' && $item->is_video == 1 ) {
			$src = '';
			$image_container = '<object><param value="'. A3_Responsive_Slider_Functions::get_youtube_url( $item->video_url ) .'&enablejsapi=1" name="movie"><param value="true" name="allowFullScreen"><param value="always" name="allowscriptaccess"><param value="opaque" name="wmode"><embed wmode="opaque" allowfullscreen="false" allowscriptaccess="always" type="application/x-shockwave-flash" src="'. A3_Responsive_Slider_Functions::get_youtube_url( $item->video_url ) .'&enablejsapi=1"></object>';
		} elseif ( ! is_array( $item ) && $item->img_url != '' ) {
			$src = $item->img_url;
			$image_container = '<img class="galleries-image" id="galleries-image-'.$i.'" src="'.$src.'" alt="'.__( 'Add an Image', 'a3_responsive_slider' ).'">';
		} else {
			$image_container = '<span class="icon-slider-add-new-image"></span>';
		}
		if ( $new ) {
			$hidden = '';
		} else {
			$hidden = $src;
		}
		?>
		<tr class="<?php if( $new ) echo 'new';?> <?php if ( ! is_array( $item ) && $item->video_url != '' && $item->is_video == 1 ) echo 'galleries-yt-row';?>" style=" <?php if ( empty( $slider_settings['support_youtube_videos'] ) && ! is_array( $item ) && $item->video_url != '' && $item->is_video == 1 ) echo 'display:none'; ?>">
              <td>
                <div class="image-wrapper">
                <?php if ( ! is_array( $item ) && $item->video_url != '' && $item->is_video == 1 ) { ?>
                <?php echo $image_container; ?>
                <?php } else { ?>
                <a href="#" title="<?php _e( 'Add an Image', 'a3_responsive_slider' ); ?>" alt="galleries-image-<?php echo $i;?>" class="browse_upload galleries-image-<?php echo $i;?>-container"><?php echo $image_container; ?></a>
                <?php } ?>
                  <input type="hidden" id="galleries-image-<?php echo $i;?>-hidden" value="<?php echo $hidden;?>" name="photo_galleries[image][<?php echo $i;?>]">
                </div>
                <div class="data-wrapper">
                <div class="title-wrapper">
                  <label for="galleries-title-<?php echo $i;?>"><?php _e( 'Title', 'a3_responsive_slider' ); ?></label>
                  <input type="text" class="galleries-title" id="galleries-title-<?php echo $i;?>" value="<?php if ( ! is_array( $item ) ) echo stripcslashes( $item->img_title );?>" name="photo_galleries[title][<?php echo $i;?>]">
                </div>
                <?php if ( ! is_array( $item ) && $item->video_url != '' && $item->is_video == 1 ) { ?>
                <div style="clear:both"></div>
                <div class="link-wrapper">
                  <label for="galleries-youtube-url-<?php echo $i;?>"><?php _e( 'Youtube Code', 'a3_responsive_slider' ); ?></label>
                  <input type="text" class="galleries-link" id="galleries-youtube-url-<?php echo $i;?>" value="<?php if ( ! is_array( $item ) ) echo $item->video_url;?>" name="photo_galleries[video_url][<?php echo $i;?>]"> <span class="description" style="white-space:nowrap"><?php _e( 'Example', 'a3_responsive_slider' ); ?>: RBumgq5yVrA</span>
                </div>
                <?php } ?>
                <div style="clear:both"></div>
                <div class="link-wrapper">
                  <label for="galleries-link-<?php echo $i;?>"><?php _e( 'Link URL', 'a3_responsive_slider' ); ?></label>
                  <input type="text" class="galleries-link" id="galleries-link-<?php echo $i;?>" value="<?php if ( ! is_array( $item ) ) echo $item->img_link;?>" name="photo_galleries[link][<?php echo $i;?>]">
                </div>
                <div style="clear:both"></div>
                <div class="text-wrapper">
                  <label for="galleries-text-<?php echo $i;?>"><?php _e( 'Caption', 'a3_responsive_slider' ); ?></label>
                  <textarea class="galleries-text" name="photo_galleries[text][<?php echo $i;?>]" id="galleries-text-<?php echo $i;?>"><?php if ( ! is_array( $item ) ) echo stripslashes($item->img_description);?></textarea>
                  <?php
                  	$show_readmore = 0;
                  	if ( isset( $item->show_readmore ) ) {
                  		$show_readmore = $item->show_readmore;
                  	}
                  ?>
                  <div class="galleries-readmore">
                  	<label><input type="checkbox" <?php checked( 1, $show_readmore, true ); ?> name="photo_galleries[show_readmore][<?php echo $i;?>]" id="galleries-readmore-<?php echo $i;?>" value="1" /><?php _e( 'Show Read More Button/Text', 'a3_responsive_slider' ); ?></label>
					<div class="desc"><?php echo __( 'Must have link URL and caption text for Read More button / text to show', 'a3_responsive_slider' ); ?></div>
                  </div>
                </div>
                </div>
              </td>
              <td><a title="<?php _e( 'Reorder Galleries Items', 'a3_responsive_slider' ); ?>" class="icon-move galleries-move" href="#"><span></span></a> <?php if(!$new) {?><a title="<?php _e( 'Delete Item', 'a3_responsive_slider' ); ?>" class="icon-delete galleries-delete-cycle" href="#"><span></span></a><?php }?></td>
        </tr>
		<?php
	}
	
}
?>
