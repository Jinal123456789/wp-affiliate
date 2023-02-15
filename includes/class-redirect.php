<?php
/**
** Get the data that needs to redirect
**/
class wpam_Redirect_Links {
	/**
	 * Constructor method for loading the components
	 */
	public function __construct() {
		add_action( 'template_redirect', array($this,'wpse_inspect_page_id' ));
		add_filter( 'the_content', array($this,'link_words') );
		add_filter( 'the_excerpt', array($this,'link_words') ); 
	}
	
	/**
	** Replace text with keywords and URL
	**/
	public function link_words( $text ) {
		global $wpdb;
		$metas = $wpdb->get_results( 
			$wpdb->prepare("SELECT meta_value,post_id FROM $wpdb->postmeta where meta_key = %s", 'wpam_expire_keywords')
		);
		$array_link = array();
		foreach ($metas as $meta) {
			$val_keywords = $meta->meta_value;
			$val_keywords_id = $meta->post_id;
			$target_link = get_post_meta($val_keywords_id, "wpam-meta-target", true);
			$target_new_link = get_post_meta($val_keywords_id, "wpam-meta-link", true);
			$nofollow_val = get_post_meta($val_keywords_id, "wpam_nofollow", true);
			$newlink=get_post_meta($val_keywords_id, 'wpam_enable_link', true);
			$nofollow_val_option = get_option( 'wpam_global_nofollow' );
			if ($nofollow_val_option == "on") {
				$rel = "nofollow, noindex";
				$target = "_blank";
			} elseif ($nofollow_val == "on") {
				$rel = "nofollow, noindex";
				$target = "_blank";
			} else {
				$rel = "";
				$target = "";
			}
			
			if($newlink=="on"){
				$target_val=site_url('/').$target_new_link;
			}else{
				$target_val=$target_link;
			}
			$finalval = explode(",",$val_keywords);
			foreach ($finalval as $result) {
				$array_link[$result] = '<a rel="'.$rel.'" target="'.$target.'" href="'.$target_val.'">'.$result.'</a>'; 
			}
		}
		
		$text = str_replace( array_keys($array_link), $array_link, $text );
		return $text;
	}

	/**
	** Redirect code
	**/
	public function wpse_inspect_page_id() {
		global $wpdb;
		// if (!is_pro_license()) {
			$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."wpam_links WHERE link_status =1" );
		// } else {
		// 	$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."wpam_links limit " . AME()->ame_limit );
		// }
		$page_object = get_queried_object();
		$page_id = get_queried_object_id();
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header("Expires: Mon, 07 Jul 1777 07:07:07 GMT");
		
		$url = explode("/",$_SERVER['REQUEST_URI']);
		$match_url = array_reverse($url);
		$match_url = $match_url[0];

		
		$current_url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$match_url = ltrim( str_replace( str_replace('https://', 'http://', site_url()), '', $current_url), '/');
		// echo "<pre>"; print_r( AME() );
		// echo 'test'; exit;

		
		foreach ($results as $final) {
			if ($final->link_status != 1) continue;
			if (get_post_meta($final->link_cpt_id, 'wpam_enable_link', true) == 'off') continue;

			$slug_val = $final->slug;
			$page = get_page_by_path( $slug_val );
			$db_page_id = $page->ID;
			$redirect_url = rtrim($final->url,"/");
			$redirect_url = $redirect_url."/";
			$unique_val = $wpdb->get_results("SELECT ip FROM ".$wpdb->prefix."wpam_clicks WHERE link_id =".$final->link_cpt_id." GROUP BY ip");
			$unique_val = count($unique_val);
			$click_count = get_post_meta($final->link_cpt_id,'post_views_count',true);
			$expire_count = get_post_meta($final->link_cpt_id,'wpam_expire_number',true);
			if ($final->redirect_type != "") {
				$type = $final->redirect_type;
				if ($final->redirect_type == "cloaked") {
					$type = "302";
				}
			} elseif (get_option( 'wpam_global_meta_redirect' ) != "") {
				$type = get_option( 'wpam_global_meta_redirect' );
			}
			if ($db_page_id != "") {
				if ($page_id == $db_page_id) {
					if ( !wpam_is_activate() ) {
						wp_redirect( AME()->default_redirect_url, 302 ); exit;
					}
					/**
					** Update X-Meta tags
					**/
					if ($unique_val <= $expire_count || $expire_count == "") {
						$robots_tags = array();
						
						if (get_post_meta($final->link_cpt_id,'wpam_nofollow',true) != "" || get_option( 'wpam_global_nofollow' ) != "") {
							$robots_tags[] = 'noindex';
							$robots_tags[] = 'nofollow';
						}
						if (get_post_meta($final->link_cpt_id,'wpam_sponsored',true) != "" || get_option( 'wpam_global_sponsored' ) != "") {
							$robots_tags[] = 'sponsored';
						}
						
						if (!empty($robots_tags)) {
							header("X-Robots-Tag: " . implode(', ', $robots_tags), true);
						}
						
						/**
						** Redirect to final URI
						**/
						
						$this->setPostViews($final->link_cpt_id);
						wp_redirect( $redirect_url, $type );
						exit;
					} else {
						
						$redirect_url = get_post_meta($final->link_cpt_id,'wpam_expire_redirect_url',true);
						$redirect_url_anable = get_post_meta($final->link_cpt_id,'wpam_expire_redirect',true);
						if ($redirect_url != "" && $redirect_url_anable == "on") {
							wp_redirect( $redirect_url, $type );
							exit;
						} else {
							
						}	
					}
				} else {
					
				}
			} elseif ($match_url == $final->slug) {
				/**
				** Update X-Meta tags
				**/
				if ( !wpam_is_activate() ) {
					wp_redirect( AME()->default_redirect_url, 302 ); exit;
				}
				if ($unique_val <= $expire_count || $expire_count == "") {
					$robots_tags = array();
					if (get_post_meta($final->link_cpt_id,'wpam_nofollow',true) != "") {
						$robots_tags[] = 'noindex';
						$robots_tags[] = 'nofollow';
					}
					if (get_post_meta($final->link_cpt_id,'wpam_sponsored',true) != "") {
						$robots_tags[] = 'sponsored';
					}
					if (!empty($robots_tags)) {
						header("X-Robots-Tag: " . implode(', ', $robots_tags), true);
					}
					/**
					** Redirect to final URI
					**/
					$this->setPostViews($final->link_cpt_id);
					wp_redirect( $redirect_url, $type );
					exit;
				} else {
					$redirect_url = get_post_meta($final->link_cpt_id, 'wpam_expire_redirect_url',true);
					$redirect_url_enable = get_post_meta($final->link_cpt_id,'wpam_expire_redirect',true);
					if ($redirect_url != "" && $redirect_url_enable == "on") {
						wp_redirect( $redirect_url, $type );
						exit;
					} else {
						
					}	
				}
			} else {
				
			}
		}
	}
	
	/**
	** Update Page count
	**/
	public function setPostViews($postID) {
		global $wpdb;
		$count_key = 'post_views_count';
		$count = get_post_meta($postID, $count_key, true);
		if ($count=='') {
			$count = 1;
			delete_post_meta($postID, $count_key);
			add_post_meta($postID, $count_key, '1');
		} else {
			$count++;
			update_post_meta($postID, $count_key, $count);
		}
		
		$ua = wpam_getBrowser();
		$created_at = date("Y-m-d H:i:s");
		// echo "<pre>"; print_r($ua); exit;
		$wpdb->insert( $wpdb->prefix . 'wpam_clicks', array(
			'ip' 		=> $_SERVER['REMOTE_ADDR'],
			'browser'	=> $ua['name'],
			'browser_version' => $ua['version'],
			'os' 		=> $ua['operating_system'],
			'user_agent' => $ua['userAgent'],
			'referer' 	=> $_SERVER['HTTP_REFERER'],
			'link_id' 	=> $postID,
			'created_at' => $created_at,
		));
	}	
}
new wpam_Redirect_Links();
?>