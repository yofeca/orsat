<?php
	//echo "<pre>";
	//print_r($site_info);
	//echo "</pre>";
	extract($site_info);
?>

<div class="page-header"><h1><?php echo $instrument_name. ' - '
	.$formal_name; ?> <small><?php echo $address. ', ' .$city. ' ' .$zip; ?></small></h1></div>

<div class="row">
	<div class="container-fluid">
		<div class="panel panel-default">
			<div class="panel-body">
			<div class="col-sm-6">
				<div class="panel panel-default">
					<div class="panel-heading">Site QA/QC</div>
					<div class="panel-body qaqc" style="height: 259px">
						<?php
							if($qaqc){
								?>
								<ul>
									<li><div class="col-sm-5">Validator: </div><div class="col-sm-7"><?php echo $qaqc['validator']; ?></div></li>
									<li><div class="col-sm-5">Operator: </div><div class="col-sm-7"><?php echo $qaqc['operator']; ?></div></li>
									<li><div class="col-sm-5">Data Validated Thru: </div><div class="col-sm-7"><?php echo $qaqc['data_validated_thru']; ?></div></li>
									<li><div class="col-sm-5">Channel A RF: </div><div class="col-sm-7"><?php echo $qaqc['channel_a_rf']; ?></div></li>
									<li><div class="col-sm-5">Channel B RF: </div><div class="col-sm-7"><?php echo $qaqc['channel_b_rf']; ?></div></li>
									<li><div class="col-sm-5">Last Calibration Date: </div><div class="col-sm-7"><?php echo $qaqc['last_calibration_date']; ?></div></li>
									<li><div class="col-sm-5">Last Calibration By: </div><div class="col-sm-7"><?php echo $qaqc['last_calibration_by']; ?></div></li>
								</ul>
								<a href="<?php echo site_url(); ?>qaqc/edit/<?php echo $qaqc['id']; ?>" class="pull-right btn btn-default" style="margin-top: 10px;">Edit</a>
								<?php
							}else{
								?>
								<p>No QA/QC Found</p>
								<a href="<?php echo site_url(); ?>qaqc/add?sid=<?php echo $_GET['sid'];?>">Click here to add</a>
								<?php
							}
						?>
					</div>
				</div><!--/.panel-->
			</div>
			<div class="col-sm-6">
				<div class="panel panel-default">
					<div class="panel-body map">
						<div id="map-canvas" style="height: 270px"></div>
					</div>
				</div><!--/.panel-->
			</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="container-fluid">
		<ul class="nav nav-tabs" role="tablist">
			<li class="active"><a href="#site-data" role="tab" data-toggle="tab">Latest TXO Uploaded</a></li>
			<li><a href="#target-components" role="tab" data-toggle="tab">Target Components</a></li>
			<li><a href="#lcs-information" role="tab" data-toggle="tab"><span class="hidden-xs">LCS Information</a></li>
			<li><a href="#cvs-information" role="tab" data-toggle="tab"><span class="hidden-xs">CVS Information</a></li>
		</ul>

		<div class="tab-content">
			<div class="panel panel-default tab-pane tabs-up active" id="site-data">
				<div class="panel-body">
					<?php echo $site_txo_data; ?>
				</div>
			</div>
			<div class="panel panel-default tab-pane tabs-up" id="target-components">
				<div class="panel-body">
					<div class="col-sm-6">
					<h4>Channel A</h4>
						<ul>
						<?php
							$t = count($target_components_a);

							for($i=0; $i<$t; $i++){
								echo '<li>'.$target_components_a[$i]['aqi_no'].' '.$target_components_a[$i]['component_name'].' - '.$target_components_a[$i]['carbon_no'].'</li>';
							}
						?>
						</ul>
					</div>
					<div class="col-sm-6">
					<h4>Channel B</h4>
						<ul>
						<?php
							$t = count($target_components_b);

							for($i=0; $i<$t; $i++){
								echo '<li>'.$target_components_b[$i]['aqi_no'].' '.$target_components_b[$i]['component_name'].' - '.$target_components_b[$i]['carbon_no'].'</li>';
							}
						?>
						</ul>
					</div>
				</div>
			</div>
			<div class="panel panel-default tab-pane tabs-up" id="lcs-information">
				<div class="panel-body">
					<?php $lcs_total = count($lcs); ?>
					<table class="table">
						<thead>
							<tr>
								<th>CYLINDER</th>
								<th>DATE ON</th>
								<th>DATE OFF</th>
								<th>DILUTION FACTOR</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							for($i=0; $i<$lcs_total; $i++){
								?>
							<tr id="lcs-<?php echo $lcs[$i]['id']; ?>">
								<td><a href="#"><?php echo $lcs[$i]['cylinder']; ?></a></td>
								<td><?php echo $lcs[$i]['date_on']; ?></td>
								<td><?php echo ($lcs[$i]['date_off'] != NULL) ? $lcs[$i]['date_off'] : ''; ?></td>
								<td><?php echo $lcs[$i]['value']; ?></td>
							</tr>
								<?php
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="panel panel-default tab-pane tabs-up" id="cvs-information">
				<div class="panel-body">
					<?php $cvs_total = count($cvs); ?>
					<table class="table">
						<thead>
							<tr>
								<th>CYLINDER</th>
								<th>DATE ON</th>
								<th>DATE OFF</th>
								<th>BLEND RATIO</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							for($i=0; $i<$cvs_total; $i++){
								?>
							<tr id="cvs-<?php echo $cvs[$i]['id']; ?>">
								<td><a href="#"><?php echo $cvs[$i]['cylinder']; ?></a></td>
								<td><?php echo $cvs[$i]['date_on']; ?></td>
								<td><?php echo ($cvs[$i]['date_off'] != NULL) ? $cvs[$i]['date_off'] : ''; ?></td>
								<td><?php echo $cvs[$i]['value']; ?></td>
							</tr>
								<?php
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
	//var map;
	function initialize() {
		var myLatLng = new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude;?>);
		var mapOptions = {
			zoom: 10,
			center: myLatLng
		};
		var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

		var marker = new google.maps.Marker({
			position: myLatLng,
			map: map,
			title: 'test'
		});
		
		google.maps.event.addDomListener(window, "resize", function() {
			var center = map.getCenter();
			google.maps.event.trigger(map, "resize");
			map.setCenter(center);
		});
	}
	google.maps.event.addDomListener(window, 'load', initialize);
</script>