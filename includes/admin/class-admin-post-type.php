<?php
/**
** Register post type and actions
**/
class wpam_Admin_PostType {
	/**
	 * Constructor method for loading the components
	 */
	public function __construct() {
		add_action( 'init', array($this, 'wpam_register_post_type') );
		add_action( 'init', array($this, 'wpam_taxonomies'), 0 );
		add_action( 'init', array($this, 'wpam_tag_taxonomies') );
		
		add_filter( 'post_row_actions', array($this, 'wpam_add_remove_row_actions'), 10, 1 );
		add_filter( 'manage_wpam_link_posts_columns', array($this, 'wpam_filter_posts_columns') );
		add_action( 'manage_wpam_link_posts_custom_column', array($this, 'wpam_realestate_column'), 10, 2);
		add_action( 'admin_menu', array($this, 'wpam_add_page_click'));

		/* Extra Fields on Category */
		add_action('wpam_category_add_form_fields', array($this,'add_term_image'), 10, 2);
		add_action('created_wpam_category', array($this,'save_term_image'), 10, 2);
		add_action('wpam_category_edit_form_fields', array($this,'edit_image_upload'), 10, 2);
		add_action('edited_wpam_category', array($this,'update_image_upload'), 10, 2);
		add_action( 'admin_enqueue_scripts', array($this,'image_uploader_enqueue') );
		add_action( 'admin_head', array($this, 'wpse_66020_add_jquery') );
		
		add_action('quick_edit_custom_box', array($this, 'quick_bulk_edit_add'), 10, 2);
		add_action('save_post', array($this, 'save_quick_edit'), 10, 2);
        add_action('init', function(){
            if (isset($_COOKIE['wpam_message'])) {
                define('wpam_message', $_COOKIE['wpam_message']);
                setcookie('wpam_message', '', time() - 10000);
            }
        });
	}

	/**
	* Register Post type
	**/
	public function wpam_register_post_type() {
		$url = wpam_PLUGIN_DIR_URL . 'assets/images/moneymagnet.png';
		$labels = array( 
			'name' => __( 'WP Affiliate Magnet' , 'wpam' ),
			'singular_name' => __( 'WP Affiliate Magnet' , 'wpam' ),
			'menu_name'=>__('WP Affiliate Magnet', 'wpam'),
			'all_items'=> __('My Affiliate Links'),
			'add_new' => __( 'Create New Link' , 'wpam' ),
			'add_new_item' => __( 'Add New Link' , 'wpam' ),
			'edit_item' => __( 'Edit Link' , 'wpam' ),
			'new_item' => __( 'New Link' , 'wpam' ),
			'view_item' => __( 'View Link' , 'wpam' ),
			'search_items' => __( 'Search Links' , 'wpam' ),
			'not_found' =>  __( 'No Links Found' , 'wpam' ),
			'not_found_in_trash' => __( 'No Links found in Trash' , 'wpam' ),
		);
		$args = array(
			'labels' => $labels,
			'public' => false,  
			'publicly_queryable' => true, 
			'show_ui' => true,
			'exclude_from_search' => true,
			'show_in_nav_menus' => false,
			'has_archive' => false,
			'rewrite' => false,
			'menu_icon'           => $url,
			'supports' => array(
				'title', 
				'page-attributes'
			)
		);
		
		// if ( wpam_is_activate() ) {
			// $args['capabilities'] = ['create_posts' => 'do_not_allow'];
			// $args['map_meta_cap'] = false;
		// } else {
		// if( wamm_fs()->is_free_plan()){
			register_post_type( 'wpam_link', $args );
		// }
		// }

		// if ( is_pro_license() ) {
			// add_action( 'pre_get_posts', [$this, 'wpam_post_query'] );
		// }
	}

	/** 
	** Register Taxonomy
	**/
	public function wpam_taxonomies() {
		
			// $html_content = '';
		$labels = array(
		'name'              => _x( 'Categories', 'taxonomy general name' ),
		'singular_name'     => _x( 'Category', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Categories' ),
		'all_items'         => __( 'All Categories' ),
		'parent_item'       => __( 'Parent Category' ),
		'parent_item_colon' => __( 'Parent Category:' ),
		'edit_item'         => __( 'Edit Category' ), 
		'update_item'       => __( 'Update Category' ),
		'add_new_item'      => __( 'Add New Category' ),
		'new_item_name'     => __( 'New Category' ),
		'menu_name'         => __( 'Categories' ),
		);
		$args = array(
		'labels' => $labels,
		'hierarchical' => true,
		'show_admin_column' => true,

		);
		// if (!is_pro_license()) {
		// if( wamm_fs()->is_free_plan()){
			register_taxonomy( 'wpam_category', 'wpam_link', $args );
		// }
		// }
	}
	
	/** 
	** Register Tags
	**/

	public function wpam_tag_taxonomies() 
	{
		
		$labels = array(
		'name' => _x( 'Tags', 'taxonomy general name' ),
		'singular_name' => _x( 'Tag', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Tags' ),
		'popular_items' => __( 'Popular Tags' ),
		'all_items' => __( 'All Tags' ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __( 'Edit Tag' ), 
		'update_item' => __( 'Update Tag' ),
		'add_new_item' => __( 'Add New Tag' ),
		'new_item_name' => __( 'New Tag Name' ),
		'separate_items_with_commas' => __( 'Separate tags with commas' ),
		'add_or_remove_items' => __( 'Add or remove tags' ),
		'choose_from_most_used' => __( 'Choose from the most used tags' ),
		'menu_name' => __( 'Tags' ),
		); 

		// if (!is_pro_license()) {
		// if( wamm_fs()->is_free_plan()){
		register_taxonomy('tag','wpam_link',array( 
			'hierarchical' => false,
			'labels' => $labels,
			'show_ui' => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var' => true,
			'rewrite' => array( 'slug' => 'tag' ),
			'show_admin_column' => true,
		));
		// }
	}
	
	/**
	** Remove view link from admin column
	**/
	public function wpam_add_remove_row_actions( $actions) {
		if ( get_post_type() === 'wpam_link' ) {
			$link = get_post_meta(get_the_ID(), 'wpam-meta-link', true);
			$target = get_post_meta(get_the_ID(), 'wpam-meta-target', true);
			$link = wpam_SITE_URL . "/" . $link;
			unset( $actions['view'] );
			$actions['target_link'] = '<a target="_blank" href="'.$target.'" class="wpam_link">' . __('Affiliate Link') . '</a>';
			$actions['wpam_link'] = '<a target="_blank" href="'.$link.'" class="wpam_link">' . __('New Link') . '</a>';
		}
		return $actions;
	}

	/** 
	** add extra column to admin post listing
	**/ 
	
	public function wpam_filter_posts_columns( $columns ) {
		$columns['clicks'] = __( '<span title="Total Analytics/Unique Analytics">Analytics</span>' );
		$columns['copylink'] = __( 'New Link' );
		$columns['wplm_status'] = __( '<div class="text-center">Status</div>' );
		$columns[ 'title' ] = 'Title';  

		return $columns;
	}
	
	public function wpam_realestate_column( $column, $post_id ) {
		global $wpdb;
		
		$value = $wpdb->get_var("SELECT slug FROM " . $wpdb->prefix . "wpam_links WHERE link_cpt_id =" . $post_id);
		$value = wpam_SITE_URL . "/" . $value;
		
		$unique_val = $wpdb->get_results("SELECT ip FROM ".$wpdb->prefix."wpam_clicks WHERE link_id =".$post_id." GROUP BY ip");
		$uniq_val = count($unique_val);
		
		$click_count = get_post_meta($post_id, 'post_views_count', true);

		if ($click_count > 0) {
			$out_click = 1;
		} else {
			$out_click = 0;
		}
		if ($click_count == "") {
			$click_count = 0;
		}
		
		if ( 'clicks' === $column ) {
			echo "<span title='Total Analytics/Unique Analytics'>" . $click_count . "/" . $uniq_val . "</span>";
		}
		if ( 'copylink' === $column ) {
			$copyImg = AME()->plugin_url() . "assets/images/copy.png";
			echo '<input type="text" value="'  . $value . '" id="myInput' . $post_id.'">
			<div class="tooltip">
				<a href="javascript:void(0);" onclick="copyToClipboard(' . $post_id . ')" onmouseout="outFunc('  .$post_id . ')">&nbsp;<span class="tooltiptext" id="myTooltip' . $post_id . '">Copy URL</span><img src="' .  $copyImg . '" alt="Copy URL" style="height:30px;"></a>
			</div>';
		}
		if ( 'wplm_status' === $column ) {
			$wplm_status = get_post_meta($post_id, 'wpam_enable_link', true);
			$st_img = $wplm_status=='on' ? 'success-check.png' : 'block.png';
			echo '<div class="text-center"><img class="sm-img" src="' . wpam_PLUGIN_DIR_URL . 'assets/images/' . $st_img . '"></div>';
		}
	}
	
	/**
	** Add submenu pages
	**/
	public function wpam_add_page_click(){
		// if (!is_pro_license()) {
		// 	add_submenu_page( AME()->wpam_link, 'Unlock Features', '<b class="target_blank wpam-text-danger">Unlock Features</b>', 'manage_options', AME()->ame_upgrade_url, '', 10);
		// }
		// add_submenu_page( AME()->wpam_link, 'License', 'License', 'manage_options', '/options-general.php?page=' . AME()->license_page, '', 12);
		add_submenu_page( AME()->wpam_link, '<b>Get Help</b>', '<b class="target_blank">Get Help</b>', 'manage_options', AME()->help_support_url, '', 9);
	}

	/* Extra fields for Category */
	function add_term_image($taxonomy){
		?>
		<script>
			jQuery(document).ready(function() {
				jQuery('form#addtag').before('<div><strong>Html Content Area Here</strong></div>');
			});
		</script>
		<div class="form-field term-group">
			<label for="">Upload Image</label>
			<input type="text" name="txt_upload_image" id="txt_upload_image" value="" style="width: 66%">
			<input type="button" id="upload_image_btn" class="button" value="Upload an Image" />
		</div>
		<?php
	}

	function save_term_image($term_id, $tt_id) {
		if (isset($_POST['txt_upload_image']) && '' !== $_POST['txt_upload_image']){
			$group = $_POST['txt_upload_image'];
			add_term_meta($term_id, 'term_image', $group, true);
		}
	}

	function edit_image_upload($term, $taxonomy) {
		// get current group
		$txt_upload_image = get_term_meta($term->term_id, 'term_image', true);
	?>
	
	<script>
		jQuery(document).ready(function() {
			jQuery('form#edittag').before('<div><strong>Html Content Area Here</strong></div>');
		});
	</script>
		<tr>
			<th>
				<label for="">Upload Image</label>
			</th>
			<td>
				<img src="<?php echo $txt_upload_image; ?>" style="max-width:100px; max-height: 100px;" />
				<br>
				<input type="text" name="txt_upload_image" id="txt_upload_image" value="<?php echo $txt_upload_image ?>" style="width: 66%">
				<input type="button" id="upload_image_btn" class="button" value="Upload an Image" />
			</td>
		</tr>
	<?php
	}

	function update_image_upload($term_id, $tt_id) {
		if (isset($_POST['txt_upload_image']) && '' !== $_POST['txt_upload_image']){
			$group = $_POST['txt_upload_image'];
			update_term_meta($term_id, 'term_image', $group);
		}
	}

	function image_uploader_enqueue() {
		global $typenow;
		if( ($typenow == 'wpam_link') ) {
			wp_enqueue_media();
		
			wp_register_script( 'meta-image', wpam_PLUGIN_DIR_URL . '/assets/js/media-uploader.js', array( 'jquery' ) );
			wp_localize_script( 'meta-image', 'meta_image',
				array(
					'title' => 'Upload an Image',
					'button' => 'Use this Image',
				)
			);
			wp_enqueue_script( 'meta-image' );
		}
	}

	public function wpse_66020_add_jquery() {
		?>
		<script type="text/javascript">
			jQuery(document).ready( function($) {   
				jQuery('.target_blank').parent().attr('target','_blank');  
			});
		</script>
		<?php
	}
	
	function wpam_post_query( $query ) {
		if (is_admin() && $query->is_main_query() && $query->get('post_type') == 'wpam_link') {
			$query->set('posts_per_page', AME()->ame_limit);
			$query->set( 'orderby', 'ID' );
			$query->set( 'order', 'ASC');
			if ( is_search() ) {
				$query->is_search = false;
				$query->query_vars['s'] = false;
				$query->query['s'] = false;
				$_GET['s'] = '';
			}
		}
	}

	public function quick_bulk_edit_add($column, $post_type) {
		if($column != 'wplm_status' || $post_type != 'wpam_link') { return; }
		global $post_id, $post;
		$metaRedirect = get_post_meta($post->ID, 'wpam-meta-redirect', true);
		?>
		  <fieldset class="inline-edit-col-right inline-edit-wplm-<?php echo "{$column}"; ?>">
			<div class="inline-edit-group">
			  <label>
				<span class="title">Redirection Code</span>
				<select required id="redirect_type" name="wpam-meta-redirect">
                    <option value="307" <?php if($metaRedirect == 307){echo "selected";}?>>307 (Temporary)&nbsp;</option>
                    <option value="302" <?php if($metaRedirect == 302){echo "selected";}?>>302 (Temporary)&nbsp;</option>
                    <option value="301" <?php if($metaRedirect == 301){echo "selected";}?>>301 (Permanent)&nbsp;</option>
                    <option value="cloaked" <?php if($metaRedirect == "cloaked"){echo "selected";}?>>Cloaked&nbsp;</option>
                </select>
				<br/>
			  </label>
			</div>
		  </fieldset>
		<?php
	}

	public function save_quick_edit($post_id, $post) {
	
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return $post_id; }
		if(isset($post->post_type) && $post->post_type == 'revision') { return $post_id; }
		if(!isset($_POST['action']) || $_POST['action'] != 'inline-save') { return $post_id; } // This action is set when doing Quick Edit save
	
		if($post->post_type == 'wpam_link') {
			update_post_meta($post_id, 'wpam-meta-redirect', $_POST['wpam-meta-redirect']);
		}
	}
	
}
new wpam_Admin_PostType();