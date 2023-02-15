<?php
/**
** Register all css and js files
**/

class wpam_Assets{
	/**
	 * Constructor method for loading the components
	 */
	public function __construct() {	
		add_action( 'admin_enqueue_scripts', [$this, 'wpam_enqueue_admin_script'] );	
	}
	
	public function wpam_enqueue_admin_script( $hook ) {
		wp_enqueue_style('wpam_admin_style', wpam_PLUGIN_DIR_URL . '/assets/css/wpam_style.css', array(), '0.2.0', 'all');
		wp_enqueue_script( 'wpam_admin_script', wpam_PLUGIN_DIR_URL . '/assets/js/wpam_script.js', array(), '2.0' );
		wp_enqueue_style( 'wpam_font_style', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), '1.0', 'all' );
	}

}
new wpam_Assets();