<?php
/**
** Affiliate Page
**/
class wpam_Impression {
	/**
	 * Constructor method for loading the components
	 */
	public function __construct() {
        add_action('admin_menu', [$this, 'impression_menu']);
        if ( isset($_POST['wpam_action']) && $_POST['wpam_action'] == 'save_affiliate') {
            add_action('init', [$this, 'save_affiliate']);
        } else if ( isset($_POST['wpam_action']) && $_POST['wpam_action'] == 'delete_affiliate') {
            add_action('init', [$this, 'delete_affiliate']);
        }
    }

    public function impression_menu() {
        // if (!is_pro_license()) {
        if ( wamm_fs()->is__premium_only() ) {
            if ( wamm_fs()->can_use_premium_code() ) {
		        add_submenu_page(AME()->wpam_link, 'Impressions', 'Analytics', 'manage_options', 'wpam_analytics',array($this, 'wpam_analytics'), 4);
            }
        }
    }

    
	function wpam_analytics() {
        global $wpdb;
        echo '<h1>Analytics</h1><hr>';
        require_once(__DIR__ . '/templates/wpam-clicks-chart.php');
        require_once(__DIR__ . '/templates/wpam-clicks.php');
	}
}

new wpam_Impression();