<?php
/* "Copyright 2012 A3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
/*-----------------------------------------------------------------------------------
Slider Template Card Read More Settings

TABLE OF CONTENTS

- var parent_tab
- var subtab_data
- var option_name
- var form_key
- var position
- var form_fields
- var form_messages

- __construct()
- subtab_init()
- set_default_settings()
- get_settings()
- subtab_data()
- add_subtab()
- settings_form()
- init_form_fields()

-----------------------------------------------------------------------------------*/

class A3_Responsive_Slider_Template_Card_ReadMore_Settings extends A3_Responsive_Slider_Admin_UI
{
	
	/**
	 * @var string
	 */
	private $parent_tab = 'readmore-card';
	
	/**
	 * @var array
	 */
	private $subtab_data;
	
	/**
	 * @var string
	 * You must change to correct option name that you are working
	 */
	public $option_name = 'a3_rslider_template_card_readmore_settings';
	
	/**
	 * @var string
	 * You must change to correct form key that you are working
	 */
	public $form_key = 'a3_rslider_template_card_readmore_settings';
	
	/**
	 * @var string
	 * You can change the order show of this sub tab in list sub tabs
	 */
	private $position = 8;
	
	/**
	 * @var array
	 */
	public $form_fields = array();
	
	/**
	 * @var array
	 */
	public $form_messages = array();
	
	/*-----------------------------------------------------------------------------------*/
	/* __construct() */
	/* Settings Constructor */
	/*-----------------------------------------------------------------------------------*/
	public function __construct() {
		$this->init_form_fields();
		$this->subtab_init();
		
		$this->form_messages = array(
				'success_message'	=> __( 'Read More Settings successfully saved.', 'a3_responsive_slider' ),
				'error_message'		=> __( 'Error: Read More Settings can not save.', 'a3_responsive_slider' ),
				'reset_message'		=> __( 'Read More Settings successfully reseted.', 'a3_responsive_slider' ),
			);
			
		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_end', array( $this, 'include_script' ) );
			
		add_action( $this->plugin_name . '_set_default_settings' , array( $this, 'set_default_settings' ) );
		
		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_init' , array( $this, 'reset_default_settings' ) );
				
		//add_action( $this->plugin_name . '_get_all_settings' , array( $this, 'get_settings' ) );
		
		add_action( $this->plugin_name . '-'. $this->form_key.'_settings_start', array( $this, 'pro_fields_before' ) );
		add_action( $this->plugin_name . '-'. $this->form_key.'_settings_end', array( $this, 'pro_fields_after' ) );
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* subtab_init() */
	/* Sub Tab Init */
	/*-----------------------------------------------------------------------------------*/
	public function subtab_init() {
		
		add_filter( $this->plugin_name . '-' . $this->parent_tab . '_settings_subtabs_array', array( $this, 'add_subtab' ), $this->position );
		
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* set_default_settings()
	/* Set default settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function set_default_settings() {
		global $a3_responsive_slider_admin_interface;
		
		$a3_responsive_slider_admin_interface->reset_settings( $this->form_fields, $this->option_name, false );
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* reset_default_settings()
	/* Reset default settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function reset_default_settings() {
		global $a3_responsive_slider_admin_interface;
		
		$a3_responsive_slider_admin_interface->reset_settings( $this->form_fields, $this->option_name, true, true );
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* get_settings()
	/* Get settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function get_settings() {
		global $a3_responsive_slider_admin_interface;
		
		$a3_responsive_slider_admin_interface->get_settings( $this->form_fields, $this->option_name );
	}
	
	/**
	 * subtab_data()
	 * Get SubTab Data
	 * =============================================
	 * array ( 
	 *		'name'				=> 'my_subtab_name'				: (required) Enter your subtab name that you want to set for this subtab
	 *		'label'				=> 'My SubTab Name'				: (required) Enter the subtab label
	 * 		'callback_function'	=> 'my_callback_function'		: (required) The callback function is called to show content of this subtab
	 * )
	 *
	 */
	public function subtab_data() {
		
		$subtab_data = array( 
			'name'				=> 'readmore',
			'label'				=> __( 'Read More', 'a3_responsive_slider' ),
			'callback_function'	=> 'a3_responsive_sider_template_card_readmore_settings_form',
		);
		
		if ( $this->subtab_data ) return $this->subtab_data;
		return $this->subtab_data = $subtab_data;
		
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* add_subtab() */
	/* Add Subtab to Admin Init
	/*-----------------------------------------------------------------------------------*/
	public function add_subtab( $subtabs_array ) {
	
		if ( ! is_array( $subtabs_array ) ) $subtabs_array = array();
		$subtabs_array[] = $this->subtab_data();
		
		return $subtabs_array;
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* settings_form() */
	/* Call the form from Admin Interface
	/*-----------------------------------------------------------------------------------*/
	public function settings_form() {
		global $a3_responsive_slider_admin_interface;
		
		$output = '';
		$output .= $a3_responsive_slider_admin_interface->admin_forms( $this->form_fields, $this->form_key, $this->option_name, $this->form_messages );
		
		return $output;
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* init_form_fields() */
	/* Init all fields of this form */
	/*-----------------------------------------------------------------------------------*/
	public function init_form_fields() {
		
  		// Define settings			
     	$this->form_fields = apply_filters( $this->option_name . '_settings_fields', array(
		
			array(
				'name'		=> __( 'Read More Settings', 'a3_responsive_slider' ),
                'type' 		=> 'heading',
           	),
			array(  
				'name' 		=> __( 'Read More', 'a3_responsive_slider' ),
				'id' 		=> 'enable_redmore_link',
				'class'		=> 'enable_redmore_link',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 1,
				'checked_value'		=> 1,
				'unchecked_value' 	=> 0,
				'checked_label'		=> __( 'ON', 'a3_responsive_slider' ),
				'unchecked_label' 	=> __( 'OFF', 'a3_responsive_slider' ),
			),
			
			array(
            	'name' 		=> __( 'Button/Hyperlink', 'a3_responsive_slider' ),
                'type' 		=> 'heading',
				'class'		=> 'readmore_settings_container',
           	),
			array(  
				'name' 		=> __( 'Button or Hyperlink Type', 'a3_responsive_slider' ),
				'id' 		=> 'readmore_bt_type',
				'class' 	=> 'readmore_bt_type',
				'type' 		=> 'switcher_checkbox',
				'default'	=> 'link',
				'checked_value'		=> 'button',
				'unchecked_value'	=> 'link',
				'checked_label'		=> __( 'Button', 'a3_responsive_slider' ),
				'unchecked_label' 	=> __( 'Hyperlink', 'a3_responsive_slider' ),
			),
			array(  
				'name' 		=> __( 'Button or Hyperlink Margin', 'a3_responsive_slider' ),
				'id' 		=> 'readmore_bt_margin',
				'type' 		=> 'array_textfields',
				'ids'		=> array( 
	 								array( 
											'id' 		=> 'readmore_bt_margin_top',
	 										'name' 		=> __( 'Top', 'a3_responsive_slider' ),
	 										'css'		=> 'width:40px;',
	 										'default'	=> 0 ),
	 
	 								array(  'id' 		=> 'readmore_bt_margin_bottom',
	 										'name' 		=> __( 'Bottom', 'a3_responsive_slider' ),
	 										'css'		=> 'width:40px;',
	 										'default'	=> 0 ),
											
									array( 
											'id' 		=> 'readmore_bt_margin_left',
	 										'name' 		=> __( 'Left', 'a3_responsive_slider' ),
	 										'css'		=> 'width:40px;',
	 										'default'	=> 5 ),
											
									array( 
											'id' 		=> 'readmore_bt_margin_right',
	 										'name' 		=> __( 'Right', 'a3_responsive_slider' ),
	 										'css'		=> 'width:40px;',
	 										'default'	=> 0 ),
	 							)
			),
			
			array(
            	'name' 		=> __( 'Hyperlink Styling', 'a3_responsive_slider' ),
                'type' 		=> 'heading',
          		'class'		=> 'readmore_settings_container show_readmore_hyperlink_styling'
           	),
			array(  
				'name' => __( 'Hyperlink Text', 'a3_responsive_slider' ),
				'id' 		=> 'readmore_link_text',
				'type' 		=> 'text',
				'default'	=> __('Read More', 'a3_responsive_slider')
			),
			array(  
				'name' 		=> __( 'Hyperlink Font', 'a3_responsive_slider' ),
				'id' 		=> 'readmore_link_font',
				'type' 		=> 'typography',
				'default'	=> array( 'size' => '12px', 'face' => 'Arial', 'style' => 'bold', 'color' => '#000000' )
			),
			
			array(  
				'name' 		=> __( 'Hyperlink hover Colour', 'a3_responsive_slider' ),
				'id' 		=> 'readmore_link_font_hover_color',
				'type' 		=> 'color',
				'default'	=> '#999999'
			),
			
			array(
            	'name' 		=> __( 'Button Styling', 'a3_responsive_slider' ),
                'type' 		=> 'heading',
          		'class' 	=> 'readmore_settings_container show_readmore_button_styling'
           	),
			array(  
				'name' 		=> __( 'Button Text', 'a3_responsive_slider' ),
				'id' 		=> 'readmore_bt_text',
				'type' 		=> 'text',
				'default'	=> __('Read More', 'a3_responsive_slider')
			),
			array(  
				'name' 		=> __( 'Button Padding', 'a3_responsive_slider' ),
				'desc' 		=> __( 'Padding from Button text to Button border', 'a3_responsive_slider' ),
				'id' 		=> 'readmore_bt_padding',
				'type' 		=> 'array_textfields',
				'ids'		=> array( 
	 								array(  'id' 		=> 'readmore_bt_padding_tb',
	 										'name' 		=> __( 'Top/Bottom', 'a3_responsive_slider' ),
	 										'class' 	=> '',
	 										'css'		=> 'width:40px;',
	 										'default'	=> '7' ),
	 
	 								array(  'id' 		=> 'readmore_bt_padding_lr',
	 										'name' 		=> __( 'Left/Right', 'a3_responsive_slider' ),
	 										'class' 	=> '',
	 										'css'		=> 'width:40px;',
	 										'default'	=> '8' ),
	 							)
			),
			array(  
				'name' 		=> __( 'Background Colour', 'a3_responsive_slider' ),
				'desc' 		=> __( 'Default', 'a3_responsive_slider' ) . ' [default_value]',
				'id' 		=> 'readmore_bt_bg',
				'type' 		=> 'color',
				'default'	=> '#EE2B2B'
			),
			array(  
				'name' 		=> __( 'Background Colour Gradient From', 'a3_responsive_slider' ),
				'desc' 		=> __( 'Default', 'a3_responsive_slider' ) . ' [default_value]',
				'id' 		=> 'readmore_bt_bg_from',
				'type' 		=> 'color',
				'default'	=> '#FBCACA'
			),
			
			array(  
				'name' 		=> __( 'Background Colour Gradient To', 'a3_responsive_slider' ),
				'desc' 		=> __( 'Default', 'a3_responsive_slider' ) . ' [default_value]',
				'id' 		=> 'readmore_bt_bg_to',
				'type' 		=> 'color',
				'default'	=> '#EE2B2B'
			),
			array(  
				'name' 		=> __( 'Button Border', 'a3_responsive_slider' ),
				'id' 		=> 'readmore_bt_border',
				'type' 		=> 'border',
				'default'	=> array( 'width' => '1px', 'style' => 'solid', 'color' => '#EE2B2B', 'corner' => 'rounded' , 'rounded_value' => 3 ),
			),
			array(  
				'name' 		=> __( 'Button Font', 'a3_responsive_slider' ),
				'id' 		=> 'readmore_bt_font',
				'type' 		=> 'typography',
				'default'	=> array( 'size' => '12px', 'face' => 'Arial', 'style' => 'bold', 'color' => '#FFFFFF' )
			),
			array(  
				'name' => __( 'Button Shadow', 'a3_responsive_slider' ),
				'id' 		=> 'readmore_bt_shadow',
				'type' 		=> 'box_shadow',
				'default'	=> array( 'enable' => 0, 'h_shadow' => '5px' , 'v_shadow' => '5px', 'blur' => '2px' , 'spread' => '2px', 'color' => '#999999', 'inset' => '' )
			),
			
        ));
	}
	
	public function include_script() {
	?>
<script>
(function($) {
$(document).ready(function() {
	
	if ( $("input.readmore_bt_type:checked").val() == 'button') {
		$(".show_readmore_button_styling").css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
		$(".show_readmore_hyperlink_styling").css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden'} );
	} else {
		$(".show_readmore_button_styling").css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden'} );
		$(".show_readmore_hyperlink_styling").css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
	}
	
	if ( $("input.enable_redmore_link:checked").val() == '1') {
		$(".readmore_settings_container").css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
		if ( $("input.readmore_bt_type:checked").val() == 'button') {
			$(".show_readmore_hyperlink_styling").css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden'} );
		} else {
			$(".show_readmore_button_styling").css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden'} );
		}
	} else {
		$(".readmore_settings_container").css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden'} );
	}
	
	$(document).on( "a3rev-ui-onoff_checkbox-switch", '.readmore_bt_type', function( event, value, status ) {
		$(".show_readmore_button_styling").hide().css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
		$(".show_readmore_hyperlink_styling").hide().css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
		if ( status == 'true') {
			$(".show_readmore_button_styling").slideDown();
			$(".show_readmore_hyperlink_styling").slideUp();
		} else {
			$(".show_readmore_button_styling").slideUp();
			$(".show_readmore_hyperlink_styling").slideDown();
		}
	});
	
	$(document).on( "a3rev-ui-onoff_checkbox-switch", '.enable_redmore_link', function( event, value, status ) {
		$(".readmore_settings_container").hide().css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
		if ( status == 'true' ) {
			$(".readmore_settings_container").slideDown();
			if ( $("input.readmore_bt_type:checked").val() == 'button') {
				$(".show_readmore_hyperlink_styling").hide();
			} else {
				$(".show_readmore_button_styling").hide();
			}
		} else {
			$(".readmore_settings_container").slideUp();
		}
	});
});
})(jQuery);
</script>
    <?php	
	}
}

global $a3_responsive_sider_template_card_readmore_settings;
$a3_responsive_sider_template_card_readmore_settings = new A3_Responsive_Slider_Template_Card_ReadMore_Settings();

/** 
 * a3_responsive_sider_template_card_readmore_settings_form()
 * Define the callback function to show subtab content
 */
function a3_responsive_sider_template_card_readmore_settings_form() {
	global $a3_responsive_sider_template_card_readmore_settings;
	$a3_responsive_sider_template_card_readmore_settings->settings_form();
}

?>