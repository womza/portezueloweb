<?php 

add_action('wp_ajax_etheme_import_ajax', 'etheme_import_data');

function etheme_import_data() {
    //delete_option('demo_data_installed');die();
    
	// Load Importer API
	require_once ABSPATH . 'wp-admin/includes/import.php';
	$importerError = false;
    $demo_data_installed = get_option('demo_data_installed');
    
    if($demo_data_installed == 'yes') die();
    
        
    $file = get_template_directory() ."/framework/dummy/Dummy.xml";
   
	//check if wp_importer, the base importer class is available, otherwise include it
	if ( !class_exists( 'WP_Importer' ) ) {
		$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
		if ( file_exists( $class_wp_importer ) ) 
			require_once($class_wp_importer);
		else 
			$importerError = true;
	}
    
	
	if($importerError !== false) {
		echo ("The Auto importing script could not be loaded. Please use the wordpress importer and import the XML file that is located in your themes folder manually.");
	} else {
	
		do_action('et_before_data_import');
		
		if(class_exists('WP_Importer')){
			try{
                
                if($demo_data_installed != 'yes') {
    				$importer = new WP_Import();
    				$importer->fetch_attachments = true;
    				$importer->import($file);
    				etheme_update_menus();
                }
			                
			                	
				$versionsUrl = 'http://8theme.com/import/' . ETHEME_DOMAIN . '_versions/';
				$ver = 'base';
				$folder = $versionsUrl.''.$ver;
				
				$sliderZip = $folder.'/slider.zip';
				$file_headers = @get_headers($sliderZip);
				
				if($file_headers[0] == 'HTTP/1.1 200 OK' && class_exists('RevSlider')) {
					$tmpZip = PARENT_DIR.'/framework/tmp/tempSliderZip.zip';
					
					file_put_contents($tmpZip, file_get_contents($sliderZip));
					
					$revapi = new RevSlider();
					
					$_FILES["import_file"]["tmp_name"] = $tmpZip;
					
					ob_start();
					
					$slider_result = $revapi->importSliderFromPost();
					
					ob_end_clean();
				}
	
	
				etheme_update_options();
			    
                die('Success!');
				
				
				
			} catch (Exception $e) {
				echo ("Error while importing");
			}

		}
		
	}
		
	
	die();
}


add_action('wp_ajax_etheme_install_version', 'etheme_install_version');

function etheme_install_version() {
 	$output = '';
	require_once ABSPATH . 'wp-admin/includes/import.php';
	$importerError = false;
	if(empty($_POST['ver'])) die();
	$versionsUrl = 'http://8theme.com/import/' . ETHEME_DOMAIN . '_versions/';
	$ver = $_POST['ver'];
	$folder = $versionsUrl.''.$ver;
	
	$sliderZip = $folder.'/slider.zip';
	$file_headers = @get_headers($sliderZip);
	
	if($file_headers[0] == 'HTTP/1.1 200 OK' && class_exists('RevSlider')) {
		$tmpZip = PARENT_DIR.'/framework/tmp/tempSliderZip.zip';
		
		file_put_contents($tmpZip, file_get_contents($sliderZip));
		
		$revapi = new RevSlider();
		
		$_FILES["import_file"]["tmp_name"] = $tmpZip;
		
		ob_start();
		
		$slider_result = $revapi->importSliderFromPost();
		
		ob_end_clean();
		
		
		if($slider_result['success']) {
			$output .= '<div class="rev-slider-result updated">';
				$output .= "Revolution slider installed successfully!";
			$output .= "</div>";
		}
	}
	
	$sliderZip2 = $folder.'/slider2.zip';
	$file_headers = @get_headers($sliderZip2);
	
	if($file_headers[0] == 'HTTP/1.1 200 OK' && class_exists('RevSlider')) {
		$tmpZip = PARENT_DIR.'/framework/tmp/tempSliderZip.zip';
		
		file_put_contents($tmpZip, file_get_contents($sliderZip2));
		
		$revapi = new RevSlider();
		
		$_FILES["import_file"]["tmp_name"] = $tmpZip;
		
		ob_start();
		
		$slider_result = $revapi->importSliderFromPost();
		
		ob_end_clean();
		
		
		if($slider_result['success']) {
			$output .= '<div class="rev-slider-result updated">';
				$output .= "Revolution slider installed successfully!";
			$output .= "</div>";
		}
	}
	
	
	$version_xml = $folder.'/version_data.xml';
	
	$file_headers = @get_headers($version_xml);
	
	if($file_headers[0] == 'HTTP/1.1 200 OK') {
		
		$tmpxml = PARENT_DIR.'/framework/tmp/version_data.xml';
		
		file_put_contents($tmpxml, file_get_contents($version_xml));
		
		//check if wp_importer, the base importer class is available, otherwise include it
		if ( !class_exists( 'WP_Importer' ) ) {
			$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			if ( file_exists( $class_wp_importer ) ) 
				require_once($class_wp_importer);
			else 
				$importerError = true;
		}
	    
		
		if($importerError !== false) {
			echo ("The Auto importing script could not be loaded. Please use the wordpress importer and import the XML file that is located in your themes folder manually.");
		} else {
			
			if(class_exists('WP_Importer')){
				try{
					$importer = new WP_Import();
					$importer->fetch_attachments = true;
					$importer->import($tmpxml);
					
					update_option( 'show_on_front', 'page' );
					update_option( 'page_on_front', $_POST['home_id'] );
					
					$output .= '<div class="updated">';
						$output .= "Version page installed successfully!";
					$output .= "</div>";
				} catch (Exception $e) {
					echo ("Error while importing");
				}
			}
		}
	}

	
	$options_txt = $folder.'/options.txt';
	
	$file_headers = @get_headers($options_txt);
	
	if($file_headers[0] == 'HTTP/1.1 200 OK') {
		
		$tmpxml = PARENT_DIR.'/framework/tmp/options.txt';
		
		$new_options = file_get_contents($options_txt);
		
		$new_options = json_decode(base64_decode($new_options),true);
		
		update_option( 'option_tree', $new_options );

		$output .= '<div class="updated">';
			$output .= "Theme Options updated!";
		$output .= "</div>";
	}

	die($output);
}

function etheme_update_options() {
    global $options_presets;
	$home_id = get_page_by_title('Home');
	$blog_id = get_page_by_title('Blog');;
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $home_id->ID );
    update_option( 'page_for_posts', $blog_id->ID );
    //add_option('demo_data_installed', 'yes');
    
    
    // Update Theme Optinos
    //$new_options = json_decode(base64_decode($options_presets[$style]),true);
	//update_option( 'option_tree', $new_options );
}

function etheme_update_menus(){
	
	global $wpdb;
	
    $menuname = 'main';
	$bpmenulocation = 'main-menu';
	$mobilemenulocation = 'mobile-menu';
	
	$tablename = $wpdb->prefix.'terms';
	$menu_ids = $wpdb->get_results(
	    "
	    SELECT term_id
	    FROM ".$tablename." 
	    WHERE name= '".$menuname."'
	    "
	);
	
	// results in array 
	foreach($menu_ids as $menu):
	    $menu_id = $menu->term_id;
	endforeach; 

	
	
    if( !has_nav_menu( $bpmenulocation ) ){
        $locations = get_theme_mod('nav_menu_locations');
        $locations[$bpmenulocation] = $menu_id;
        $locations[$mobilemenulocation] = $menu_id;
        set_theme_mod( 'nav_menu_locations', $locations );
    }
        
}
