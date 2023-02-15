<?php
/**
** Register post meta
**/

class wpam_Admin_Meta {
	/**
	 * Constructor method for loading the components
	 */
	public function __construct() {
		add_action('add_meta_boxes', array($this, 'add_wpam_meta'));
		add_action('save_post', array($this, 'save_wpam_meta'));
		add_action('delete_post', array($this, 'delete_wpam_links'));
		add_action('wp_trash_post', array($this, 'trash_wpam_links'));
		add_action('untrash_post', array($this, 'untrash_wpam_links'));
		add_action('admin_init', array($this, 'remove_page_excerpt_field'));
	}

	/**
	** Create and save meta fields
	**/
	public function add_wpam_meta() {
		add_meta_box('csm-id','WP Affiliate Magnet Link Settings', array($this, 'wpam_post_meta'),'wpam_link','normal');
		add_meta_box('wpam-id','Optional Settings', array($this, 'wpam_post_advanced_meta'),'wpam_link','normal');
	}
	
	/**
	** Add Meta boxes
	**/
	public function wpam_post_meta($post) {
		require_once(__DIR__ . '/templates/wpam_post_meta.php');
	}
	/**
	** Add Advanced Meta boxes
	**/
	
	public function wpam_post_advanced_meta($post){
		// if ( !is_pro_license() ) {
			require_once(__DIR__ . '/templates/wpam_post_advanced_meta.php');
		// } else {
			// echo '<span class="errorpopup"></span>';
		// }
	}
	
	
	/**
	** Save Meta boxes and redirect value in the table
	**/
	public function save_wpam_meta($post_id){
		
		global $wpdb,$post;
		
		/**
		** Save Pro settings
		**/

		// echo "<pre>"; print_r($_POST); exit;
		if(isset($_POST['wpam_expire']) || isset($_POST['wpam_expire_number']) || isset($_POST['wpam_expire_redirect']) || isset($_POST['wpam_expire_redirect_url']) || isset($_POST['wpam_expire_keywords'])){
			update_post_meta($post_id,'wpam_expire',$_POST['wpam_expire']);
			update_post_meta($post_id,'wpam_expire_number',$_POST['wpam_expire_number']);
			update_post_meta($post_id,'wpam_expire_redirect',$_POST['wpam_expire_redirect']);
			update_post_meta($post_id,'wpam_expire_redirect_url',$_POST['wpam_expire_redirect_url']);
			update_post_meta($post_id,'wpam_expire_keywords',$_POST['wpam_expire_keywords']);
		}
		
		
		/**
		** Save Advanced settings
		**/
		if(isset($_POST['wpam_nofollow']) || isset($_POST['wpam_sponsored']) || isset($_POST['wpam_tracking']) || isset($_POST['wpam-meta-note'])){
			update_post_meta($post_id,'wpam_nofollow',$_POST['wpam_nofollow']);
			update_post_meta($post_id,'wpam_sponsored',$_POST['wpam_sponsored']);
			update_post_meta($post_id,'wpam_tracking',$_POST['wpam_tracking']);
			update_post_meta($post_id,'wpam-meta-note',$_POST['wpam-meta-note']);
		}
		
		/**
		** Save Basic Settings
		**/
		$lError = false;
		if ( isset($_POST['wpam-meta-link']) && !empty( trim($_POST['wpam-meta-link'])) ) {
			$sql = "select post_id from $wpdb->postmeta where meta_key='wpam-meta-link' and meta_value = '".$_POST['wpam-meta-link']."' and post_id != '".$post_id."'";
			if ($wpdb->get_row($sql, ARRAY_A )) {
				$lError = "New Link Aleady Exist, Please Choose Another.";
			}
		} elseif ( isset($_POST['wpam-meta-link'])) {
			$lError = 'New Link is Empty.';
		}

		if ($lError) {
			add_settings_error(
				'meta-link-error',
				'meta-link-error',
				$lError,
				'error'
			);
			set_transient( 'settings_errors', get_settings_errors(), 30 );
		}
		if ( isset($_POST['wpam-meta-target']) && isset($_POST['wpam-meta-link']) && $lError == false ) {
			if (!isset($_POST['wpam-meta-redirect']) || empty($_POST['wpam-meta-redirect'])) {
				$type_redirect = get_option( 'wpam_global_meta_redirect' );
			} else {
				$type_redirect = $_POST['wpam-meta-redirect'];
			}
			update_post_meta($post_id, 'wpam-meta-redirect', $type_redirect);
			update_post_meta($post_id, 'wpam-meta-target', $_POST['wpam-meta-target']);
			update_post_meta($post_id, 'wpam-meta-link', $_POST['wpam-meta-link']);
			update_post_meta($post_id,'wpam_enable_link',$_POST['wpam_enable_link']??'off');
			$valueid = $wpdb->get_var("SELECT id FROM ".$wpdb->prefix."wpam_links WHERE link_cpt_id =".$post_id);
			if($_POST['post_title'] == ""){
				$title = $_POST['wpam-meta-link'];
				$wpdb->update(
					$wpdb->prefix .'posts', 
					array( 
						'post_title' => $title,
					), 
					array(
						"ID" => $post_id
					) 
				);
			} else {
				$title = $_POST['post_title'];
			}
			
			if($valueid == ""){
				$wpdb->insert($wpdb->prefix .'wpam_links', array(
					'name' => $title,
					'description' => $_POST['wpam-meta-note'],
					'url' => $_POST['wpam-meta-target'],
					'slug' => $_POST['wpam-meta-link'],
					'redirect_type' => $type_redirect,
					'link_cpt_id' => $post_id, 
					'link_status' => 1, 
				));
					$url=  admin_url().'edit.php?post_type=wpam_link';
					wp_redirect($url);
					exit;
			} else {
				$result = $wpdb->update(
					$wpdb->prefix .'wpam_links', 
					array( 
						'name' => $title,
						'description' => $_POST['wpam-meta-note'],
						'url' => $_POST['wpam-meta-target'],
						'slug' => $_POST['wpam-meta-link'],
						'redirect_type' => $type_redirect,
					), 
					array(
						"id" => $valueid
					) 
				);
				$url=  admin_url().'edit.php?post_type=wpam_link';
				wp_redirect($url);
				exit;
			}
		}
	}
	
	/**
	** Delete meta if post wpam post deleted
	**/
	
	public function delete_wpam_links($post_id){
		
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpam_links';
		if ($wpdb->get_var($wpdb->prepare("SELECT `id` FROM $table_name WHERE `link_cpt_id` = %d", $post_id))) {
			$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE `link_cpt_id` = %d", $post_id));
		}
		
	}
	
	/**
	** Change link status if post wpam is in trash
	**/
	
	public function trash_wpam_links($post_id){
		global $wpdb;
		$valueid = $wpdb->get_var("SELECT id FROM ".$wpdb->prefix."wpam_links WHERE link_cpt_id =".$post_id);
		$result = $wpdb->update(
			$wpdb->prefix .'wpam_links', 
			array( 
				'link_status' => 0,
			), 
			array(
				"id" => $valueid
			) 
		);
	}
	
	/**
	** Change link status if post wpam retored from trash
	**/
	
	public function untrash_wpam_links($post_id){
		global $wpdb;
		$valueid = $wpdb->get_var("SELECT id FROM ".$wpdb->prefix."wpam_links WHERE link_cpt_id =".$post_id);
		$result = $wpdb->update(
			$wpdb->prefix .'wpam_links', 
			array( 
				'link_status' => 1,
			), 
			array(
				"id" => $valueid
			) 
		);
	}
	
	/**
	** Hide Post attribute
	**/
	public function remove_page_excerpt_field(){
		remove_meta_box( 'pageparentdiv' , 'wpam_link' , 'normal' ); 
	}
}

new wpam_Admin_Meta();