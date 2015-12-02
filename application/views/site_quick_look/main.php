<script>
$(window).load(function(){
	generate_txodata_chart_summary('#quicklooka', '.th-labels', '#myChart', 'a', 'line');
	generate_txodata_chart_summary('#quicklookb', '.th-labels', '#myChart', 'b', 'line');
});

$(document).ready(function(){

	var currentCharta;
	var currentChartb;

	var view =  "<?php echo $_GET['v'] ?>";

	$('select[name="decimal-place"]').change(function(){
		
		var d = $(this).val();

		$('.value').each(function(){
			var da = parseFloat($(this).data("value"));
			var old_val = $(this).data("value");
			if(d!=''){
				if(!isNaN(da)){
					$(this).html(da.toFixed(d));
					//clean_value($(this));
				}else{
					$(this).html(old_val);
					//clean_value($(this));
				}
			}else{
				$(this).html(old_val);
			}
		});

		mask_values('.value','td-zero','');

	});

	$('.quicklook').doubleScroll();

	$('select[name="view"]').change(function(){
		var view = $(this).val().toLowerCase();
		self.location = "<?php echo site_url(); echo $controller ?>?dd=<?php echo $_GET['dd']; ?>&sid=<?php echo $_GET['sid']; ?>&v="+view;
	});
	
	/*$('.value').each(function(){
		$(this).html(parseFloat($(this).data("value")));
		clean_value($(this));
	});*/

	function clean_value(v){
		if(v.html() != 'N/A'){
			if(parseFloat(v.data("value")) < 0.4){
				v.html('ND');
				console.log(v.html());
			}
		}
	}
	
	$('#bt-next').on('click', function(){
		var nxturl = "<?php echo site_url(); ?>site_quick_look?dd=<?php echo date('Y-m-d', strtotime('+1 day',strtotime($_GET['dd']))); ?>&sid=<?php echo $_GET['sid']; ?>&v=<?php echo $_GET['v']; ?>";;
		self.location = nxturl;
	});
	$('#bt-previous').on('click', function(){
		var nxturl = "<?php echo site_url(); ?>site_quick_look?dd=<?php echo date('Y-m-d', strtotime('-1 day',strtotime($_GET['dd']))); ?>&sid=<?php echo $_GET['sid']; ?>&v=<?php echo $_GET['v']; ?>";;
		self.location = nxturl;
	});

	$('.bt-charta').click(function(){
		currentCharta = $(this);
		generate_txodata_chart($(this), '.th-labels', '#myChart','line');
	});
	$('.bt-chartb').click(function(){
		currentChartb = $(this);
		generate_txodata_chart($(this), '.th-labels', '#myChart','line');
	});
	$('#bt-column-chart-a').click(function(){
		if(currentCharta){
			generate_txodata_chart(currentCharta, '.th-labels', '#myChart','column');
		}else{
			generate_txodata_chart_summary('#quicklooka', '.th-labels', '#myChart', 'a', 'column');
		}
	});
	$('#bt-line-chart-a').click(function(){
		if(currentCharta){
			generate_txodata_chart(currentCharta, '.th-labels', '#myChart','line');
		}else{
			generate_txodata_chart_summary('#quicklooka', '.th-labels', '#myChart', 'a', 'line');
		}
	});
	$('#bt-column-chart-b').click(function(){
		if(currentChartb){
			generate_txodata_chart(currentChartb, '.th-labels', '#myChart','column');
		}else{
			generate_txodata_chart_summary('#quicklookb', '.th-labels', '#myChart', 'b', 'column');
		}
	});
	$('#bt-line-chart-b').click(function(){
		if(currentChartb){
			generate_txodata_chart(currentChartb, '.th-labels', '#myChart','line');
		}else{
			generate_txodata_chart_summary('#quicklookb', '.th-labels', '#myChart', 'b', 'line');
		}
	});

	$('.bt-previous-chart').click(function(){
		ch = $(this).attr('id').replace('bt-previous-chart-','');
		if(ch=='a'){
			var currentChartId = currentCharta.attr('id').replace('bt-','');
			var currentObj = $('#'+currentChartId).prev().attr('id');

			if(currentObj === undefined || currentObj === null){
				var previousChart = $('#bt-'+currentChartId);
			}else{
				var previousChart = $('#bt-'+currentObj);
			}
			currentCharta = previousChart;
			generate_txodata_chart(previousChart, '.th-labels', '#myChart','line');
		}else{
			var currentChartId = currentChartb.attr('id').replace('bt-','');
			var currentObj = $('#'+currentChartId).prev().attr('id');

			if(currentObj === undefined || currentObj === null){
				var previousChart = $('#bt-'+currentChartId);
			}else{
				var previousChart = $('#bt-'+currentObj);
			}
			currentChartb = previousChart;
			generate_txodata_chart(previousChart, '.th-labels', '#myChart','line');
		}
	});
	$('.bt-next-chart').click(function(){
		ch = $(this).attr('id').replace('bt-next-chart-','');
		if(ch=='a'){
			var currentChartId = currentCharta.attr('id').replace('bt-','');
			var currentObj = $('#'+currentChartId).next().attr('id');

			if(currentObj === undefined || currentObj === null){
				var nextChart = $('#bt-'+currentChartId);
			}else{
				var nextChart = $('#bt-'+currentObj);
			}
			currentCharta = nextChart;
			generate_txodata_chart(nextChart, '.th-labels', '#myChart','line');
		}else{
			var currentChartId = currentChartb.attr('id').replace('bt-','');
			var currentObj = $('#'+currentChartId).next().attr('id');
			console.log(currentChartId);
			console.log(currentObj);
			if(currentObj === undefined || currentObj === null){
				var nextChart = $('#bt-'+currentChartId);
			}else{
				var nextChart = $('#bt-'+currentObj);
			}
			currentChartb = nextChart;
			generate_txodata_chart(nextChart, '.th-labels', '#myChart','line');
		}
	});
	mask_values('.value','td-zero','');
});
</script>

<?php 
	$channel_a_rf = $qaqc_info['channel_a_rf'];
	$channel_b_rf = $qaqc_info['channel_b_rf'];
?>

<div class="panel panel-default">
<div class="panel-heading">
		Daily Quick Look 
		<a href="<?php echo site_url('site_info') ?>?sid=<?php echo $site_info['id'] ?>" style="font-weight: normal; font-size: 12px">
		Back to Site Info.</a>

		<div id="site-prev-next" style="float: right; margin-top: -3px;">
			<button id="bt-previous"><i class="fa fa-arrow-left"></i></button>
			<button id="bt-next"><i class="fa fa-arrow-right"></i></button>
		</div>
</div>

<div class="panel-body">

<!-- Panel wrapper -->
	<div class="container-fluid">
		<div id="filters" class="col-xs-12 col-sm-5 pull-right">
			<div class="col-xs-12 col-sm-6">
				<label class="col-xs-6 col-sm-6">Decimal: </label>
				<div class="col-xs-6 col-sm-6">
					<select name="decimal-place" class="">
						<option></option>
						<option value="0">0</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
					</select>
				</div>
			</div>

			<div class="col-xs-12 col-sm-6">
				<label class="col-xs-6 col-sm-6">View: </label>
				<div class="col-xs-6 col-sm-6">
					<select name="view">
						<option></option>
						<option value="amount">Amount</option>
						<option value="area">Area</option>
						<option value="responsefactor">Response Factor</option>
						<option value="time">Time</option>
					</select>
				</div>
			</div>
		</div>
	</div>

	<div class="container-fluid quick-look-info" style="background: #ddd; ">
		<div class="col-sm-3">
			<b><?php echo $site_info[short_name]; ?></b><br/>
			<?php echo date('Y-m-d',strtotime($txo[0]['data_acquisition_time'])); ?>
		</div>
		<div class="col-sm-3">
			<b>CONCENTRATION PPB-C</b>
		</div>
		<div class="col-sm-3">
			<b>CURRENT SEQUENCE/IDX NAME</b><br/>
			<?php echo basename($txo[0]['sequence_file']); ?></div>
		<div class="col-sm-3">
			<b>SEQUENCE (DAYS IN USE)</b><br/>
			?
		</div>
	</div>

	<div class="panel panel-default">
	<div class="panel-heading">Channel A 
		<span class="toggle-chart">
			<button class="btn bt-toggle-chart" id="bt-column-chart-a"><i class="fa fa-bar-chart"></i></button>
			<button class="btn bt-toggle-chart" id="bt-line-chart-a"><i class="fa fa-line-chart"></i></button>
			<button class="btn bt-previous-chart" id="bt-previous-chart-a"><i class="fa fa-chevron-circle-left"></i></button>
			<button class="btn bt-next-chart" id="bt-next-chart-a"><i class="fa fa-chevron-circle-right"></i></button>
		</span>
	</div>
	<div class="panel-body">
	<!-- Channel A Compounds -->

		<div class="container-fluid">
			<div id="myChart-a" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
		</div>
		
		<div class="panel-body table-responsive">
			<div class="quicklook">
			<?php if($headera){ ?>
			<table class="table table-bordered" id="quicklooka">
				<thead>
					<tr style="font-size: 9px;">
						<th></th>
						<th class="text-center">File</th>
						<!--th class="text-center">Date</th-->
						<th class="text-center">Time</th>
					<?php

						$th = count($headera);
						$st = count($standards);
						$extra_char = array(" ","-",",");

						for($i=0; $i<$th; $i++){
							$standard = '';
							for($j=0; $j<$st; $j++){
								$st_name = substr(str_replace($extra_char, "", $standards[$j]['component_name']),0,10);
								$hd_name = substr(str_replace($extra_char, "", $headera[$i]['component_name']),0,10);
								if($st_name == $hd_name){
									$standard = 'standard';
									break;
								}
							}
							?>
								<th class="text-center th-labels-a <?php echo $standard; ?>" id="<?php echo strtolower($headera[$i]['component_name']); ?>"><?php echo ($headera[$i]['alias']) ? $headera[$i]['alias'] : $headera[$i]['component_name']; ?></th>
							<?php
						}
					?>
						<th>Total<br/>Target</th>
						<?php if($view != 'responsefactor'){?>
						<th>Total</th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>

					<?php 
						$tt = count($txo);
						$extra_char = array(" ","-",",");
						$view = $_GET['v'];

						for($i=0; $i<$tt; $i++) //data loop
						{
							if($txo[$i]['channel']=='A')
							{
								$filename = str_replace('.TX0', '', $txo[$i]['filename']);

								$lfn = stripos(substr($filename,-5,1), 'e');
								$cfn = stripos(substr($filename,-5,1), 'c');
								$bfn = stripos(substr($filename,-5,1), 'b');
								$rfn = stripos(substr($filename,-5,1), 'q');

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

								echo '<tr class="'.$standard.' row-channel-a" id="'.$filename.'">';
								echo '<td><button id="bt-'.$filename.'" class="bt-charta" data-channel="a"><i class="fa fa-line-chart"></i></button></td>';
								echo '<td><a href="' .site_url().'txo_dumps/edit/'.$filename.'.TX0'.'">'.$filename.'</a></td>';
								//echo '<td>'.date('d-M',strtotime($txo[$i]['data_acquisition_time'])).'</td>';
								echo '<td>'.date('H:i',strtotime($txo[$i]['data_acquisition_time'])).'</td>';
								
								$tc = count($txo[$i]['components']);
								$tnmc = 0;
								$tnmtc = 0;

								for($j=0; $j<$th; $j++) //header loop
								{
									$match = false;
									
									for($k=0; $k<$tc; $k++) //components loop
									{
										$header = substr(str_replace($extra_char, "", $headera[$j]['component_name']),0,10);
										$component = substr(str_replace($extra_char, "", $txo[$i]['components'][$k]['component_name']),0,10);
										if($view == 'responsefactor'){
											$area = (float)$txo[$i]['components'][$k]['area'];
											$amount = (float)$txo[$i]['components'][$k]['amount'];
											if($area>0){
												$value = $area / $amount;
												$value = round($value,0, PHP_ROUND_HALF_UP);
											}else{
												$value = 0;
											}

											if($value < $channel_a_rf){
												$err_rf = 'err-rf';
											}else{
												$err_rf = '';
											}
										}else{
											$value = $txo[$i]['components'][$k][$view];
										}
										
										if($component=='METHANE'){
											$methane = $value;
										}

										if($header==$component)
										{
											$match = true;
											
											echo '<td class="'.strtolower($headera[$j]['component_name']).' '. $err_rf .' value text-center" data-value="'.$value.'">'. $value .'</td>';
											$tnmtc += (float) $value;
											break;
										}

										/*if($k==$tc-1){
											switch($view){
												case 'amount':
													$tnmc = $txo[$i]['pp_carbon'] - $methane;
													$tnmtc = $tnmtc - $methane;
													break;
												case 'time':
													$tnmc = $txo[$i]['time'] - $methane;
													$tnmtc = $tnmtc - $methane;
													break;
												case 'area':
													$tnmc = $txo[$i]['area'] - $methane;
													$tnmtc = $tnmtc - $methane;
													break;
											}
											
										}*/

									}//end of component loop

									if(!$match)
									{
										echo '<td class="'.strtolower($headera[$j]['component_name']).' value text-center" data-value="N/A">N/A</td>';
									}

								}//end of header loop
								
								switch($view){
									case 'amount':
										$tnmc = $txo[$i]['pp_carbon'];
										break;
									case 'time':
										$tnmc = $txo[$i]['time'];
										break;
									case 'area':
										$tnmc = $txo[$i]['area'];
										break;
								}

								$tnmc = ($tnmc<=0) ? 0: $tnmc;
								echo '<td class="value-" data-value="'.$tnmtc.'">'.$tnmtc.'</td>';
								if($view != 'responsefactor'){
									echo '<td class="value-" data-value="'.$tnmc.'">'.$tnmc.'</td>';
								}
								echo '</tr>';

							}//end of Channel A loop

						}//end of Data loop
					?>
				</tbody>
			</table>
			<?php } ?>
		
		</div><!--end of quicklook-->
		</div>

	<!-- End of Channel A compounds -->
	</div>
	</div>


	<div class="container-fluid quick-look-info" style="background: #ddd; ">
		<div class="col-sm-3">
			<b><?php echo $site_info[short_name]; ?></b><br/>
			<?php echo date('Y-m-d',strtotime($txo[0]['data_acquisition_time'])); ?>
		</div>
		<div class="col-sm-3">
			<b>CONCENTRATION PPB-C</b>
		</div>
		<div class="col-sm-3">
			<b>CURRENT SEQUENCE/IDX NAME</b><br/>
			<?php echo basename($txo[0]['sequence_file']); ?></div>
		<div class="col-sm-3">
			<b>SEQUENCE (DAYS IN USE)</b><br/>
			?
		</div>
	</div>

	<div class="panel panel-default" style="margin-top: 15px">
	<div class="panel-heading">Channel B
		<span class="toggle-chart">
			<button class="btn bt-toggle-chart" id="bt-column-chart-b"><i class="fa fa-bar-chart"></i></button>
			<button class="btn bt-toggle-chart" id="bt-line-chart-b"><i class="fa fa-line-chart"></i></button>
			<button class="btn bt-previous-chart" id="bt-previous-chart-b"><i class="fa fa-chevron-circle-left"></i></button>
			<button class="btn bt-next-chart" id="bt-next-chart-b"><i class="fa fa-chevron-circle-right"></i></button>
		</span>
	</div>
	<div class="panel-body">
	<!-- Channel B Compounds -->
		<div class="container-fluid">
			<div id="myChart-b" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
		</div>
		<div class="panel-body table-responsive">
			<div class="quicklook">
			<?php if($headerb){ ?>
			<table class="table table-bordered" id="quicklookb">
				<thead>
					<tr style="font-size: 9px;">
						<th></th>
						<th class="text-center">File</th>
						<!--th class="text-center">Date</th-->
						<th class="text-center">Time</th>
					<?php

						$th = count($headerb);
						$st = count($standards);
						
						$extra_char = array(" ","-",",");
						for($i=0; $i<$th; $i++){
							$standard = '';
							for($j=0; $j<$st; $j++){
								$st_name = substr(str_replace($extra_char, "", $standards[$j]['component_name']),0,10);
								$hd_name = substr(str_replace($extra_char, "", $headerb[$i]['component_name']),0,10);
								if($st_name == $hd_name){
									$standard = 'standard';
									break;
								}
							}
							?>
							<th class="text-center th-labels-b <?php echo  $standard; ?>" id="<?php echo strtolower($headerb[$i]['component_name']); ?>"><?php echo ($headerb[$i]['alias']) ? $headerb[$i]['alias'] : $headerb[$i]['component_name']; ?></th>
							<?php
						}
					?>
						<th>Total<br/>Target</th>
						<?php
						if($view != 'responsefactor'){?>
						<th>Total</th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>

					<?php 
						$tt = count($txo);
						$extra_char = array(" ","-",",");
						$view = $_GET['v'];

						for($i=0; $i<$tt; $i++){ //data loop

							if($txo[$i]['channel']=='B')
							{
								$filename = str_replace('.TX0', '', $txo[$i]['filename']);

								$lfn = stripos(substr($filename,-5,1), 'e');
								$cfn = stripos(substr($filename,-5,1), 'c');
								$bfn = stripos(substr($filename,-5,1), 'b');
								$rfn = stripos(substr($filename,-5,1), 'q');

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

								echo '<tr class="'.$standard.' row-channel-b" id="'.$filename.'">';
								echo '<td><button id="bt-'.$filename.'" class="bt-chartb" data-channel="b"><i class="fa fa-line-chart"></i></button></td>';
								echo '<td><a href="' .site_url().'txo_dumps/edit/'.$filename.'.TX0'.'">'.$filename.'</a></td>';
								//echo '<td>'.date('d-M',strtotime($txo[$i]['data_acquisition_time'])).'</td>';
								echo '<td>'.date('H:i',strtotime($txo[$i]['data_acquisition_time'])).'</td>';
								
								$tc = count($txo[$i]['components']);
								$tnmc = 0;
								$tnmtc = 0;

								for($j=0; $j<$th; $j++){//header loop
									$match = false;
									
									for($k=0; $k<$tc; $k++){//component loop
										$header = substr(str_replace($extra_char, "", $headerb[$j]['component_name']),0,10);
										$component = substr(str_replace($extra_char, "", $txo[$i]['components'][$k]['component_name']),0,10);
										
										if($view == 'responsefactor'){
											$area = (float)$txo[$i]['components'][$k]['area'];
											$amount = (float)$txo[$i]['components'][$k]['amount'];
											if($area>0){
												$value = $area / $amount;
												$value = round($value,0, PHP_ROUND_HALF_UP);
											}else{
												$value = 0;
											}

											if($value < $channel_b_rf){
												$err_rf = 'err-rf';
											}else{
												$err_rf = '';
											}
										}else{
											$value = $txo[$i]['components'][$k][$view];
										}

										if($component=='METHANE'){
											$methane = $value;
										}

										if($header==$component)
										{
											$match = true;
											
											echo '<td class="'.strtolower($headerb[$j]['component_name']).' '.$err_rf.' value text-center" data-value="'.$value.'">'. $value .'</td>';
											$tnmtc += (float) $value;
											break;
										}

										/*if($k==$tc-1){
											switch($view){
												case 'amount':
													$tnmc = $txo[$i]['pp_carbon'] - $methane;
													$tnmtc = $tnmtc - $methane;
													break;
												case 'time':
													$tnmc = $txo[$i]['time'] - $methane;
													$tnmtc = $tnmtc - $methane;
													break;
												case 'area':
													$tnmc = $txo[$i]['area'] - $methane;
													$tnmtc = $tnmtc - $methane;
													break;
											}
											
										}*/

									}//end of component loop

									if(!$match)
									{
										echo '<td class="'.strtolower($headerb[$j]['component_name']).' value text-center" data-value="N/A">N/A</td>';
									}

								}//end of header loop

								switch($view){
									case 'amount':
										$tnmc = $txo[$i]['pp_carbon'];
										break;
									case 'time':
										$tnmc = $txo[$i]['time'];
										break;
									case 'area':
										$tnmc = $txo[$i]['area'];
										break;
								}

								$tnmc = ($tnmc<=0) ? 0: $tnmc;
								echo '<td class="value-" data-value="'.$tnmtc.'">'.$tnmtc.'</td>';
								if($view != 'responsefactor'){
									echo '<td class="value-" data-value="'.$tnmc.'">'.$tnmc.'</td>';
								}
								echo '</tr>';

							}//end of Channel B check
						}//end of data-loop
					?>
				</tbody>
			</table>
			<?php } ?>
		
		</div><!--end of quick-look-->
		</div>
	
	<!-- End of Channel B compounds -->
	</div>
	</div>
		

<!-- End of panel wrapper -->
</div>
</div>

<?php
	echo $cvs_content;
	echo $lcs_content;
?>