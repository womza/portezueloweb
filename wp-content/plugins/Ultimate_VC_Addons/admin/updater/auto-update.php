<?php
if (!class_exists('Plugin_Upgrader')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
}
if (!class_exists('UltAutomaticUpdater')) {
    class UltAutomaticUpdater extends Plugin_Upgrader
    {
        protected static function envatoDownloadPurchaseUrl($username, $api_key, $purchase_code)
        {
            return 'http://marketplace.envato.com/api/edge/' . rawurlencode($username) . '/' . rawurlencode($api_key) . '/wp-download:6892199.json';
        }
        protected function getDownloadUrl()
        {
			require_once (ABSPATH . '/wp-admin/includes/file.php');
			WP_Filesystem();
            global $wp_filesystem;
            $this->skin->feedback('download_envato');
            $package_filename = 'Ultimate_VC_Addons.zip';
            $res              = $this->fs_connect(array(
                WP_CONTENT_DIR
            ));
            if (!$res) {
                return new WP_Error('no_credentials', __("Error! Can't connect to filesystem", 'ultimate_vc'));
            }
            $ultimate_keys = get_option('ultimate_keys');
            $username      = trim($ultimate_keys['envato_username']);
            $api_key       = trim($ultimate_keys['envato_api_key']);
            $purchase_code = trim($ultimate_keys['ultimate_purchase_code']);
            if (empty($username) || empty($api_key) || empty($purchase_code)) {
                return new WP_Error('no_credentials', __('Error! Envato username, api key and your purchase code are required for downloading updates from Envato marketplace for the Visual Composer.','ultimate_vc').' '.__('Visit','ultimate_vc').' <a href="' . admin_url('options-general.php?page=wpb_vc_settings&tab=updater') . '' . '">'.__('Settings','ultimate_vc').'</a> '._('to fix.', 'ultimate_vc'));
            }
            $json   = wp_remote_get($this->envatoDownloadPurchaseUrl($username, $api_key, $purchase_code));
            $result = json_decode($json['body'], true);
            if (!isset($result['wp-download']['url'])) {
                return new WP_Error('no_credentials', __('Error! Envato API error' . (isset($result['error']) ? ': ' . $result['error'] : '.'), 'ultimate_vc'));
            }
            $download_file = download_url($result['wp-download']['url']);
            if (is_wp_error($download_file)) {
                $download_file = file_get_contents($result['wp-download']['url']);
				if(is_wp_error($download_file)) {
					return $download_file;
				}
            }
            $upgrade_folder = $wp_filesystem->wp_content_dir() . 'upgrade_tmp/ultimate_envato_package';
            if (is_dir($upgrade_folder)) {
                $wp_filesystem->delete($upgrade_folder);
            }
			else {
				mkdir($upgrade_folder,0755);
			}
            $is_unzip = unzip_file($download_file, $upgrade_folder);
			if($is_unzip)
			{
				$result = copy($result['wp-download']['url'], $upgrade_folder . '/' . $package_filename);
				if($result) {
					return $upgrade_folder . '/' . $package_filename;
				}
				else {
					if(file_exists($upgrade_folder.'/Ultimate_VC_Addons') && is_dir($upgrade_folder.'/Ultimate_VC_Addons'))
					{
						$this->Ultimate_Zip($upgrade_folder.'/Ultimate_VC_Addons', $upgrade_folder.'/'.$package_filename);
						if(is_file($upgrade_folder.'/'.$package_filename))
							return $upgrade_folder.'/'.$package_filename;
						return new WP_Error('no_credentials', __('Error while zipping plugin', 'ultimate_vc'));
					}
					return new WP_Error('no_credentials', __('Error while coping zip from Remote server, allow_url_fopen is disabled on your server.', 'ultimate_vc'));
				}
			}
            return new WP_Error('no_credentials', __('Error on unzipping package', 'ultimate_vc'));
        }
        function download_package($package)
        {
            $package = $this->getDownloadUrl();
            if (is_wp_error($package))
                return $package;
            return parent::download_package($package);
        }
        function upgradeUltimate()
        {
            global $wp_filesystem;
            $this->init();
            $this->upgrade_strings();
            $this->strings['download_envato'] = __('Downloading package from envato market&#8230;', 'ultimate_vc');
            add_filter('upgrader_pre_install', array(
                &$this,
                'deactivate_plugin_before_upgrade'
            ), 10, 2);
            add_filter('upgrader_clear_destination', array(
                &$this,
                'delete_old_plugin'
            ), 10, 4);
            $this->run(array(
                'package' => 'Ultimate_VC_Addons',
                'destination' => WP_PLUGIN_DIR,
                'clear_destination' => true,
                'clear_working' => true,
                'hook_extra' => array(
                    'plugin' => 'Ultimate_VC_Addons'
                )
            ));
            // Cleanup our hooks, in case something else does a upgrade on this connection.            
			remove_filter('upgrader_pre_install', array(&$this, 'deactivate_plugin_before_upgrade'));            
			remove_filter('upgrader_clear_destination', array(&$this, 'delete_old_plugin'));
			if ( ! $this->result || is_wp_error($this->result) )
				return $this->result;
			if(is_dir($wp_filesystem->wp_content_dir() . 'upgrade_tmp/ultimate_envato_package')) {
            	$wp_filesystem->delete($wp_filesystem->wp_content_dir() . 'upgrade_tmp/ultimate_envato_package', true);
        	}
        	// Force refresh of plugin update information            
			delete_site_transient('update_plugins');
			wp_cache_delete( 'plugins', 'plugins' );
			return true;
		}
    }
}