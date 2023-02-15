<?php
$result = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."wpam_clicks order by created_at desc");
?>
<hr>
<div class="wrap click-dashboard">
	<h1 class="wp-heading-inline">Analytic Details</h1>
	<table class="widefat post fixed imprsiontable mt-3" id="mytable" cellspacing="0">
		<thead>
			<tr>
			  	<th class="manage-column" width="13%">Timestamp</th>
				<th class="manage-column" width="12%">IP Address</th>
				<th class="manage-column" width="15%">Shortlink</th>
				<th class="manage-column" width="15%">Link Name</th>
				<!-- <th class="manage-column" width="10%">OS</th>
				<th class="manage-column" width="10%">Browser</th> -->
				<th class="manage-column" width="35%">Page Viewed</th>
				<th class="manage-column" width="12%">View Source</th>
			</tr>
		</thead>
        <tbody>
		<?php foreach($result as $final){ 
			$link_title = $wpdb->get_var("SELECT name FROM ".$wpdb->prefix."wpam_links WHERE link_cpt_id =".$final->link_id);
			$valueid = $wpdb->get_var("SELECT slug FROM ".$wpdb->prefix."wpam_links WHERE link_cpt_id =".$final->link_id);

		?>
			<tr id="record_10" class="">
				<td><?php echo $final->created_at; ?></td>
				<td><?php echo $final->ip; ?></td>
				<td><?php echo "/".$valueid."/"; ?></td>
				<td><?php echo $link_title;?></td>
				<!-- <td><?php //echo $final->os; ?></td>
				<td><?php //echo $final->browser; ?></td> -->
				<td><a href="<?php echo get_edit_post_link($final->link_id); ?>"><?php echo get_edit_post_link($final->link_id); ?></a></td>
				<td align="center"><a class="sharelink" href="<?php echo get_edit_post_link($final->link_id); ?>" style="display:inline-block;" target="_blank"><img src="<?php echo AME()->plugin_url() . "assets/images/external.png"; ?>" width="26px"></a></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	<button>Export to CSV file</button>
</div>
<script>
function download_csv(csv, filename) {
    var csvFile;
    var downloadLink;

    // CSV FILE
    csvFile = new Blob([csv], {type: "text/csv"});

    // Download link
    downloadLink = document.createElement("a");

    // File name
    downloadLink.download = filename;

    // We have to create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);

    // Make sure that the link is not displayed
    downloadLink.style.display = "none";

    // Add the link to your DOM
    document.body.appendChild(downloadLink);

    // Lanzamos
    downloadLink.click();
}

function export_table_to_csv(html, filename) {
	var csv = [];
	var rows = document.querySelectorAll("table tr");
    for (var i = 0; i < rows.length; i++) {
		var row = [], cols = rows[i].querySelectorAll("td, th");
        for (var j = 0; j < cols.length; j++) 
            row.push(cols[j].innerText);
		csv.push(row.join(","));		
	}
    // Download CSV
    download_csv(csv.join("\n"), filename);
}
jQuery("button").on("click",function(){
	var html = document.querySelector("table").outerHTML;
	export_table_to_csv(html, "table.csv");
})

</script>

<script src="<?php echo wpam_PLUGIN_DIR_URL;?>assets/js/datatables.min.js"></script>
<link rel="stylesheet" href="<?php echo wpam_PLUGIN_DIR_URL;?>assets/css/datatables.min.css">
<script>
	jQuery('#mytable').DataTable({
		"order": [[ 0, "desc" ]]
	});
</script>
<style>
.ui-widget-header {
    border: 1px solid var(--tutor-primary-color);
    background: var(--tutor-primary-color);
}
.dataTables_wrapper .dataTables_filter input, .dataTables_wrapper select {
    color: #333;
}
div#chartContainer:before {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    background: #fff;
    z-index: 9999;
    width: 57px;
    height: 12px;
}
.canvasjs-chart-credit{display:none;}
</style>
