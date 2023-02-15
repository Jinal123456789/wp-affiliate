<?php
/**
** Affiliate Page
**/
class wpam_Defaul_Option {
	/**
	 * Constructor method for loading the components
	 */
	public function __construct() {
        add_action('admin_menu', [$this, 'option_menu']);
        if ( isset($_POST['wpam_action']) && $_POST['wpam_action'] == 'save_option') {
            add_action('init', [$this, 'save_option']);
        }
    }

    public function option_menu() {
        // if (!is_pro_license()) {
        // if( wamm_fs()->is_free_plan()) {
            add_submenu_page(AME()->wpam_link, 'Default Settings', 'Default Settings', 'manage_options', 'wpam_settings', [$this,'wpam_option_page'], 5);
        // }
        // }
    }

    
	function wpam_option_page() {
        require_once(__DIR__ . '/templates/default_setting.php');
	}

    public function save_option() {
        if ( isset($_POST['submit']) ) {
            update_option( 'wpam_global_meta_redirect', $_POST['wpam_global_meta_redirect'] );	
            update_option( 'wpam_global_nofollow', $_POST['wpam_global_nofollow'] );	
            update_option( 'wpam_global_sponsored', $_POST['wpam_global_sponsored'] );	
            update_option( 'wpam_global_tracking', $_POST['wpam_global_tracking'] );
            setcookie('wpam_message', 'Setting Save Successfully.');
        }
        wp_redirect(AME()->ame_url . '&page=wpam_settings'); exit;
    }
}

new wpam_Defaul_Option();