<?php
global $wpdb;
$result = $wpdb->get_results("SELECT count(*) as total, link_id, DATE(created_at) as date FROM ".$wpdb->prefix."wpam_clicks GROUP BY DATE(created_at), link_id order by date desc");
$total = 0;
$dataPoints = [];
foreach ($result as $row) {
	$newDate = strtotime($row->date) * 1000;

	if (array_key_exists($row->link_id, $dataPoints)) {
		$dataPoints[$row->link_id]['dataPoints'][] = ["x"=> $newDate, "y"=> $row->total ];
	} else {
		$dataPoints[$row->link_id] = [
			'type'			=> 'stackedColumn', 
			'name'			=> get_the_title($row->link_id),
			'xValueType'	=> "dateTime",
			'dataPoints'	=> [ ["x"=> $newDate, "y"=> $row->total ] ]
		];
	}
}
$dataPoints = json_encode(array_values($dataPoints), JSON_NUMERIC_CHECK);
?>

<div id="chartContainer" style="margin-bottom:50px;height: 370px; width: 100%;position: relative;"></div>

<script src="<?php echo wpam_PLUGIN_DIR_URL;?>/assets/js/jquery.canvasjs.min.js"></script>
<script>
window.onload = function () {
	var chart = new CanvasJS.Chart("chartContainer", {
		animationEnabled: true,
		theme: "light2",
		title:{
			text: "Affiliate Link Analytics Report"
		},
		toolTip: {
			shared: true
		},
		data: <?php echo $dataPoints; ?>
	});
	chart.render();

}
</script>