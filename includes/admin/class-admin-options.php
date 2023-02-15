<?php
if(isset($_POST['submit'])){
	
	    update_option( 'wpam_global_meta_redirect', $_POST['wpam_global_meta_redirect'] );	
		update_option( 'wpam_global_nofollow', $_POST['wpam_global_nofollow'] );	
		update_option( 'wpam_global_sponsored', $_POST['wpam_global_sponsored'] );	
		update_option( 'wpam_global_tracking', $_POST['wpam_global_tracking'] );	
	
}
$wpam_global_redirect = get_option( 'wpam_global_meta_redirect' );
$wpam_global_nofollow = get_option( 'wpam_global_nofollow' );
$wpam_global_sponsored = get_option( 'wpam_global_sponsored' );
$wpam_global_tracking = get_option( 'wpam_global_tracking' );
?>
<div class="wrap click-dashboard">
	<h1 class="wp-heading-inline">Default Settings</h1>
</div>
<form method="post" action="">
	<table class="form-table wpam_table wpam_basic">
		<tbody>
			<tr>
				<th>Redirection* 
					<span class="magnet-tooltip">
					  <span><i class="fa fa-info-circle" aria-hidden="true"></i></span>
					  <span class="magnet-content">
							<i class="fa fa-flag" aria-hidden="true"></i>
						  <h3>Redirection</h3>
						  <p class="magnet-data-info">Please select the redirection type.</p>
						  <p class="close">Close</p>
					  </span>
					</span>
				</th>
				<td>
					<select required id="redirect_type" name="wpam_global_meta_redirect">	
						<option value="307" <?php if($wpam_global_redirect == 307){echo "selected";}?>>307 (Temporary)&nbsp;</option>
						<option value="302" <?php if($wpam_global_redirect == 302){echo "selected";}?>>302 (Temporary)&nbsp;</option>
						<option value="301" <?php if($wpam_global_redirect == 301){echo "selected";}?>>301 (Permanent)&nbsp;</option>
						<option value="cloaked" <?php if($wpam_global_redirect == "cloaked"){echo "selected";}?>>Cloaked&nbsp;</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>
					Enable No-Follow Attribute 
					<span class="magnet-tooltip">
					  <span><i class="fa fa-info-circle" aria-hidden="true"></i></span>
					  <span class="magnet-content">
							<i class="fa fa-flag" aria-hidden="true"></i>
						  <h3>Enable No-Follow Attribute</h3>
						  <p class="magnet-data-info">Default all new links to be tracked.</p>
						  <p class="close">Close</p>
					  </span>
					</span>
				</th>
				
				<td><input type="checkbox" name="wpam_global_nofollow" <?php if( isset ( $wpam_global_nofollow) && $wpam_global_nofollow == "on"){ echo "checked";} ?>></td>
			</tr>
			<tr>
				<th>Enable Sponsored Attribute  
					<span class="magnet-tooltip">
					  <span><i class="fa fa-info-circle" aria-hidden="true"></i></span>
					  <span class="magnet-content">
							<i class="fa fa-flag" aria-hidden="true"></i>
						  <h3>Enable Sponsored Attribute</h3>
						  <p class="magnet-data-info">Default all new links to be tracked.</p>
						  <p class="close">Close</p>
					  </span>
					</span>
				</th>
				<td><input type="checkbox" name="wpam_global_sponsored" <?php if( isset ( $wpam_global_sponsored) && $wpam_global_sponsored == "on"){ echo "checked";} ?>></td>
			</tr>
			<tr>
				<th>Enable Analytics Tracking 
					<span class="magnet-tooltip">
					  <span><i class="fa fa-info-circle" aria-hidden="true"></i></span>
					  <span class="magnet-content">
							<i class="fa fa-flag" aria-hidden="true"></i>
						  <h3>Enable Analytics Tracking</h3>
						  <p class="magnet-data-info">Default all new links to be tracked.</p>
						  <p class="close">Close</p>
					  </span>
					</span>
				</th>
				<td><input type="checkbox" name="wpam_global_tracking" <?php if( isset ( $wpam_global_tracking) && $wpam_global_tracking == "on"){ echo "checked";} ?>></td>
			</tr>
			<tr>
				<td><input type="submit" name="submit" value="SAVE" class="button"></td>
			</tr>
		</tbody>
	</table>
</form>