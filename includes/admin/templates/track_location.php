<?php
global $wpdb;
$qry = "SELECT * FROM " . $wpdb->prefix . "wpam_track_links order by link_id";
$rows = $wpdb->get_results( $qry );
?>
<?php if (defined('wpam_message')) { ?>
    <div id="setting-error-settings_updated" class="notice notice-success settings-error is-dismissible"> 
        <p><strong><?php echo wpam_message; ?></strong></p>
    </div>
<?php } ?>
<br>
<h1>Track Locations of Links</h1>
<hr>
<div id="myElem" style="display:none"></div>
<div class="comontophead">
    <h3>Click on Button to Track Locations</h3>
    <button type="button" class="button button-primary cmntpbtn"  id="track_links">Track Locations</button>
</div>
<div class="cmntablewrp">
    <table class="wp-list-table widefat cmntable" id="broken-link-table">
        <thead>
            <tr>
                <th>Affiliate Title</th>
                <th>Affiliate Link</th>
                <th>Location Title</th>
                <th>Location Post ID</th>
                <th width="200px">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($rows) { foreach($rows as $row) { ?>
                <tr>
                    <td><?php echo $row->link_title; ?></td>
                    <td><?php echo $row->link_slug; ?></td>
                    <td><?php echo $row->post_title; ?></td>
                    <td><?php echo $row->post_id; ?></td>
                    <td>
                        <a href="<?php echo get_edit_post_link($row->post_id); ?>" class="button">View</a>
                    </td>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>

<script>
jQuery(document).ready(function($) {
	$('#track_links').click(function(e) {
		e.preventDefault();
		$(this).addClass('disabled').html('Processing ...');
		
        $.ajax({
             type : "POST",
             dataType : "json",
             url : "<?php echo admin_url('admin-ajax.php'); ?>",
             data : { action: "track_links" },
             success: function(response) {
				if (response.status=='done') {
					location.reload();                    
				}else{
					$("#myElem").html('No internal linking found_else').show();
					//setTimeout(function() { location.reload(); }, 500);
				}
				$('#track_links').removeClass('disabled').html('Track Locations');
            },
			error:function(error) {
                var url = "<?php echo admin_url('admin-ajax.php'); ?>";
                console.log(url,'url');
				$("#myElem").html('No internal linking found_error').show();
			}
        });
	});
});
</script>