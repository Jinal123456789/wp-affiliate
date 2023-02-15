<?php
/**
** Affiliate Page
**/
class wpam_Affiliate {
	/**
	 * Constructor method for loading the components
	 */
	public function __construct() {
        add_action('admin_menu', [$this, 'affiliate_menu']);
        if ( isset($_POST['wpam_action']) && $_POST['wpam_action'] == 'save_affiliate') {
            add_action('init', [$this, 'save_affiliate']);
        } else if ( isset($_POST['wpam_action']) && $_POST['wpam_action'] == 'delete_affiliate') {
            add_action('init', [$this, 'delete_affiliate']);
        }
    }

    public function affiliate_menu() {
        // if (!is_pro_license()) {
        if ( wamm_fs()->is__premium_only() ) {
            if ( wamm_fs()->can_use_premium_code() ) {
                add_submenu_page(AME()->wpam_link, 'Affiliate Details', 'Affiliate Details', 'manage_options', 'wpam_affiliate', [$this,'wpam_affiliate_page'], 6);
            }
        }
    }

    
	function wpam_affiliate_page() {
        if ( isset($_GET['action']) && ($_GET['action']=='add' || $_GET['action']=='edit') ) {
            require_once(__DIR__ . '/templates/affiliate_add_edit.php');
        } else {
            require_once(__DIR__ . '/templates/affiliate_index.php');
        }
	}

    public function save_affiliate() {
        global $wpdb;
        $data = [
            'company'   => $_POST['company'],
            'website'   => $_POST['website'],
            'login_url' => $_POST['login_url'],
            'login_username'   => $_POST['login_username'],
            'commission'=> $_POST['commission'],
            'status'    => 1
        ];
        if (isset($_POST['id']) && !empty($_POST['id']) && is_numeric($_POST['id'])) {
            $where = ['id' => $_POST['id']];
            $wpdb->update( $wpdb->prefix . "wpam_affiliates", $data, $where);
            setcookie('wpam_message', 'Affiliate Edit Successfully.');
        } else {
            $wpdb->insert( $wpdb->prefix . "wpam_affiliates", $data);
            setcookie('wpam_message', 'Affiliate Add Successfully.');
        }
        wp_redirect(AME()->ame_url . '&page=wpam_affiliate'); exit;
    }

    public function delete_affiliate() {
        global $wpdb;
        if (isset($_POST['id']) && !empty($_POST['id']) && is_numeric($_POST['id'])) {
            $where = ['id' => $_POST['id']];
            $wpdb->delete( $wpdb->prefix . "wpam_affiliates", $where);
            setcookie('wpam_message', 'Affiliate Delete Successfully.', time() + 10000);
        }
        wp_redirect(AME()->ame_url . '&page=wpam_affiliate'); exit;
    }

}

new wpam_Affiliate();