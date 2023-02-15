<?php
/**
** Broken Links
**/
class wpam_BrokenLink {
	/**
	 * Constructor method for loading the components
	 */
	public function __construct() {
        add_action('admin_menu', [$this, 'broken_menu']);
		
		add_action( 'wp_ajax_nopriv_check_broken_link', [$this, 'check_broken_link_process'] );
		add_action( 'wp_ajax_check_broken_link', [$this, 'check_broken_link_process'] );
		add_action( 'check_broken_link', [$this, 'check_broken_link_process'] );
    }

    public function broken_menu() {
        // if (! is_pro_license()) {
    	if ( wamm_fs()->is__premium_only() ) {
            if ( wamm_fs()->can_use_premium_code() ) {
            	add_submenu_page(AME()->wpam_link, 'Broken Links', 'Broken Links', 'manage_options', 'wpam_broken_link', [$this,'wpam_broken_links_page'], 7);
        	}
        }
    }

    
	function wpam_broken_links_page() {
        require_once(__DIR__ . '/templates/broken_index.php');
	}

	

	public function check_broken_link_process() {
		global $wpdb;
		$qry = "SELECT * FROM " . $wpdb->prefix . "wpam_links WHERE link_status = 1";
		$results = $wpdb->get_results( $qry );
		$brokenLinks = [];
		if ($start == 0) {
			$wpdb->query("TRUNCATE TABLE " . $wpdb->prefix . "wpam_broken_links");
		}
		
		foreach ($results as $result) {
			
			if ( !wpam_url_exist($result->url) ) {
				$bokenLink = [
					'link_id'	=> $result->id,
					'title'		=> $result->name,
					'url'		=> $result->url,
					'slug'		=> $result->slug,
					'link_cpt_id'	=> $result->link_cpt_id
				];
				$wpdb->insert($wpdb->prefix.'wpam_broken_links', $bokenLink);
				$bokenLink['link_cpt_url'] = get_edit_post_link($result->link_cpt_id);
				$brokenLinks[] = $bokenLink;
			}
		}
		$return = [
			'status'		=> 1,
			'brokenLinks'	=> $brokenLinks
		];
		
		echo json_encode($return); wp_die();
	}

}

new wpam_BrokenLink();