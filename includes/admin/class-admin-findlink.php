<?php
/**
** Track Locations
**/
class wpam_TackLocations {
	/**
	 * Constructor method for loading the components
	 */
	public function __construct() {
        add_action('admin_menu', [$this, 'track_menu']);
        if ( isset($_POST['wpam_action']) && $_POST['wpam_action'] == 'track_links') {
            add_action('init', [$this, 'track_links']);
        }
        add_action('init', function(){
            if (isset($_COOKIE['wpam_message'])) {
                define('wpam_message', $_COOKIE['wpam_affiliate_message']);
                setcookie('wpam_message', '', time() - 10000);
            }
        });
		add_action( 'wp_ajax_nopriv_track_links', [$this, 'track_links'] );
		add_action( 'wp_ajax_track_links', [$this, 'track_links'] );
    }

    public function track_menu() {
        // if (!is_pro_license()) {
    	if ( wamm_fs()->is__premium_only() ) {
            if ( wamm_fs()->can_use_premium_code() ) {
            	add_submenu_page(AME()->wpam_link, 'Track Location', 'Track Location', 'manage_options', 'wpam_track_locations', [$this,'wpam_track_locations_page'], 6);
        	}
        }
    }

    
	function wpam_track_locations_page() {
        require_once(__DIR__ . '/templates/track_location.php');
	}

	public function track_links() {
		global $wpdb;
		$pages = [];
		$qry = "SELECT * FROM " . $wpdb->prefix . "wpam_links WHERE link_status = 1 order by id";
		$links = $wpdb->get_results( $qry );
		foreach ($links as $link) {
			$search = site_url($link->slug);
			
			$qry = "SELECT ID, post_title FROM " . $wpdb->prefix . "posts where (post_content LIKE '%".$search."<%' || post_content LIKE '%".$search." %') and post_type!='revision'";
			if ($posts = $wpdb->get_results( $qry )) {
				foreach ($posts as $post) {
					$pages[] = [
						'link_id'	=> $link->id, 
						'link_slug'	=> $link->slug, 
						'link_title'	=> $link->name, 
						'post_id'	=> $post->ID,
						'post_title'	=> $post->post_title,
					];
				}
			}
			
			$qry = "SELECT * FROM " . $wpdb->prefix . "postmeta where meta_value LIKE '%".$search."%'";
			$results = $wpdb->get_results( $qry );
			if ($results) {
				foreach ($results as $result) {
					$pages[] = [
						'link_id'	=> $link->id, 
						'link_slug'	=> $link->slug, 
						'link_title'	=> $link->name, 
						'post_id'	=> $result->post_id,
						'post_title'	=> get_post_title($post->post_id),
					];
				}
			}
			
			
		}
		
		if ($pages && count($pages) > 0) {
            $wpdb->query("TRUNCATE TABLE " . $wpdb->prefix . "wpam_track_links");
			foreach ($pages as $page) {
				$wpdb->insert($wpdb->prefix.'wpam_track_links', $page);
			}
            echo json_encode(['status'=>'done']);
		} else {
            echo json_encode(['status'=>'error']);
        }
		// echo "<pre>"; print_r($pages); exit;
        exit;
	}

    public function delete_affiliate() {
        global $wpdb;
        if (isset($_POST['id']) && !empty($_POST['id']) && is_numeric($_POST['id'])) {
            $where = ['id' => $_POST['id']];
            $wpdb->delete( $wpdb->prefix . "wpam_affiliates", $where);
            setcookie('wpam_affiliate_message', 'Affiliate Delete Successfully.', time() + 10000);
        }
        wp_redirect(AME()->ame_url . '&page=wpam_affiliate'); exit;
    }

}

new wpam_TackLocations();