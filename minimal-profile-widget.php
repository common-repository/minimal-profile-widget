<?php 
/*
* Plugin Name: Minimal Profile Widget
* Plugin URI: http://codiov.com/
* Description: A simple minimal profile widget plugin
* Author: Ibrahim Hasnat
* Author URI: https://linkedin.com/in/ibrahimhasnat
* Version: 1.0
* Text Domain: minimal
* Domain Path: /languages
* License:      GPL2
* License URI:  https://www.gnu.org/licenses/gpl-2.0.html

Minimal Profile Widget is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Minimal Profile Widget is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Minimal Profile Widget. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

// Exit if access directly
if( !defined( 'ABSPATH' ) ) {
	exit;
}

function minimal_assets() {

    load_plugin_textdomain( 'minimal', false, dirname(__FILE__) . '/languages' );

}
add_action( 'plugins_loaded', 'minimal_assets' );

// Load Widget Class
require_once( plugin_dir_path(__FILE__) . '/inc/minimal-profile-widget-class.php' );


// Register Widget
function register_minimalprofilewidget_widget() {
	register_widget( 'Minimalprofilewidget_Widget' );
}
add_action( 'widgets_init', 'register_minimalprofilewidget_widget' );

// Load Assets
require_once( plugin_dir_path(__FILE__) . '/inc/minimal-assets.php' );