<?php
/**
** Create dashboard widget
**/

class wpam_Admin_Dashboard_Meta {
	/**
	 * Constructor method for loading the components
	 */
	public function __construct() {
		add_action('wp_dashboard_setup',array($this,'wpam_dashboard_widgets'));
	}

	public function wpam_dashboard_widgets() {
		global $wp_meta_boxes;
		wp_add_dashboard_widget('wpam_dashboard_widget', 'WP Affiliate Magnet Quick Add Links', array($this,'wpam_dashboard_meta'), 'side', 'high');
		wp_add_dashboard_widget('wpam_impression_dashboard_widget', 'WP Affiliate Magnet Impressions Chart', array($this,'wpam_dashboard_impressions'), 'side', 'high');
	}

	public function wpam_dashboard_meta() {
		global $wpdb, $post;
	
		echo '<form id="record" method="POST" action="">
				<table class="form-table">
					<tbody>
						<tr class="form-field">
							<td valign="top">Affiliate URL</td>
							<td><input type="URL" name="target_url" id="target_url" placeholder="Affiliate URL">
							</td>
						</tr>
						<tr>
							<td valign="top">New Link</td>
							<td><strong>'.wpam_SITE_URL.'</strong>/<input type="text" name="wpam_link" id="wpam_link" placeholder="New Link">
							</td>
						</tr>
					</tbody>
				</table>
				<input class="button button-primary" type="submit" value="SAVE">
			</form>';
		
		/**
		** Save meta values
		**/ 	
		if($_POST){
			$my_post = array(
				'post_title'    => $_POST['wpam_link'],
				'post_status'   => 'publish',
				'post_author'   => 1,
				'wpam_enable_link' => 1,
				'post_type' =>	'wpam_link'
			);

			// Insert the post into the database.
			$post_id = wp_insert_post( $my_post, $wp_error );
			if ($post_id) {
				$type_redirect = get_option( 'wpam_global_meta_redirect' );
				update_post_meta($post_id,'wpam-meta-redirect', $type_redirect);
				$on = update_post_meta($post_id, 'wpam_enable_link', 'on');
				update_post_meta($post_id,'wpam-meta-target', $_POST['target_url']);
				update_post_meta($post_id,'wpam-meta-link', $_POST['wpam_link']);
				update_post_meta($post_id,'wpam-meta-note', '');
				$valueid = $wpdb->get_var("SELECT id FROM ".$wpdb->prefix."wpam_links WHERE link_cpt_id =".$post_id);
				if ($valueid == "") {
					$wpdb->insert($wpdb->prefix .'wpam_links', array(
						'name' => $title,
						'url' => $_POST['target_url'],
						'slug' => $_POST['wpam_link'],
						'redirect_type' => $type_redirect,
						'link_cpt_id' => $post_id, 
						'link_status' => 1, 
					));
				}
			}
		}
	}
	
	/**
	** Impressions Chart
	**/
	public function wpam_dashboard_impressions(){
		require_once wpam_PLUGIN_INCLUDES_PATH . '/admin/templates/wpam-clicks-chart.php';
	}
}
new wpam_Admin_Dashboard_Meta();