<?php

/*
Plugin Name: WP Affiliate Magnet wp-affiliate-magnet_pro
Plugin URI: /
Description: Add, redirect and update URL's
Version: 2.2.3
Author: The Focused Startup
Author URI: /
Text Domain: /
Copyright: 2021
@fs_premium_only /includes/admin/class-admin-affiliate.php, /premium-files/
@fs_premium_only /includes/admin/class-admin-findlink.php, /premium-files/
@fs_premium_only /includes/admin/class-admin-broken-link.php, /premium-files/
@fs_premium_only /includes/admin/class-admin-impression.php, /premium-files/
@fs_premium_only /includes/admin/class-admin-broken-link.php, /premium-files/
@fs_premium_only /includes/admin/class-admin-duplicate-title.php, /premium-files/
*/
if ( !defined( 'ABSPATH' ) ) {
    die( 'You are not allowed to call this page directly.' );
}
define( 'wpam_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'wpam_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'wpam_PLUGIN_INCLUDES_PATH', wpam_PLUGIN_DIR_PATH . 'includes' );
define( 'wpam_SITE_URL', site_url() );
/** new code**/
// if ( ! class_exists( 'Appsero\Client' ) ) {
// require_once __DIR__ . '/appsero.php';
// }

if ( function_exists( 'wamm_fs' ) ) {
    wamm_fs()->set_basename( true, __FILE__ );
} else {
    
    if ( !function_exists( 'wamm_fs' ) ) {
        // Create a helper function for easy SDK access.
        function wamm_fs()
        {
            global  $wamm_fs ;
            
            if ( !isset( $wamm_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $wamm_fs = fs_dynamic_init( array(
                    'id'               => '9640',
                    'slug'             => 'wpam_link',
                    'premium_slug'     => 'wp-affiliate-magnet',
                    'type'             => 'plugin',
                    'public_key'       => 'pk_003a113029f345484bc3c235af8f1',
                    'is_premium'       => true,
                    'premium_suffix'   => 'wp-affiliate-magnet_pro',
                    'has_addons'       => false,
                    'has_paid_plans'   => true,
                    'is_org_compliant' => false,
                    'menu'             => array(
                    'slug'       => 'edit.php?post_type=wpam_link',
                    'first-path' => 'edit.php?post_type=wpam_link',
                    'contact'    => false,
                    'support'    => false,
                ),
                    'is_live'          => true,
                ) );
            }
            
            return $wamm_fs;
        }
        
        // Init Freemius.
        wamm_fs();
        // Signal that SDK was initiated.
        do_action( 'wamm_fs_loaded' );
    }
    
    class API_Manager
    {
        /**
         * Self Upgrade Values
         */
        // Base URL to the remote upgrade API Manager server. If not set then the Author URI is used.
        public  $upgrade_url = 'https://wpaffiliatemagnet.com/' ;
        /**
         * @var string
         */
        public  $version = '2.2.3' ;
        /**
         * @var string
         * This version is saved after an upgrade to compare this db version to $version
         */
        public  $api_manager_version_name = 'plugin_api_manager_version' ;
        /**
         * @var string
         */
        public  $plugin_url ;
        /**
         * @var string
         * used to defined localization for translation, but a string literal is preferred
         *
         * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/issues/59
         * http://markjaquith.wordpress.com/2011/10/06/translating-wordpress-plugins-and-themes-dont-get-clever/
         * http://ottopress.com/2012/internationalization-youre-probably-doing-it-wrong/
         */
        public  $text_domain = 'api-manager' ;
        /**
         * Data defaults
         * @var mixed
         */
        public  $wpam_slug_key = 'wp-affiliate-magnet' ;
        public  $ame_settings_menu_title = 'WP Affiliate License' ;
        public  $ame_settings_title = 'WP Affiliate Magnet' ;
        public  $ame_plugin_name = 'wp-affiliate-magnet' ;
        public  $ame_renew_license_url = 'https://wpaffiliatemagnet.com/my-account' ;
        public  $default_redirect_url = 'https://wpaffiliatemagnet.com' ;
        public  $help_support_url = 'https://wpaffiliatemagnet.com/support/' ;
        public  $ame_upgrade_url = 'https://wpaffiliatemagnet.com/upgrade/' ;
        public  $license_page = 'wp_affiliate_magnet_settings' ;
        public  $wpam_link = 'edit.php?post_type=wpam_link' ;
        public  $ame_domain ;
        public  $ame_url ;
        public  $ame_limit = 5 ;
        /**
         * Used to send any extra information.
         * @var mixed array, object, string, etc.
         */
        public  $ame_extra ;
        /**
         * @var The single instance of the class
         */
        protected static  $_instance = null ;
        public static function instance()
        {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        
        /**
         * Cloning is forbidden.
         *
         * @since 1.2
         */
        private function __clone()
        {
        }
        
        /**
         * Unserializing instances of this class is forbidden.
         *
         * @since 1.2
         */
        private function __wakeup()
        {
        }
        
        public function __construct()
        {
            // Run the activation function
            register_activation_hook( __FILE__, array( $this, 'activation' ) );
            $this->ame_domain = str_ireplace( array( 'http://', 'https://' ), '', home_url() );
            $this->ame_url = admin_url() . $this->wpam_link;
            require_once plugin_dir_path( __FILE__ ) . 'functions.php';
            if ( is_admin() ) {
                // Check for external connection blocking
                add_action( 'admin_notices', array( $this, 'wpam_notices' ) );
            }
            /**
             * Deletes all data if plugin deactivated
             */
            // register_deactivation_hook( __FILE__, array( $this, 'uninstall' ) );
            /**
             * License Page Link on plugin page
             */
            $plugin = plugin_basename( __FILE__ );
            add_filter( "plugin_action_links_{$plugin}", [ $this, 'wplm_plugin_settings_link' ] );
        }
        
        /**
         * Set Plugin url
         */
        public function plugin_url()
        {
            if ( isset( $this->plugin_url ) ) {
                return $this->plugin_url;
            }
            return $this->plugin_url = plugins_url( '/', __FILE__ );
        }
        
        /**
         * Generate the default data arrays
         */
        public function activation()
        {
            global  $wpdb ;
            $curr_ver = get_option( $this->api_manager_version_name );
            if ( version_compare( $this->version, $curr_ver, '>' ) ) {
                update_option( $this->api_manager_version_name, $this->version );
            }
            // define('WPLM_LICENSE_VALID', true);
            // define('WPLM_LICENSE_ACTIVE', true);
            $wpam_global_meta_redirect = get_option( 'wpam_global_meta_redirect' );
            
            if ( !$wpam_global_meta_redirect ) {
                update_option( 'wpam_global_meta_redirect', '302' );
                update_option( 'wpam_global_tracking', 'on' );
            }
        
        }
        
        /**
         * Deletes all data if plugin deactivated
         * @return void
         */
        // public function uninstall() {
        // wplm_appsero_license_deactivate();
        // }
        /**
         * Displays an inactive notice when the software is inactive.
         */
        public static function am_inactive_notice()
        {
            ?>
		<?php 
            if ( !current_user_can( 'manage_options' ) ) {
                return;
            }
            ?>
		<?php 
            if ( isset( $_GET['page'] ) && 'api_manager_dashboard' == $_GET['page'] ) {
                return;
            }
            ?>
		<div id="message" class="error">
			<p><?php 
            printf( __( 'The WP Affiliate Magnet License Key has not been activated, so the plugin is inactive! %sClick here%s to activate the license key and the plugin.', 'api-manager' ), '<a href="' . esc_url( admin_url( 'options-general.php?page=api_manager_dashboard' ) ) . '">', '</a>' );
            ?></p>
		</div>
		<?php 
        }
        
        /**
         * Check for Admin Notices
         * @return string
         */
        public function wpam_notices()
        {
            // show notice if external requests are blocked through the WP_HTTP_BLOCK_EXTERNAL constant
            
            if ( defined( 'WP_HTTP_BLOCK_EXTERNAL' ) && WP_HTTP_BLOCK_EXTERNAL === true ) {
                // check if our API endpoint is in the allowed hosts
                $host = parse_url( $this->upgrade_url, PHP_URL_HOST );
                
                if ( !defined( 'WP_ACCESSIBLE_HOSTS' ) || stristr( WP_ACCESSIBLE_HOSTS, $host ) === false ) {
                    ?>
				<div class="error">
					<p><?php 
                    printf(
                        __( '<b>Warning!</b> You\'re blocking external requests which means you won\'t be able to get %s updates. Please add %s to %s.', 'api-manager' ),
                        8,
                        '<strong>' . $host . '</strong>',
                        '<code>WP_ACCESSIBLE_HOSTS</code>'
                    );
                    ?></p>
				</div>
				<?php 
                }
            
            }
            
            // if (is_pro_license()) return;
            $class = 'notice notice-error is-dismissible wpam-notice purplemsg';
            // $message = __( 'Thank you for activating WP Affiliate Magnet! If you already purchased a license key, click <a href="edit.php?post_type=wpam_link">HERE</a> to license your plugin. If you need a new license please click <a href="https://wpaffiliatemagnet.com/" target="_blank">HERE</a>.' , 'sample-text-domain' );
            $message = __( 'Thank you for activating WP Affiliate Magnet! If you already purchased a license key, click <a href="options-general.php?page=' . AME()->license_page . '">HERE</a> to license your plugin. If you need a new license please click <a href="https://wpaffiliatemagnet.com/" target="_blank">HERE</a>.', 'sample-text-domain' );
            printf( '<div class="%1$s"><p> </p><p>%2$s</p><p> </p></div>', esc_attr( $class ), $message );
        }
        
        /**
         * License Page Link on plugin page
         */
        public function wplm_plugin_settings_link( $links )
        {
            $settings_link = '<a href="options-general.php?page=' . AME()->license_page . '">Settings</a>';
            // $settings_link = '<a href="edit.php?post_type=wpam_link">Settings</a>';
            array_unshift( $links, $settings_link );
            return $links;
        }
    
    }
    // End of class
    function AME()
    {
        return API_Manager::instance();
    }
    
    AME();
    final class wpam
    {
        /**
         * Constructor method for loading the components
         */
        public function __construct()
        {
            $this->load_dependencies();
            register_activation_hook( __FILE__, array( $this, 'wpam_activate' ) );
            register_deactivation_hook( __FILE__, array( $this, 'wpam_deactivate' ) );
        }
        
        /**
         * Load the other class dependencies
         */
        protected function load_dependencies()
        {
            require_once wpam_PLUGIN_INCLUDES_PATH . '/admin/class-lang.php';
            require_once wpam_PLUGIN_INCLUDES_PATH . '/admin/class-assets.php';
            require_once wpam_PLUGIN_INCLUDES_PATH . '/admin/admin-files.php';
            require_once wpam_PLUGIN_INCLUDES_PATH . '/admin/class-admin-post-type.php';
            require_once wpam_PLUGIN_INCLUDES_PATH . '/admin/class-admin-meta.php';
            require_once wpam_PLUGIN_INCLUDES_PATH . '/admin/class-admin-dashboard.php';
            require_once wpam_PLUGIN_INCLUDES_PATH . '/class-redirect.php';
        }
        
        /**
         * Activate the plugin and create database
         **/
        public function wpam_activate()
        {
            global  $wpdb ;
            
            if ( $wpdb->get_var( "show tables like '{$wpam_db_name}'" ) != $wpam_db_name ) {
                $wpam_db_name = $wpdb->prefix . 'wpam_links';
                $sql = "CREATE TABLE " . $wpam_db_name . " (\r\n\t\t\t`id` int(11) NOT NULL AUTO_INCREMENT,\r\n\t\t\t`name` varchar(255),\r\n\t\t\t`description` varchar(255),\r\n\t\t\t`url` varchar(255),\r\n\t\t\t`slug` varchar(255),\r\n\t\t\t`nofollow` varchar(255),\r\n\t\t\t`track_me` varchar(255),\r\n\t\t\t`sponsored` varchar(255),\r\n\t\t\t`redirect_type` varchar(255),\r\n\t\t\t`link_status` varchar(255),\r\n\t\t\t`created_at` datetime,\r\n\t\t\t`updated_at` datetime,\r\n\t\t\t`group_id` int(11),\r\n\t\t\t`link_cpt_id` int(11),\r\n\t\t\tUNIQUE KEY id (id)\r\n\t\t\t);";
                $wpam_db_name = $wpdb->prefix . 'wpam_clicks';
                $clicksql = "CREATE TABLE " . $wpam_db_name . " (\r\n\t\t\t`id` int(11) NOT NULL AUTO_INCREMENT,\r\n\t\t\t`ip` varchar(255),\r\n\t\t\t`browser` varchar(255),\r\n\t\t\t`browser_version` varchar(255),\r\n\t\t\t`os` varchar(255),\r\n\t\t\t`user_agent` varchar(255),\r\n\t\t\t`referer` varchar(255),\r\n\t\t\t`link_id` varchar(255),\r\n\t\t\t`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,\r\n\t\t\tUNIQUE KEY id (id)\r\n\t\t\t);";
                $wpam_db_name = $wpdb->prefix . 'wpam_broken_links';
                $brokensql = "CREATE TABLE " . $wpam_db_name . " (\r\n\t\t\t`id` int(11) NOT NULL AUTO_INCREMENT,\r\n\t\t\t`link_id` int(11),\r\n\t\t\t`title` varchar(255),\r\n\t\t\t`url` varchar(255),\r\n\t\t\t`slug` varchar(255),\r\n\t\t\t`link_cpt_id` int(11),\r\n\t\t\t`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,\r\n\t\t\tUNIQUE KEY id (id)\r\n\t\t\t);";
                $wpam_db_name = $wpdb->prefix . 'wpam_affiliates';
                $affiliatesql = "CREATE TABLE " . $wpam_db_name . " (\r\n\t\t\t`id` int(11) NOT NULL AUTO_INCREMENT,\r\n\t\t\t`company` varchar(255),\r\n\t\t\t`website` varchar(255),\r\n\t\t\t`login_url` varchar(255),\r\n\t\t\t`login_username` varchar(255),\r\n\t\t\t`commission` varchar(255),\r\n\t\t\t`status` TINYINT(2),\r\n\t\t\t`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,\r\n\t\t\t`updated_at` timestamp on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,\r\n\t\t\tUNIQUE KEY id (id)\r\n\t\t\t);";
                $wpam_db_name = $wpdb->prefix . 'wpam_track_links';
                $tracksql = "CREATE TABLE " . $wpam_db_name . " (\r\n\t\t\t`id` int(11) NOT NULL AUTO_INCREMENT,\r\n\t\t\t`link_id` int(11),\r\n\t\t\t`link_slug` varchar(255),\r\n\t\t\t`link_title` varchar(255),\r\n\t\t\t`post_id` int(11),\r\n\t\t\t`post_title` varchar(255),\r\n\t\t\t`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,\r\n\t\t\tUNIQUE KEY id (id)\r\n\t\t\t);";
                require_once ABSPATH . 'wp-admin/includes/upgrade.php';
                dbDelta( $sql );
                dbDelta( $clicksql );
                dbDelta( $brokensql );
                dbDelta( $affiliatesql );
                dbDelta( $tracksql );
            }
        
        }
        
        /**
         * Deactivation hook.
         **/
        public function wpam_deactivate()
        {
            unregister_post_type( 'wpam_link' );
            flush_rewrite_rules();
        }
    
    }
    $wpam = new wpam();
}
