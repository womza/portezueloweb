<?php
/*
* Add-on Name: Image Separator
*/
if(!class_exists('Ultimate_Image_Separator')) 
{
	class Ultimate_Image_Separator{
		function __construct(){
			add_action('init',array($this,'ultimate_img_separator_init'));
			add_shortcode('ultimate_img_separator',array($this,'ultimate_img_separator_shortcode'));
			add_action('wp_enqueue_scripts', array($this, 'register_easy_separator_assets'),1);
		}
		function register_easy_separator_assets()
		{
			wp_register_style('ult-easy-separator-style',plugins_url('../assets/min-css/easy-separator.min.css',__FILE__),array(), ULTIMATE_VERSION);
			wp_register_script('ult-easy-separator-script',plugins_url('../assets/min-js/easy-separator.min.js',__FILE__),array('jquery'), ULTIMATE_VERSION);
		}
		function ultimate_img_separator_init(){
			if(function_exists('vc_map'))
			{
				vc_map(
					array(
					   "name" => __("Image Separator","ultimate_vc"),
					   "base" => "ultimate_img_separator",
					   "class" => "vc_img_separator_icon",
					   "icon" => "vc_icon_img_separator",
					   "category" => "Ultimate VC Addons",
					   //"description" => __("Displays the banner image with Information","smile"),
					   "params" => array(
							array(
								'type' => 'attach_image',
								'heading' => __('Image','ultimate_vc'),
								'param_name' => 'img_separator',
							),
							array(
								"type" => "animator",
								"class" => "",
								"heading" => __("Animation","ultimate_vc"),
								"param_name" => "animation",
								"value" => "",
								//"description" => __("","smile"),
						  	),
							array(
								"type" => "number",
								"class" => "",
								"heading" => __("Animation Duration","ultimate_vc"),
								"param_name" => "animation_duration",
								"value" => 3,
								"min" => 1,
								"max" => 100,
								"suffix" => "s",
								"description" => __("How long the animation effect should last. Decides the speed of effect.","ultimate_vc"),
						  	),
							array(
								"type" => "number",
								"class" => "",
								"heading" => __("Animation Delay","ultimate_vc"),
								"param_name" => "animation_delay",
								"value" => 0,
								"min" => 1,
								"max" => 100,
								"suffix" => "s",
								"description" => __("Delays the animation effect for seconds you enter above.","ultimate_vc"),
						  	),
							array(
								"type" => "number",
								"class" => "",
								"heading" => __("Animation Repeat Count","ultimate_vc"),
								"param_name" => "animation_iteration_count",
								"value" => 1,
								"min" => 0,
								"max" => 100,
								"suffix" => "",
								"description" => __("The animation effect will repeat to the count you enter above. Enter 0 if you want to repeat it infinitely.","ultimate_vc"),
						  	),
							array(
								"type" => "number",
								"class" => "",
								"heading" => __("Viewport Position", "ultimate_vc"),
								"param_name" => "opacity_start_effect",
								"suffix" => "%",
								//"admin_label" => true,
								"value" => "90",
								"description" => __("The area of screen from top where animation effects will start working.", "ultimate_vc"),
							),

							array(
								'type' => 'ultimate_responsive',
								'heading' => __('Width','ultimate_vc'),
								'unit'  => 'px',                                  // use '%' or 'px'
								'media' => array(
									'Desktop'           => '',                  // Here '28' is default value set for 'Desktop'
									'Tablet'           => '',
									'Tablet Portrait'   => '',
									'Mobile Landscape'  => '',
									'Mobile'            => '',
								),
								'param_name' => 'img_separator_width'
							),
							array(
								'type' => 'dropdown',
								'heading' => __('Position','ultimate_vc'),
								'param_name' => 'img_separator_position',
								'value' => array(
									__('Top','ultimate_vc') => 'ult-top-easy-separator',
									__('Bottom','ultimate_vc') => 'ult-bottom-easy-separator',
								)
							),
							array(
								'type' => 'number',
								'heading' => __('Gutter','ultimate_vc'),
								'param_name' => 'img_separator_gutter',
								'suffix' => '%',
								'decription' => __('This value will help you set cusom position for the image. Read documntation in details here.','ultimate_vc')
							)
						),
					)
				);
			}
		}
		// Shortcode handler function for stats banner
		function ultimate_img_separator_shortcode($atts, $content)
		{			
			$output = $wrapper_class = $custom_position = $opacity_start_effect_data = $animation_style = $animation_el_class = '';
			extract(shortcode_atts( array(
				'img_separator' => '',
				'animation' => '',
				'img_separator_width' => '',
				'img_separator_position' => '',
				'img_separator_gutter' => '',
				'opacity' => 'set',
				'opacity_start_effect' => '',
				'animation_duration' => '',
				'animation_delay' => '',
				'animation_iteration_count' => ''
			),$atts));
			
			$ultimate_custom_vc_row = get_option('ultimate_custom_vc_row');
			if($ultimate_custom_vc_row == '')
				$ultimate_custom_vc_row = 'wpb_row';
			
			$img = wp_get_attachment_image_src( $img_separator, 'full');
			$alt = get_post_meta($img_separator, '_wp_attachment_image_alt', true);
			
			$id = 'ult-easy-separator-'.uniqid(rand());
			
			$args = array(
				'target'      =>  '#'.$id,  // set targeted element e.g. unique class/id etc.
				'media_sizes' => array(
				   'width' => $img_separator_width
				), 
			);
			$data_list = get_ultimate_vc_responsive_media_css($args);
			
			if($img_separator_gutter != '')
			{
				$wrapper_class = 'ult-easy-separator-no-default';
				if($img_separator_position == 'ult-top-easy-separator')
				{
					$custom_position = 'top:'.$img_separator_gutter.'%;';
				}
				else if($img_separator_position == 'ult-bottom-easy-separator')
				{
					$custom_position = 'bottom:'.$img_separator_gutter.'%;';
				}
			}
			
			$inifinite_arr = array("InfiniteRotate", "InfiniteDangle","InfiniteSwing","InfinitePulse","InfiniteHorizontalShake","InfiniteBounce","InfiniteFlash","InfiniteTADA");
			if($animation_iteration_count == 0 || in_array($animation,$inifinite_arr)){
				$animation_iteration_count = 'infinite';
				$animation = 'infinite '.$animation;
			}
			
			if($opacity == "set"){
				$animation_style .= 'opacity:0;';
				$animation_el_class .= ' ult-animate-viewport ';
				$opacity_start_effect_data = 'data-opacity_start_effect="'.$opacity_start_effect.'"';
			}
			
			$output = '<div id="'.$id.'" class="ult-easy-separator-wrapper ult-responsive '.$img_separator_position.' '.$wrapper_class.'" style="'.$custom_position.'" data-vc-row="'.$ultimate_custom_vc_row.'" '.$data_list.'>';
				$output .= '<div class="ult-easy-separator-inner-wrapper">';
					$output .= '<div class="ult-animation '.$animation_el_class.'" style="'.$animation_style.'" data-animate="'.$animation.'" data-animation-delay="'.$animation_delay.'" data-animation-duration="'.$animation_duration.'" data-animation-iteration="'.$animation_iteration_count.'" '.$opacity_start_effect_data.'>';
						$output .= '<img class="ult-easy-separator-img" alt="'.$alt.'" src="'.$img[0].'" />';
					$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>';
			
			return $output;
		}
	}
}
if(class_exists('Ultimate_Image_Separator'))
{
	$Ultimate_Image_Separator = new Ultimate_Image_Separator;
}

?>