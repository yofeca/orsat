<script>
$(document).ready(function(){
	var view =  "<?php echo $_GET['v'] ?>";

	if(!$(this).prop('checked')){
		$('#myChart-a').fadeOut(200);
		$('#myChart-b').fadeOut(200);
	}
	$('input[name="toggle-chart"]').on('click',function(){
		if($(this).prop('checked')){
			$('#myChart-a').fadeIn(200);
			$('#myChart-b').fadeIn(200);
		}else{
			$('#myChart-a').fadeOut(200);
			$('#myChart-b').fadeOut(200);
		}
	});

	$('select[name="decimal-place"]').change(function(){
		
		var d = $(this).val();

		$('.value').each(function(){
			var da = parseFloat($(this).data("value"));
			var old_val = $(this).data("value");
			if(d!=''){
				if(!isNaN(da)){
					$(this).html(da.toFixed(d));
				}else{
					$(this).html(old_val);
				}
			}else{
				$(this).html(da);
			}
		});
	});
	$('select[name="view"]').change(function(){
		
		var view = $(this).val().toLowerCase();
		self.location = "<?php echo site_url(); echo $controller ?>?dd=<?php echo $_GET['dd']; ?>&sid=<?php echo $_GET['sid']; ?>&v="+view;
	});
	
	var arr_labels_a = new Array();
	$('.th-labels-a').each(function(){
		arr_labels_a.push ($(this).attr('id'));
	});
	
	var arr_row_a = new Array();
	$('.row-channel-a').each(function(){
		var data = new Array();

		$('.value',this).each(function(){
			var val = parseFloat($(this).data('value'));
			if(isNaN(val)) val = 0

			data.push(val);
		});
		arr_row_a.push(data);
	});
	console.log(arr_row_a);

	ctr = arr_row_a.length;
	var str = new Array();
	for(var i=0; i<ctr; i++){
		str.push({label: "My First dataset",
	            fillColor: "rgba("+i*2+","+(i*4)+","+(i*6)+",.2)",
	            strokeColor: "rgba(220,220,220,1)",
	            pointColor: "rgba(220,220,220,1)",
	            pointStrokeColor: "#fff",
	            pointHighlightFill: "#fff",
	            pointHighlightStroke: "rgba(220,220,220,1)",
	            data: arr_row_a[i]});
	}console.log(str);
	// Get the context of the canvas element we want to select
	var ctx = document.getElementById("myChart-a").getContext("2d");
	//var myNewChart = new Chart(ctx).PolarArea(data);
		var data_a = {
	    labels: arr_labels_a,
	    datasets: str
	};
	var options;
	if(view=='amount'){
		options_a={scaleOverride: true,
					scaleSteps: 10,
					scaleStepWidth: 30,
					scaleStartValue: 0,
					bezierCurve : false,
					bezierCurveTension : 0}; 
	}else if(view=='time'){
		options_a={scaleOverride: true,
					scaleSteps: 5,
					scaleStepWidth: 50,
					scaleStartValue: 0}; 		
	}else if(view=='area'){
		options_a={scaleOverride: true,
					scaleSteps: 100,
					scaleStepWidth: 1000,
					scaleStartValue: 0}; 
	}
	var myLineChart = new Chart(ctx).Line(data_a,options_a);

	//Channel b
	var arr_labels_b = new Array();
	$('.th-labels-b').each(function(){
		arr_labels_b.push ($(this).attr('id'));
	});
	
	var arr_row_b = new Array();
	$('.row-channel-b').each(function(){
		var data = new Array();

		$('.value',this).each(function(){
			var val = parseFloat($(this).data('value'));
			if(isNaN(val)) val = 0

			data.push(val);
		});
		arr_row_b.push(data);
	});

	ctr = arr_row_b.length;
	var str = new Array();
	for(var i=0; i<ctr; i++){
		str.push({label: "My First dataset",
	            fillColor: "rgba("+i*2+","+(i*4)+","+(i*6)+",.2)",
	            strokeColor: "rgba(220,220,220,1)",
	            pointColor: "rgba(220,220,220,1)",
	            pointStrokeColor: "#fff",
	            pointHighlightFill: "#fff",
	            pointHighlightStroke: "rgba(220,220,220,1)",
	            data: arr_row_b[i]});
	}
	// Get the context of the canvas element we want to select
	var ctx = document.getElementById("myChart-b").getContext("2d");
	//var myNewChart = new Chart(ctx).PolarArea(data);
		var data_b = {
	    labels: arr_labels_b,
	    datasets: str
	};
	var options;
	if(view=='amount'){
		options_b={scaleOverride: true,
					scaleSteps: 10,
					scaleStepWidth: 30,
					scaleStartValue: 0}; 
	}else if(view=='time'){
		options_b={scaleOverride: true,
					scaleSteps: 5,
					scaleStepWidth: 50,
					scaleStartValue: 0}; 		
	}else if(view=='area'){
		options_b={scaleOverride: true,
					scaleSteps: 100,
					scaleStepWidth: 1000,
					scaleStartValue: 0}; 
	}
	var myLineChart = new Chart(ctx).Line(data_b,options_b);

	$('.th-labels-a').on('click',function(){
		id = $(this).attr('id');

		$('#chart-modal').modal('show');
	});
});
</script>

<style>
	#quicklooka td, #quicklookb td{ padding: 2px 4px !important;}
	.cvs{ background: #D4D4FF; }
	.lcs{ background: #BBE5E5; }
	.blank{ background: #E1E1E1; }
	.rts{ background: #00FFDD; }
	.text-center{ text-align: center; }
	.table{ white-space: nowrap; }
	.table td{ font-size: 12px; text-align: center; }
	.table td:first-child, .table th:first-child{ text-align: left !important; }
	.table th{ font-weight: bold; text-align: center; }
	.standard{ color: #ff0000; }
	.lcs-report { white-space: normal !important; }
	.fail{ color: #ff0000 !important; }
	.rts-summary{ background: gray; color: #fff;}
</style>

<?php
	/**
	 * Common variables
	*/
	$extra_char = array(" ","-",","); 
	$tha = count($headera);
	$thb = count($headerb);
	$tt = count($txo);

	$stdev = $rts_summary['stdev'];
	$average = $rts_summary['average'];
	$min = $rts_summary['min'];
	$max = $rts_summary['max'];

	$stdev_count = count($stdev);
	$average_count = count($average);
	$min_count = count($min);
	$max_count = count($max);

	$tma = count($modea);
	$tmb = count($modeb);
?>
<div class="panel panel-default">
<div class="panel-heading"><?php echo $site_info['instrument_name'] . ' - ' . $site_info['short_name']; ?> Daily Quick Look <a href="<?php echo site_url('site_info') ?>?sid=<?php echo $site_info['id'] ?>" style="font-weight: normal; font-size: 12px">Back to Site Info.</a></div>
<div class="panel-body">
<!-- Panel wrapper -->
	<div class="container-fluid">
		<div id="filters" class="pull-right col-sm-7">
			<div class="col-sm-3">
					<input type="checkbox" name="toggle-chart"/> Show Charts:
			</div>
			<div class="col-sm-5">
				<label class="col-sm-6 control-label">Decimal Place: </label>
				<div class="col-sm-6">
					<select name="decimal-place" class="">
						<option></option>
						<option>0</option>
						<option>1</option>
						<option>2</option>
						<option>3</option>
					</select>
				</div>
			</div>

			<div class="col-sm-4">
				<label class="col-sm-6 control-label">View: </label>
				<div class="col-sm-6">
					<select name="view" class="">
						<option></option>
						<option>Amount</option>
						<option>Area</option>
						<option>Time</option>
					</select>
				</div>
			</div>
		</div>
	</div>

<div class="panel panel-default">
	<div class="panel-heading">Channel A</div>
	<div class="panel-body table-responsive">
	<!-- Channel A Compounds -->

			<div class="container-fluid">
				<canvas id="myChart-a" width="1200px" height="400"></canvas>
			</div>
			<?php 
				if($headera){ 
			?>
			<table class="table table-bordered" id="quicklooka">
				<thead>
					<tr style="font-size: 9px;">
						<th class="text-center">File</th>
						<th class="text-center">Date</th>
						<th class="text-center">Time</th>
					<?php
						
						for($i=0; $i<$tha; $i++){
							?>
								<th class="text-center th-labels-a" id="<?php echo strtolower($headera[$i]['component_name']); ?>"><?php echo ($headera[$i]['alias']) ? $headera[$i]['alias'] : $headera[$i]['component_name']; ?></th>
							<?php
						}
					?>
					</tr>
				</thead>
				
				<tbody>
					<?php 
						for($i=0; $i<$tt; $i++){
							if($txo[$i]['channel']=='A'){
								$filename = str_replace('.TX0', '', $txo[$i]['filename']);

								$lfn = stripos(substr($filename,-5,1), 'e'); //check for lcs standard
								$cfn = stripos(substr($filename,-5,1), 'c'); //check for cvs standard
								$bfn = stripos(substr($filename,-5,1), 'b'); //check for blank standard
								$rfn = stripos(substr($filename,-5,1), 'q'); //check for rts standard

								if($lfn>-1)
									$standard = 'lcs';
								else if($cfn>-1)
									$standard = 'cvs';
								else if($bfn>-1)
									$standard = 'blank';
								else if($rfn>-1)
									$standard = 'rts';
								else
									$standard = '';

								echo '<tr class="'.$standard.' row-channel-a">';
								echo '<td><a href="' .site_url().'txo_dumps/edit/'.$filename.'.TX0'.'">'.$filename.'</a></td>';
								echo '<td>'.date('d-M',strtotime($txo[$i]['data_acquisition_time'])).'</td>';
								echo '<td>'.date('H:i',strtotime($txo[$i]['data_acquisition_time'])).'</td>';
								
								for($j=0; $j<$tha; $j++){
									$match = false;
									
									$tc = count($txo[$i]['components']);
									for($k=0; $k<$tc; $k++){
										$header = substr(str_replace($extra_char, "", $headera[$j]['component_name']),0,10);
										$component = substr(str_replace($extra_char, "", $txo[$i]['components'][$k]['component_name']),0,10);
										$value = $txo[$i]['components'][$k]['time'];

										if($header==$component){
											$match = true;
											echo '<td class="'.strtolower($headera[$j]['component_name']).' value text-center" data-value="'.$value.'">'. $value .'</td>';
											break;
										}
									}
									if(!$match){
										echo '<td class="'.strtolower($headera[$j]['component_name']).' value text-center" data-value="n/a">n/a</td>';
									}
								}
							}//end of channel A check
							echo '</tr>';
						}//end of txo loop
					?>

					<tr class="rts-summary">
						<td colspan="3">STDEV</td>
						<?php
							for($i = 0; $i<$tha; $i++){
								$match = false;
								for($j=0; $j<$stdev_count; $j++){
									if($stdev[$j]['channel']=='A'){
										$header = substr(str_replace($extra_char, "", $headera[$i]['component_name']),0,10);
										$component = substr(str_replace($extra_char, "", $stdev[$j]['component_name']),0,10);
										$value = $stdev[$j]['value'];

										if($header==$component){
											$match = true;
											echo '<td class="text-center" data-value="'.$value.'">'. round($value, 2) .'</td>';
											break;
										}
									}
								}
								if(!$match){
									echo '<td class="value text-center" data-value="n/a">n/a</td>';
								}
							}
						?>
					</tr>
					<tr class="rts-summary">
						<td colspan="3">MODE</td>
						<?php
							for($i = 0; $i<$tha; $i++){
								$match = false;
								for($j=0; $j<$tma; $j++){
										$header = substr(str_replace($extra_char, "", $headera[$i]['component_name']),0,10);
										$component = substr(str_replace($extra_char, "", $modea[$j]['component_name']),0,10);
										$value = $modea[$j]['time'];
										if($header==$component){
											$match = true;
											echo '<td class="text-center" data-value="'.$value.'">'. round($value, 2) .'</td>';
											break;
										}
									
								}
								if(!$match){
									echo '<td class="value text-center" data-value="n/a">n/a</td>';
								}
							}
						?>
					</tr>
					<tr class="rts-summary">
						<td colspan="3">AVERAGE</td>
						<?php
							for($i = 0; $i<$tha; $i++){
								$match = false;
								for($j=0; $j<$average_count; $j++){
									if($average[$j]['channel']=='A'){
										$header = substr(str_replace($extra_char, "", $headera[$i]['component_name']),0,10);
										$component = substr(str_replace($extra_char, "", $average[$j]['component_name']),0,10);
										$value = $average[$j]['value'];

										if($header==$component){
											$match = true;
											echo '<td class="text-center" data-value="'.$value.'">'. round($value, 2) .'</td>';
											break;
										}
									}
								}
								if(!$match){
									echo '<td class="value text-center" data-value="n/a">n/a</td>';
								}
							}
						?>
					</tr>
					<tr class="rts-summary">
						<td colspan="3">MINIMUM</td>
						<?php
							for($i = 0; $i<$tha; $i++){
								$match = false;
								for($j=0; $j<$min_count; $j++){
									if($min[$j]['channel']=='A'){
										$header = substr(str_replace($extra_char, "", $headera[$i]['component_name']),0,10);
										$component = substr(str_replace($extra_char, "", $min[$j]['component_name']),0,10);
										$value = $min[$j]['value'];

										if($header==$component){
											$match = true;
											echo '<td class="text-center" data-value="'.$value.'">'. round($value, 2) .'</td>';
											break;
										}
									}
								}
								if(!$match){
									echo '<td class="value text-center" data-value="n/a">n/a</td>';
								}
							}
						?>
					</tr>
					<tr class="rts-summary">
						<td colspan="3">MAXIMUM</td>
						<?php
							for($i = 0; $i<$tha; $i++){
								$match = false;
								for($j=0; $j<$max_count; $j++){
									if($max[$j]['channel']=='A'){
										$header = substr(str_replace($extra_char, "", $headera[$i]['component_name']),0,10);
										$component = substr(str_replace($extra_char, "", $max[$j]['component_name']),0,10);
										$value = $max[$j]['value'];

										if($header==$component){
											$match = true;
											echo '<td class="text-center" data-value="'.$value.'">'. round($value, 2) .'</td>';
											break;
										}
									}
								}
								if(!$match){
									echo '<td class="value text-center" data-value="n/a">n/a</td>';
								}
							}
						?>
					</tr>
				</tbody>
			</table>
			<?php } ?>

	<!-- End of Channel A compounds -->
	</div>
</div><!--Channel A-->

<div class="panel panel-default" style="margin-top: 15px; ">
	<div class="panel-heading">Channel B</div>
	<div class="panel-body table-responsive">
	<!-- Channel B Compounds -->

			<div class="container-fluid">
				<canvas id="myChart-b" width="1200px" height="400"></canvas>
			</div>
			<?php 
				if($headerb){ 
			?>
			<table class="table table-bordered" id="quicklookb">
				<thead>
					<tr style="font-size: 9px;">
						<th class="text-center">File</th>
						<th class="text-center">Date</th>
						<th class="text-center">Time</th>
					<?php
						
						for($i=0; $i<$thb; $i++){
							?>
								<th class="text-center th-labels-b" id="<?php echo strtolower($headerb[$i]['component_name']); ?>"><?php echo ($headerb[$i]['alias']) ? $headerb[$i]['alias'] : $headerb[$i]['component_name']; ?></th>
							<?php
						}
					?>
					</tr>
				</thead>
				
				<tbody>
					<?php 
						for($i=0; $i<$tt; $i++){
							if($txo[$i]['channel']=='B'){
								$filename = str_replace('.TX0', '', $txo[$i]['filename']);

								$lfn = stripos(substr($filename,-5,1), 'e'); //check for lcs standard
								$cfn = stripos(substr($filename,-5,1), 'c'); //check for cvs standard
								$bfn = stripos(substr($filename,-5,1), 'b'); //check for blank standard
								$rfn = stripos(substr($filename,-5,1), 'q'); //check for rts standard

								if($lfn>-1)
									$standard = 'lcs';
								else if($cfn>-1)
									$standard = 'cvs';
								else if($bfn>-1)
									$standard = 'blank';
								else if($rfn>-1)
									$standard = 'rts';
								else
									$standard = '';

								echo '<tr class="'.$standard.' row-channel-b">';
								echo '<td><a href="' .site_url().'txo_dumps/edit/'.$filename.'.TX0'.'">'.$filename.'</a></td>';
								echo '<td>'.date('d-M',strtotime($txo[$i]['data_acquisition_time'])).'</td>';
								echo '<td>'.date('H:i',strtotime($txo[$i]['data_acquisition_time'])).'</td>';
								
								for($j=0; $j<$thb; $j++){
									$match = false;
									
									$tc = count($txo[$i]['components']);
									for($k=0; $k<$tc; $k++){
										$header = substr(str_replace($extra_char, "", $headerb[$j]['component_name']),0,10);
										$component = substr(str_replace($extra_char, "", $txo[$i]['components'][$k]['component_name']),0,10);
										$value = $txo[$i]['components'][$k]['time'];

										if($header==$component){
											$match = true;
											echo '<td class="'.strtolower($headerb[$j]['component_name']).' value text-center" data-value="'.$value.'">'. $value .'</td>';
											break;
										}
									}
									if(!$match){
										echo '<td class="'.strtolower($headerb[$j]['component_name']).' value text-center" data-value="n/a">n/a</td>';
									}
								}
							}//end of channel A check
							echo '</tr>';
						}//end of txo loop
					?>

					<tr class="rts-summary">
						<td colspan="3">STDEV</td>
						<?php
							for($i = 0; $i<$thb; $i++){
								$match = false;
								for($j=0; $j<$stdev_count; $j++){
									if($stdev[$j]['channel']=='B'){
										$header = substr(str_replace($extra_char, "", $headerb[$i]['component_name']),0,10);
										$component = substr(str_replace($extra_char, "", $stdev[$j]['component_name']),0,10);
										$value = $stdev[$j]['value'];

										if($header==$component){
											$match = true;
											echo '<td class="text-center" data-value="'.$value.'">'. round($value, 2) .'</td>';
											break;
										}
									}
								}
								if(!$match){
									echo '<td class="value text-center" data-value="n/a">n/a</td>';
								}
							}
						?>
					</tr>
					<tr class="rts-summary">
						<td colspan="3">MODE</td>
						<?php
							for($i = 0; $i<$thb; $i++){
								$match = false;
								for($j=0; $j<$tmb; $j++){
										$header = substr(str_replace($extra_char, "", $headerb[$i]['component_name']),0,10);
										$component = substr(str_replace($extra_char, "", $modeb[$j]['component_name']),0,10);
										$value = $modeb[$j]['time'];
										if($header==$component){
											$match = true;
											echo '<td class="text-center" data-value="'.$value.'">'. round($value, 2) .'</td>';
											break;
										}
									
								}
								if(!$match){
									echo '<td class="value text-center" data-value="n/a">n/a</td>';
								}
							}
						?>
					</tr>
					<tr class="rts-summary">
						<td colspan="3">AVERAGE</td>
						<?php
							for($i = 0; $i<$thb; $i++){
								$match = false;
								for($j=0; $j<$average_count; $j++){
									if($average[$j]['channel']=='B'){
										$header = substr(str_replace($extra_char, "", $headerb[$i]['component_name']),0,10);
										$component = substr(str_replace($extra_char, "", $average[$j]['component_name']),0,10);
										$value = $average[$j]['value'];

										if($header==$component){
											$match = true;
											echo '<td class="text-center" data-value="'.$value.'">'. round($value, 2) .'</td>';
											break;
										}
									}
								}
								if(!$match){
									echo '<td class="value text-center" data-value="n/a">n/a</td>';
								}
							}
						?>
					</tr>
					<tr class="rts-summary">
						<td colspan="3">MINIMUM</td>
						<?php
							for($i = 0; $i<$thb; $i++){
								$match = false;
								for($j=0; $j<$min_count; $j++){
									if($min[$j]['channel']=='B'){
										$header = substr(str_replace($extra_char, "", $headerb[$i]['component_name']),0,10);
										$component = substr(str_replace($extra_char, "", $min[$j]['component_name']),0,10);
										$value = $min[$j]['value'];

										if($header==$component){
											$match = true;
											echo '<td class="text-center" data-value="'.$value.'">'. round($value, 2) .'</td>';
											break;
										}
									}
								}
								if(!$match){
									echo '<td class="value text-center" data-value="n/a">n/a</td>';
								}
							}
						?>
					</tr>
					<tr class="rts-summary">
						<td colspan="3">MAXIMUM</td>
						<?php
							for($i = 0; $i<$thb; $i++){
								$match = false;
								for($j=0; $j<$max_count; $j++){
									if($max[$j]['channel']=='B'){
										$header = substr(str_replace($extra_char, "", $headerb[$i]['component_name']),0,10);
										$component = substr(str_replace($extra_char, "", $max[$j]['component_name']),0,10);
										$value = $max[$j]['value'];

										if($header==$component){
											$match = true;
											echo '<td class="text-center" data-value="'.$value.'">'. round($value, 2) .'</td>';
											break;
										}
									}
								}
								if(!$match){
									echo '<td class="value text-center" data-value="n/a">n/a</td>';
								}
							}
						?>
					</tr>
				</tbody>
			</table>
			<?php } ?>

	<!-- End of Channel A compounds -->
	</div>
</div><!--Channel B-->


<!-- End of panel wrapper -->
</div>
</div>


	</div>
</div>

<div class="modal fade">
  <div class="modal-dialog" id="chart-modal">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Chart</h4>
      </div>
      <div class="modal-body">
        <p>One fine body&hellip;</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->