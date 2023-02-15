<?php
/**
** Duplicate Validation
**/
class DuplicateTitle {
	/**
	 * Constructor method for loading the components
	 */
	public $postType  = 'wpam_link';
	public function __construct() {
		add_action( 'admin_enqueue_scripts', [$this, 'duplicate_titles_enqueue_scripts'], 2000 );
		add_action('wp_ajax_title_checks', [$this, 'duplicate_title_checks_callback']);
		add_action( 'save_post', [$this, 'duplicate_titles_wallfa_bc'] ) ;
		add_action( 'admin_notices', [$this, 'not_published_error_notice'] ); 
		add_action( 'wp_print_scripts', [$this, 'disable_autosave'] ) ;      
    }

	//jQuery to send AJAX request 
	public function duplicate_titles_enqueue_scripts( $hook ) {
		global $post_type;
		if ($post_type != $this->postType) return;
		if( !in_array( $hook, array( 'post.php', 'post-new.php' , 'edit.php'))) return;
		wp_enqueue_script('duptitles',
		wp_enqueue_script('duptitles', wpam_PLUGIN_DIR_URL.'/assets/js/duptitles.js', array( 'jquery' )), array( 'jquery' )  );
	}
 
	/// callback ajax 
	public function duplicate_title_checks_callback() {
		global $wpdb;
		$title = $_POST['post_title'];
		$post_id = $_POST['post_id'];
		$titles = "SELECT post_title FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = '".$this->postType."' AND post_title = '{$title}' AND ID != {$post_id} ";
		$results = $wpdb->get_results($titles);
		if($results) {
			$msg = '<span style="color:red;">'. __( 'Duplicate title detected, please change the title.' ) .'</span>';
		} else {
			$msg =  '<span style="color:green;">'.__('This title is unique.' , 'dublicate-title-validate').'</span>';
		}
		echo $msg;
		die();
	}
 
	// this chek backend title and if Duplicate update status draft .
	public function duplicate_titles_wallfa_bc( $post ) {
		global $wpdb, $post_type;
		if ($post_type != $this->postType) return;
		$title = $_POST['post_title'] ;
		$post_id = $post ;
		$wtitles = "SELECT post_title FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = '".$this->postType."' AND post_title = '{$title}' AND ID != {$post_id} " ;
		$wresults = $wpdb->get_results( $wtitles ) ;
		if ( $wresults ) {
			$wpdb->update( $wpdb->posts, array( 'post_status' => 'draft' ), array( 'ID' => $post ) ) ;
			$arr_params = array( 'message' => '10', 'wallfaerror' => '1' )  ;      
			$location = add_query_arg( $arr_params , get_edit_post_link( $post , 'url' ) ) ;
			wp_redirect( $location  ) ;
			exit ; 
		}
	}
 
	/// handel error for back end 
	public function not_published_error_notice() {
		if(isset($_GET['wallfaerror']) == 1 ){
		?>
			<div class="updated">
				<p style='color:red' ><?php _e('Title used for this post appears to be a duplicate. Please modify the title' , 'dublicate-title-validate') ?></p>
			</div>
		<?php
		}
	}
 
	public function disable_autosave() {
		global $post_type;
		if ($post_type == $this->postType) {
			wp_deregister_script( 'autosave' ) ;
		}
	}
}
new DuplicateTitle();