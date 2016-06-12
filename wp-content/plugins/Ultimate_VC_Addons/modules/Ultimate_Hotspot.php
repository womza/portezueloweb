<?php
if(!class_exists('ULT_HotSpot')) {
	class ULT_HotSpot {

		function __construct() {
			// Use this when creating a shortcode addon
			add_shortcode( 'ult_hotspot', array( $this, 'ult_hotspot_callback' ) );
			add_shortcode( 'ult_hotspot_items', array($this, 'ult_hotspot_items_callback' ) );
			
			// We safely integrate with VC with this hook
			add_action( 'init', array( $this, 'ult_hotspot_init' ),99 );
			add_action('admin_enqueue_scripts',array($this, 'enqueue_admin_assets'),999);
			
			// Register CSS and JS
			add_action( 'wp_enqueue_scripts', array( $this, 'ult_hotspot_scripts' ), 1 );
			  
			if(function_exists('add_shortcode_param'))
			{
				add_shortcode_param('ultimate_hotspot_param', array($this, 'ultimate_hotspot_param_callback'), plugins_url('../admin/vc_extend/js/vc-hotspot-param.js',__FILE__));
			}
		}
		
		function ultimate_hotspot_param_callback($settings, $value)
		{
			$dependency = vc_generate_dependencies_attributes($settings);
			$class = isset($settings['class']) ? $settings['class'] : '';
			$output = '<div class="ult-hotspot-image-wrapper '.$class.'">';
				$output .= '<img src="" class="ult-hotspot-image" alt="image"/>';
				$output .= '<div class="ult-hotspot-draggable"></div>';
				$output .= '<input type="hidden" name="'.$settings['param_name'].'" value="'.$value.'" class="ult-hotspot-positions wpb_vc_param_value" '.$dependency.'/>';
			$output .= '</div>';
			return $output;
		}
	  
		function enqueue_admin_assets()
		{
			wp_register_script('hotspt-admin-js', plugins_url( '../admin/vc_extend/js/admin_enqueue_js.js', __FILE__ ),array( 'jquery', 'jquery-ui-core', 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ),ULTIMATE_VERSION,true);
			wp_enqueue_script('hotspt-admin-js');
		}
   
		function ult_hotspot_callback( $atts, $content = null ) {
		
			global $tooltip_continuous_animation;
		
			extract( shortcode_atts( array(
				'main_img'        => '',
				'main_img_size'   => '',
				'main_img_width'  => '',
				'el_class'        => '',
			), $atts ) );
		
			$content = wpb_js_remove_wpautop($content, true); // fix unclosed/unwanted paragraph tags in $content
		
			$mnimg = wp_get_attachment_image_src( $main_img,'full');
		
			$cust_size = '';
			if( $main_img_size== 'main_img_custom'){
				if($main_img_width!='') {   
					$cust_size .= "width:".$main_img_width."px;";  
				}
			}
			$output  = "<div class='ult_hotspot_container ult-hotspot-tooltip-wrapper ".$el_class."' style=".$cust_size.">";
			$output .= "  <img class='ult_hotspot_image' src=".$mnimg[0]." />";
			$output .= "     <div class='utl-hotspot-items ult-hotspot-item'>".do_shortcode($content)."</div>";
			$output .= "     <div style='color:#000;' data-image='".$GLOBALS['hotspot_icon']." ".$GLOBALS['hotspot_icon_bg_color']." ".$GLOBALS['hotspot_icon_color']." ".$GLOBALS['hotspot_icon_size']." ".$GLOBALS['tooltip_continuous_animation']."'></div>";
			$output .= "</div>";
			return $output;
		}
		function ult_hotspot_items_callback( $atts, $content = null ){
		   global $hotspot_icon, $hotspot_icon_bg_color, $hotspot_icon_color, $hotspot_icon_size;
		
			extract( shortcode_atts( array(
				'hotspot_content' 					=> '',
				'hotspot_label'                   	=> '',
				'hotspot_position'              	=> '0,0',
				'tooltip_content'                 	=> '',
				'tooltip_width'                   	=> '300',
				'tooltip_padding'                 	=> '',
				'tooltip_position'                	=> '',
				"icon_type"							=> '',
				'icon'								=> 'Defaults-circle',
				'icon_color'						=> '',
				'icon_style'						=> '',
				'icon_color_bg'						=> '',
				'icon_border_style'					=> '',
				'icon_color_border'					=> '',
				'icon_border_size'					=> '',
				'icon_border_radius'				=> '',
				'icon_border_spacing'				=> '',
				'icon_img'							=> '',
				'img_width'							=> '60',
				'icon_size'							=> '',
				"alignment"							=>	"center",
				'tooltip_trigger'                 	=> '',
				'tooltip_animation'               	=> '',
				'tooltip_continuous_animation'    	=> '',
				'enable_bubble_arrow'             	=> '',
				'tooltip_custom_bg_color'         	=> '',
				'tooltip_custom_color'            	=> '',
				'tooltip_font'                    	=> '',
				'tooltip_font_style'              	=> '',
				'tooltip_font_size'               	=> '',
				'tooltip_font_line_height'        	=> '',
				'tooltip_custom_border_size'      	=> '',
				'tooltip_align'						=> '',
			), $atts ) 	);
				
			//$content = wpb_js_remove_wpautop($content, false); // fix unclosed/unwanted paragraph tags in $content

			$animatn = $tooltip_continuous_animation;
		
			if($animatn == "on"){
			  $pulse = "ult-pulse";
			}
			else{
			  $pulse = "";
			}
			
			
			if(trim($content) !== '')
				$hotspot_content = $content;
		
		
		  /**    Tooltip [Content] Styling 
		   *--------------------------------------*/
		  $font_args = array();
		  $tooltip_content_style = '';
		  $tooltip_base_style = '';
		
		  if($tooltip_font != '') {
			$font_family = get_ultimate_font_family($tooltip_font);
			$tooltip_content_style .= 'font-family:'.$font_family.';';
			array_push($font_args, $tooltip_font);
		  }
		  if($tooltip_font_style != '') { $tooltip_content_style .= get_ultimate_font_style($tooltip_font_style); }
		  if($tooltip_font_size != '') { $tooltip_content_style .= 'font-size:'.$tooltip_font_size.'px;'; }
		  if($tooltip_font_line_height != '') { $tooltip_content_style .= 'line-height:'.$tooltip_font_line_height.'px;'; }
		
		  //  Width
		  if($tooltip_width!=''){ $tooltip_content_style .= 'width:' .$tooltip_width. 'px;'; }
		
		  //  Padding
		  if($tooltip_padding!=''){ $tooltip_content_style .= $tooltip_padding; }
		
		  /**
		   *    Tooltip [Base] Styling options
		   *
		   */
		  //  Background
		  if($tooltip_custom_bg_color!=''){ $tooltip_base_style .= 'background-color:'   .$tooltip_custom_bg_color.  ';'; }
		
		  /*if($tooltip_theme == 'custom' ) {*/
		  if($tooltip_custom_color!=''){ $tooltip_base_style .=  'color:'   .$tooltip_custom_color.  ';'; }
		  
		  //  Border Styling
		  if($tooltip_custom_border_size!=''){             
			$bstyle = str_replace( '|', '', $tooltip_custom_border_size );
			$tooltip_base_style .= $bstyle;
		  }
		  if($tooltip_align!=''){
		  	$tooltip_base_style .= 'text-align:'.$tooltip_align.';';
		  }

		
			$data = '';
			if($tooltip_content_style!='')  { $data .= 'data-tooltip-content-style="'.$tooltip_content_style. '"'; }
			if($tooltip_base_style!='')     { $data .= 'data-tooltip-base-style="'.$tooltip_base_style. '"'; }
		
			
			if($enable_bubble_arrow!='' && $enable_bubble_arrow == 'on') {
			  $data .= ' data-bubble-arrow="true" '; 
			} else { 
			  $data .= ' data-bubble-arrow="false" ';
			}
			
			
			$hotspot_position = explode(',', $hotspot_position);
			if($icon_type == 'custom')
				$temp_icon_size = ($img_width/2)-14;
			else
				$temp_icon_size = ($icon_size/2)-14;
				
				//$temp_icon_size = 0;

			$hotspot_x_position = $hotspot_position[0];
			$hotspot_y_position = (isset($hotspot_position[1])) ? $hotspot_position[1] : '0';
			
			$tooltip_offsetY = '';

			//if($icon_size != '')  { 
				//  set offsetY for tooltip
				$tooltip_offsetY = $temp_icon_size;
			//}
			
			if($tooltip_animation!='')      { $data .= 'data-tooltipanimation="'.$tooltip_animation.'"';}
			if($tooltip_trigger!='')        { $data .= 'data-trigger="'.$tooltip_trigger.'"';}
			if($tooltip_offsetY!='')        { $data .= 'data-tooltip-offsety="'.$tooltip_offsetY.'"';}
			if($tooltip_position!='')       { $data .= 'data-arrowposition="'.$tooltip_position.'"';}
			
			$icon_animation = '';
			$icon_inline = do_shortcode('[just_icon icon_align="'.$alignment.'" icon_type="'.$icon_type.'" icon="'.$icon.'" icon_img="'.$icon_img.'" img_width="'.$img_width.'" icon_size="'.$icon_size.'" icon_color="'.$icon_color.'" icon_style="'.$icon_style.'" icon_color_bg="'.$icon_color_bg.'" icon_color_border="'.$icon_color_border.'"  icon_border_style="'.$icon_border_style.'" icon_border_size="'.$icon_border_size.'" icon_border_radius="'.$icon_border_radius.'" icon_border_spacing="'.$icon_border_spacing.'" icon_animation="'.$icon_animation.'"]');
		
			$output  = "<div class='ult-hotspot-item ".$pulse."' style='top:-webkit-calc(".$hotspot_x_position."% - ".$temp_icon_size."px);top:-moz-calc(".$hotspot_x_position."% - ".$temp_icon_size."px);top:calc(".$hotspot_x_position."% - ".$temp_icon_size."px);left: -webkit-calc(".$hotspot_y_position."% - ".$temp_icon_size."px);left: -moz-calc(".$hotspot_y_position."% - ".$temp_icon_size."px);left: calc(".$hotspot_y_position."% - ".$temp_icon_size."px);' >";
			$output .= "   <a ".$data." class='ult-tooltip ult-tooltipstered ult-hotspot-tooltip' href='#'>";
				$output .= $icon_inline;
				$output .= "<span class='hotspot-tooltip-content'>".esc_html( str_replace('"', '\'', $hotspot_content ) )."</span>";
			$output .= "  </a>";
			$output .= "</div>";
		
			return $output;
		}
		function ult_hotspot_init() {
			if(function_exists("vc_map")){
				vc_map( array(
					"name" => __("Hotspot", 'ultimate_vc'),
					"base" => "ult_hotspot",
					"as_parent" => array('only' => 'ult_hotspot_items'),
					"content_element" => true,
					"show_settings_on_create" => true,
					"category" => 'Ultimate VC Addons',
					"icon" => "ult_hotspot",
					"class" => "ult_hotspot",
					"description" => __("Display Hotspot on Image.", 'ultimate_vc'),
					"is_container"    => false,
					"params" => array(
							array(
								"type" => "attach_image",
								"class" => "",
								"heading" => __("Select Hotspot Image", 'ultimate_vc'),
								"param_name" => "main_img",
								"value" =>"",
								/*"description" => __("Add Hotspot image.", 'ultimate'),*/
								/*"holder" =>"div"*/
							),
							array(
								"type" => "dropdown",
								"class" => "",
								"heading" => __("Image Size", 'ultimate_vc'),
								"param_name" => "main_img_size",
								"value" => array(
									"Default / Full Size" => "main_img_original",
									"Custom" => "main_img_custom",
								),
								/*"description" => __("Select Hotspot image size.", 'ultimate')*/
							),
							array(
								"type" => "number",
								"heading" => __("Image Width", 'ultimate_vc'),
								"class" => "",
								"value" => "",
								"suffix" => "px",
								"param_name" => "main_img_width",
								/*"description" => __("Enter image Width, Height will calculate automatically.", 'ultimate'),*/
								"dependency" => array("element" => "main_img_size", "value" => "main_img_custom" ),
							),
							array(
								"type" => "textfield",
								"heading" => __("Extra Class Name", "ultimate_vc"),
								"param_name" => "el_class",
								"description" => __("Ran out of options? Need more styles? Write your own CSS and mention the class name here.", "ultimate_vc")
							)
						),
						"js_view" => 'ULTHotspotContainerView'
				) );
			
				global $ultimate_hostspot_image;
				vc_map( array(
					  "name" => __("Hotspot Item", 'ultimate_vc'),
					  "base" => "ult_hotspot_items",
					  "content_element" => true,
					  "as_child" => array('only' => 'ult_hotspot'),
					  "icon" => "ult_hotspot",
					  "class" => "ult_hotspot",    
					  "js_view" => "ULTHotspotSingleView", 
					  "params" => array(
					  			array(
									'type' => 'ultimate_hotspot_param',
									'heading' => 'Position',
									'param_name' => 'hotspot_position'
								),
								/*array(
									"type" => "number",
									"class" => "",
									"heading" => __("Horizontal Position", 'ultimate'),
									"param_name" => "hotspot_x_position",
									"value" => "50",
									"admin_label" => true,
								),
								array(
									"type" => "number",
									"class" => "",
									"admin_label" => true,
									"heading" => __("Vertical Position", 'ultimate'),
									"param_name" => "hotspot_y_position",
									"value" => "50",
									"suffix"  => "%",
								),*/
								// Hotspot Icon
								array(
								"type" => "dropdown",
								"class" => "",
								"heading" => __("Icon to display:", "ultimate_vc"),
								"param_name" => "icon_type",
								"value" => array(
									__("Font Icon Manager","ultimate_vc") => "selector",
									__("Custom Image Icon","ultimate_vc") => "custom",
								),
								"description" => __("Use an existing font icon or upload a custom image.", "ultimate_vc"),
								"dependency" => Array("element" => "spacer", "value" => array("line_with_icon","icon_only")),
							),
							array(
								"type" => "icon_manager",
								"class" => "",
								"heading" => __("Select Icon ","ultimate_vc"),
								"param_name" => "icon",
								"value" => "",
								"description" => __("Click and select icon of your choice. If you can't find the one that suits for your purpose","ultimate_vc").", ".__("you can","ultimate_vc")." <a href='admin.php?page=font-icon-Manager' target='_blank'>".__("add new here","ultimate_vc")."</a>.",
								"dependency" => Array("element" => "icon_type","value" => array("selector")),
							),
							array(
								"type" => "number",
								"class" => "",
								"heading" => __("Size of Icon", "ultimate_vc"),
								"param_name" => "icon_size",
								"value" => 32,
								"min" => 12,
								"max" => 72,
								"suffix" => "px",
								"description" => __("How big would you like it?", "ultimate_vc"),
								"dependency" => Array("element" => "icon_type","value" => array("selector")),
							),
							array(
								"type" => "colorpicker",
								"class" => "",
								"heading" => __("Color", "ultimate_vc"),
								"param_name" => "icon_color",
								"value" => "",
								"description" => __("Give it a nice paint!", "ultimate_vc"),
								"dependency" => Array("element" => "icon_type","value" => array("selector")),						
							),
							array(
								"type" => "dropdown",
								"class" => "",
								"heading" => __("Icon Style", "ultimate_vc"),
								"param_name" => "icon_style",
								"value" => array(
									__("Simple","ultimate_vc") => "none",
									__("Circle Background","ultimate_vc") => "circle",
									__("Square Background","ultimate_vc") => "square",
									__("Design your own","ultimate_vc") => "advanced",
								),
								"description" => __("We have given three quick preset if you are in a hurry. Otherwise, create your own with various options.", "ultimate_vc"),
								"dependency" => Array("element" => "icon_type","value" => array("selector")),
							),
							array(
								"type" => "colorpicker",
								"class" => "",
								"heading" => __("Background Color", "ultimate_vc"),
								"param_name" => "icon_color_bg",
								"value" => "",
								"description" => __("Select background color for icon.", "ultimate_vc"),	
								"dependency" => Array("element" => "icon_style", "value" => array("circle","square","advanced")),
							),
							array(
								"type" => "dropdown",
								"class" => "",
								"heading" => __("Icon Border Style", "ultimate_vc"),
								"param_name" => "icon_border_style",
								"value" => array(
									__("None","ultimate_vc") => "",
									__("Solid","ultimate_vc") => "solid",
									__("Dashed","ultimate_vc") => "dashed",
									__("Dotted","ultimate_vc") => "dotted",
									__("Double","ultimate_vc") => "double",
									__("Inset","ultimate_vc") => "inset",
									__("Outset","ultimate_vc") => "outset",
								),
								"description" => __("Select the border style for icon.","ultimate_vc"),
								"dependency" => Array("element" => "icon_style", "value" => array("advanced")),
							),
							array(
								"type" => "colorpicker",
								"class" => "",
								"heading" => __("Border Color", "ultimate_vc"),
								"param_name" => "icon_color_border",
								"value" => "#333333",
								"description" => __("Select border color for icon.", "ultimate_vc"),	
								"dependency" => Array("element" => "icon_border_style", "not_empty" => true),
							),
							array(
								"type" => "number",
								"class" => "",
								"heading" => __("Border Width", "ultimate_vc"),
								"param_name" => "icon_border_size",
								"value" => 1,
								"min" => 1,
								"max" => 10,
								"suffix" => "px",
								"description" => __("Thickness of the border.", "ultimate_vc"),
								"dependency" => Array("element" => "icon_border_style", "not_empty" => true),
							),
							array(
								"type" => "number",
								"class" => "",
								"heading" => __("Border Radius", "ultimate_vc"),
								"param_name" => "icon_border_radius",
								"value" => 500,
								"min" => 1,
								"max" => 500,
								"suffix" => "px",
								"description" => __("0 pixel value will create a square border. As you increase the value, the shape convert in circle slowly. (e.g 500 pixels).", "ultimate_vc"),
								"dependency" => Array("element" => "icon_border_style", "not_empty" => true),
							),
							array(
								"type" => "number",
								"class" => "",
								"heading" => __("Background Size", "ultimate_vc"),
								"param_name" => "icon_border_spacing",
								"value" => 50,
								"min" => 30,
								"max" => 500,
								"suffix" => "px",
								"description" => __("Spacing from center of the icon till the boundary of border / background", "ultimate_vc"),
								"dependency" => Array("element" => "icon_style", "value" => array("advanced")),
							),
							array(
								"type" => "attach_image",
								"class" => "",
								"heading" => __("Upload Image Icon:", "ultimate_vc"),
								"param_name" => "icon_img",
								"value" => "",
								"description" => __("Upload the custom image icon.", "ultimate_vc"),
								"dependency" => Array("element" => "icon_type","value" => array("custom")),
							),
							array(
								"type" => "number",
								"class" => "",
								"heading" => __("Image Width", "ultimate_vc"),
								"param_name" => "img_width",
								"value" => 48,
								"min" => 16,
								"max" => 512,
								"suffix" => "px",
								"description" => __("Provide image width", "ultimate_vc"),
								"dependency" => Array("element" => "icon_type","value" => array("custom")),
							),
								array(
									"type" => "textarea_html",
									"class" => "",
									"value" => "Tooltip content goes here!",
									"heading" => __("Hotspot Tooltip Content", 'ultimate_vc'),
									/*"param_name" => "tooltip_content",*/
									/*"admin_label" => true,*/
									"param_name" => "content",
									/*"description" => __("Enter the content for tooltip.", 'ultimate')*/
								),
			
								array(
									"type" => "ult_switch",
									"class" => "",
									"heading" => __("Continuous Pulse Animation For Hotspot", "ultimate_vc"),
									"param_name" => "tooltip_continuous_animation",
									// "admin_label" => true,
									"value" => "on",
									"options" => array(
										"on" => array(
											"label" => __("Enable Pulse Animation?","ultimate_vc"),
											"on" => __("Yes","ultimate_vc"),
											"off" => __("No","ultimate_vc"),
										  ),
									  ),
									/*"description" => __("", "smile"),*/
									"description" => __("Animate tooltip continuously or not", 'ultimate_vc'),
								),
			
								// Tooltip
								array(
								  "type" => "colorpicker",
								  "class" => "",
								  "heading" => __("Tooltip Text Color", "ultimate_vc"),
								  "param_name" => "tooltip_custom_color",
								  /*"edit_field_class" => "vc_col-sm-6",*/
								  "value" => "#333333",
								  /*"dependency" => array("element" => "tooltip_theme", "value" => "custom"),*/
								  "group" => "Tooltip",
								  /*"description" => __("Select the color for tooltip text.", "smile"),                */
								),
								array(
									"type" => "colorpicker",
									"class" => "",
									"heading" => __("Background Color", "ultimate_vc"),
									"param_name" => "tooltip_custom_bg_color",
									/*"edit_field_class" => "vc_col-sm-6",*/
									"value" => "#F9F9F9",
									/*"dependency" => array("element" => "tooltip_theme", "value" => "custom"),*/
									"group" => "Tooltip",
									/*"description" => __("Select the color for tooltip background.", "smile"),                */
								),
								array(
									"type" => "number",
									"class" => "",
									"value" => "300",
									"heading" => __("Width", 'ultimate_vc'),
									"param_name" => "tooltip_width",
									"group" => "Tooltip",
									"suffix"  => "px",
									"description" => __("Tooltip Default width: auto.", 'ultimate')
								),
								array(
									"type" => "dropdown",
									"class" => "",
									"heading" => __("Trigger On", 'ultimate_vc'),
									"param_name" => "tooltip_trigger",
									"value" => array("Hover" => "hover", "Click" => "click"),
									"group" => "Tooltip",
									/*"description" => __("When to display the tooltip onclick or hover", 'ultimate')*/
								),
								array(
									"type" => "dropdown",
									"class" => "",
									"heading" => __("Position", 'ultimate_vc'),
									"param_name" => "tooltip_position",
									"value" => array(
										__("Top","ultimate_vc") => "top",
										__("Bottom","ultimate_vc") => "bottom",
										__("Left","ultimate_vc") => "left",
										__("Right","ultimate_vc") => "right"
									),
									"group" => "Tooltip",
									/*"description" => __("Set position of tooltip.", 'ultimate')*/
								),
							   
								array(
									"type" => "ultimate_border",
									"heading" => __("Border","ultimate_vc"),
									"param_name" => "tooltip_custom_border_size",
									"unit"     => "px",                        //  [required] px,em,%,all     Default all
									"positions" => array(
										__("Top","ultimate_vc")     => "",
										__("Right","ultimate_vc")   => "",
										__("Bottom","ultimate_vc")  => "",
										__("Left","ultimate_vc")    => ""
									),
									//"enable_radius" => false,                   //  Enable border-radius. default true
									"radius" => array(                          
										__("Top Left","ultimate_vc")        => "3",                // use 'Top Left'
									  	__("Top Right","ultimate_vc")       => "3",                  // use 'Top Right'
									  	__("Bottom Right","ultimate_vc")    => "3",                // use 'Bottom Right'
									 	__("Bottom Left","ultimate_vc")     => "3"                   // use 'Bottom Left'
									),
									"label_color"   => __("Border Color","ultimate_vc"),       //  label for 'border color'   default 'Border Color'
									"label_radius"  => __("Border Radius","ultimate_vc"),        //  label for 'radius'  default 'Border Redius'
									//"label_border"  => "Border Style",       //  label for 'style'   default 'Border Style'
									"group" => "Tooltip",
								),
								
								array(
									"type" => "ult_switch",
									"class" => "",
									"heading" => __("Arrow", "ultimate_vc"),
									"param_name" => "enable_bubble_arrow",
									// "admin_label" => true,
									"value" => "on",
									"options" => array(
										"on" => array(
											"label" => __("Enable Tooltip Arrow?","ultimate_vc"),
											"on" => __("Yes","ultimate_vc"),
											"off" => __("No","ultimate_vc"),
										  ),
									  ),
									/*"description" => __("", "smile"),*/
									/*"description" => __("Hide or Show Tooltip Arrow. Default: Hide. ", 'ultimate'),*/
									"group" => "Tooltip",
								),
							  
								array(
									"type" => "dropdown",
									"class" => "",
									"heading" => __("Appear Animation", 'ultimate_vc'),
									"param_name" => "tooltip_animation",
									"value" => array(
										__("Fade","ultimate_vc")=>"fade",
										__("Glow","ultimate_vc")=>"glow",
										__("Swing","ultimate_vc")=>"swing",
										__("Slide","ultimate_vc")=>"slide",
										__("Fall","ultimate_vc")=>"fall",
										__("Euclid","ultimate_vc")=>"euclid"
									),
									"group" => "Tooltip",
									/*"description" => __("Tooltip appearance", 'ultimate')*/
								),
							   
								array(
									"type" => "ultimate_spacing",
									"heading" => __("Padding", "ultimate_vc"),
									"param_name" => "tooltip_padding",
									"mode"  => "padding",                    //  margin/padding
									"unit"  => "px",                        //  [required] px,em,%,all     Default all
									"positions" => array(                   //  Also set 'defaults'
									  __("Top","ultimate_vc")     => "",
									  __("Right","ultimate_vc")   => "",
									  __("Bottom","ultimate_vc")  => "",
									  __("Left","ultimate_vc")    => ""
									),
									"group" => "Tooltip",
									/*"description" => __("Thickness of the Tooltip Border. E.g. 5px 10px 5px 10px or 5px 10px etc.", "ultimate"),*/
									/*"dependency" => array("element" => "tooltip_custom_border_style", "not_empty" => true),*/
								),
			
								array(
								  "type" => "ultimate_google_fonts",
								  "heading" => __("Font Family","ultimate_vc"),
								  "param_name" => "tooltip_font",
								  "value" => "",
								  "group" => "Typography"
								),
								array(
								  "type" => "ultimate_google_fonts_style",
								  "heading" => __("Font Style","ultimate_vc"),
								  "param_name" => "tooltip_font_style",
								  "value" => "",
								  "group" => "Typography"
								),
								array(
								  "type" => "number",
								  "param_name" => "tooltip_font_size",
								  "heading" => __("Font size","ultimate_vc"),
								  "value" => "12",
								  "suffix" => "px",
								  "min" => 10,
								  "group" => "Typography"
								),
								array(
								  "type" => "number",
								  "param_name" => "tooltip_font_line_height",
								  "heading" => __("Line Height","ultimate_vc"),
								  "value" => "18",
								  "suffix" => "px",
								  "min" => 10,
								  "group" => "Typography"
								),
								array(
									"type" => "dropdown",
									"class" => "",
									"heading" => __("Text Align", 'ultimate_vc'),
									"param_name" => "tooltip_align",
									"value" => array(
										__("Left","ultimate_vc") 	=> "left",
										__("Center","ultimate_vc") 	=> "center",
										__("Right","ultimate_vc") 	=> "right",
										__("Justify","ultimate_vc") 	=> "justify"
									),
									"group" => "Typography"
								),
					  )
				) );
			}
		}

		function ult_hotspot_scripts() {
			//  css
			//wp_register_style( 'ult_hotspot_tooltip_min_css',plugins_url( 'assets/css/hotspot-tooltip-min.css', dirname( __FILE__ )) );
			wp_register_style( 'ult_hotspot_css', plugins_url('../assets/min-css/hotspot.min.css', __FILE__ ));
			wp_register_style( 'ult_hotspot_tooltipster_css',plugins_url( 'assets/min-css/hotspot-tooltipster.min.css', dirname( __FILE__ )) );
			//  js
			wp_register_script( 'ult_hotspot_js',plugins_url( 'assets/min-js/hotspot.min.js', dirname( __FILE__ )) );
			wp_register_script( 'ult_hotspot_tooltipster_js',plugins_url( 'assets/min-js/hotspot-tooltipster.min.js', dirname( __FILE__ )) );
		}
  	}

  	new ULT_HotSpot;

	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	  class WPBakeryShortCode_ult_hotspot extends WPBakeryShortCodesContainer {
	  }
	}
	if ( class_exists( 'WPBakeryShortCode' ) ) {
	  class WPBakeryShortCode_ult_hotspot_items extends WPBakeryShortCode {
	  }
	}

}

?>