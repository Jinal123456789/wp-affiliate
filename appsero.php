<?php
// if ( ! class_exists( 'Appsero\Client' ) ) {
// 	require_once __DIR__ . '/appsero/src/Client.php';
// }

// function wplm_appsero_client() {
// 	// $client = new Appsero\Client( '02d470b2-38a7-4531-97a3-4242e11fb2f7', 'WP Affiliate Magnet', __FILE__ );
// 	$status = "activate";

// 	return $status;
// }

// function wplm_appsero_license($client=false) {
// 	if (!$client) {
// 		$client = wplm_appsero_client();
// 	}
//     $wplm_appsero_license = $client->license();
// 	$wplm_license = $wplm_appsero_license->get_license();

// 	return $wplm_license;
// }

// function wplm_appsero_license_deactivate($client=false) {
// 	if (!$client) {
// 		$client = wplm_appsero_client();
// 	}
// 	$license = wplm_appsero_license($client);
// 	$client->license()->deactivate($license['key']);
// 	global $wpdb;
// 	$option_key = $wpdb->get_var("SELECT option_name  FROM `".$wpdb->prefix."options` WHERE `option_name` LIKE 'appsero_%' and `option_name` LIKE '%_manage_license'");
// 	update_option($option_key, '');

// }

// function wplm_define_license() {
// 	// if (!$client) {
// 	// 	$client = wplm_appsero_client();
// 	// }
// 	// $wplm_license = wplm_appsero_license($client);
// 	// echo "<pre>"; print_r($wplm_license); exit;
// 	if ( $status == 'activate' ) {
// 		if ( $status == 'activate' ) {
// 			define('WPLM_LICENSE_VALID', true);
// 		} else {
// 			define('WPLM_LICENSE_VALID', false);
// 		}
// 		define('WPLM_LICENSE_ACTIVE', true);
// 	} else {
// 		define('WPLM_LICENSE_ACTIVE', false);
// 		define('WPLM_LICENSE_VALID', false);
// 	}
// }

// function wplm_appsero_init_tracker_wp_affiliate_magnet() {
    // $client = wplm_appsero_client();

    // Active insights
    // $client->insights()->init();

    // Active automatic updater
    // $client->updater();

    // Active license page and checker
 //    $args = array(
 //        'type'       => 'options',
 //        'menu_title' => 'WP Affiliate Magnet',
 //        'page_title' => 'WP Affiliate Magnet Settings',
 //        'menu_slug'  => 'wp_affiliate_magnet_settings',
 //    );
 //    $client->add_settings_page( $args );
	
	// wplm_define_license($client);
	
// }
// wplm_appsero_init_tracker_wp_affiliate_magnet();


// function redirect_after_submit_appsero() {
// 	if ( isset($_POST['_action']) && ($_POST['_action']=='deactive' || $_POST['_action']=='active') ) {
// 		if (headers_sent()) {
// 			echo "<script type='text/javascript'>
// 				window.location=document.location.href;
// 			</script>";
// 		} else {
// 			wp_redirect($_SERVER['HTTP_REFERER']);
// 		}
// 		exit;
// 	}
// }

// add_action('after_appsero_license_section', 'redirect_after_submit_appsero');

// function freemius_license_key_section() {

// 	add_options_page(
// 		'WP Affiliate Magnet', // page Title
// 		'WP Affiliate Magnet', // menu link text
// 		'manage_options', // capability to access the page
// 		'wp_affiliate_magnet_settings', // page URL slug
// 		'freemius_license_key', // callback function with content
// 		2 // priority
// 	);

// }
// add_action( 'admin_menu', 'freemius_license_key_section' );

// function freemius_license_key(){
?>
<?php
    // $plugin_id         = '9640';
    // $plugin_public_key = 'pk_003a113029f345484bc3c235af8f1';
    // $plugin_secret_key = 'sk_t;H-@bzcyMUY!PI43X+dfe@cyE4h6';
    // $timestamp         = time();
    
    // $sandbox_token = md5(
    //     $timestamp .
    //     $plugin_id .
    //     $plugin_secret_key .
    //     $plugin_public_key .
    //     'checkout'
    // );
?>
<!-- <button id="purchase">Buy Button</button>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://checkout.freemius.com/checkout.min.js"></script>
<script>
    var handler = FS.Checkout.configure({
        plugin_id:  '9640',
        plan_id:    '16255',
        public_key: 'pk_003a113029f345484bc3c235af8f1',
        image:      'https://your-plugin-site.com/logo-100x100.png',
        // IMPORTANT:
        // Remove timestamp and sandbox token before deployment to production,
        // otherwise, user will be able to upgrade with dummy credit-cards.
        timestamp:     '<?php //echo $timestamp ?>',
        sandbox_token: '<?php //echo $sandbox_token ?>'
    });
    
    $('#purchase').on('click', function (e) {
        handler.open({
            name     : 'WP Affiliate Magnet',
            licenses : 1,
            // You can consume the response for after purchase logic.
            purchaseCompleted  : function (response) {
                // The logic here will be executed immediately after the purchase confirmation.                                // alert(response.user.email);
            },
            success  : function (response) {
                // The logic here will be executed after the customer closes the checkout, after a successful purchase.                                // alert(response.user.email);
            }
        });
        e.preventDefault();
    });
</script> -->
<?php
//}