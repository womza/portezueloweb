<?php
global $etheme_theme_data;
$etheme_theme_data = wp_get_theme( get_stylesheet_directory() . '/style.css' );
define('ETHEME_DOMAIN', 'woopress');
require_once( get_template_directory() . '/framework/init.php' );

