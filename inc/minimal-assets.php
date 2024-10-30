<?php

function minimal_widget_assets() {

	wp_enqueue_style( 'minimal-main-css', plugin_dir_url( __FILE__ ) . '../css/main.css' );

}
add_action( 'wp_enqueue_scripts', 'minimal_widget_assets' );