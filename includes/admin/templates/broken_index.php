<?php
wp_schedule_event( time(), 'hourly', 'check_broken_link' );
global $wpdb;
$qry = "SELECT * FROM " . $wpdb->prefix . "wpam_broken_links";
$results = $wpdb->get_results( $qry );
?>
<?php if (defined('wpam_message')) { ?>
    <div id="setting-error-settings_updated" class="notice notice-success settings-error is-dismissible"> 
        <p><strong><?php echo wpam_message; ?></strong></p>
    </div>
<?php } ?>
<br>
<h1>Check Broken Links</h1>
<hr>
<div class="comontophead">
    <h3>Click on Button to Check Broken Links</h3>
    <button type="button" class="button button-primary cmntpbtn" id="check_broken_links">Check Broken Links</button>
</div>
<div class="cmntablewrp">
    <table class="wp-list-table widefat cmntable" id="broken-link-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>New Link</th>
                <th>Broken Link</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($results) { foreach ($results as $result) { ?>
                <tr>
                    <td><?php echo $result->title; ?></td>
                    <td><?php echo $result->slug; ?></td>
                    <td><?php echo $result->url; ?></td>
                    <td><a class="button" href="<?php echo get_edit_post_link($result->link_cpt_id); ?>">Edit</a></td>
                </tr>
            <?php } }?>
        </tbody>
    </table>
</div>

<script>
jQuery(document).ready(function($) {
	$('#check_broken_links').click(function(e) {
		e.preventDefault();
		$(this).addClass('disabled').html('Processing...');
		$.ajax({
             type : "POST",
             dataType : "json",
             url : "<?php echo admin_url('admin-ajax.php'); ?>",
             data : { action: "check_broken_link" },
             success: function(response) {
				if (response.status) {
					location.reload();
				}
				$('#check_broken_links').removeClass('disabled').html('Check Broken Links');
            },
			error: function(response) {
				
				$('#check_broken_links').removeClass('disabled').html('Error please try again');
            },
        }); 
	});
});
</script>