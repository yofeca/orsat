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

<div class="panel panel-default">
<div class="panel-heading">
	<?php 
		echo $site_info['instrument_name'] . ' - ' . $site_info['short_name']; ?> 
		Daily Quick Look <a href="<?php echo site_url('site_info') ?>?sid=<?php echo $site_info['id'] ?>" style="font-weight: normal; font-size: 12px">
		Back to Site Info.</a>

		<div id="site-prev-next" style="float: right; margin-top: -3px;">
			<button id="bt-previous"><i class="fa fa-arrow-left"></i></button>
			<button id="bt-next"><i class="fa fa-arrow-right"></i></button>
		</div>
</div>
<div class="panel-body">
<!-- Panel wrapper -->
	<div class="container-fluid">
		<div id="filters" class="pull-right col-sm-7">
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
	<div class="panel-heading">Channel A 
		<span class="toggle-chart">
			<button class="btn bt-toggle-chart" id="bt-column-chart-a"><i class="fa fa-bar-chart"></i></button>
			<button class="btn bt-toggle-chart" id="bt-line-chart-a"><i class="fa fa-line-chart"></i></button>
			<button class="btn bt-previous-chart" id="bt-previous-chart-a"><i class="fa fa-chevron-circle-left"></i></button>
			<button class="btn bt-next-chart" id="bt-next-chart-a"><i class="fa fa-chevron-circle-right"></i></button>
		</span>
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
						<th class="text-center">Date</th>
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
						<th>Total</th>
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
								echo '<td>'.date('d-M',strtotime($txo[$i]['data_acquisition_time'])).'</td>';
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
										$value = $txo[$i]['components'][$k][$view];
										if($component=='METHANE'){
											$methane = $value;
										}

										

										if($header==$component)
										{
											$match = true;
											
											echo '<td class="'.strtolower($headera[$j]['component_name']).' value text-center" data-value="'.$value.'">'. $value .'</td>';
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
								echo '<td class="value-" data-value="'.$tnmc.'">'.$tnmc.'</td>';
								echo '<td class="value-" data-value="'.$tnmtc.'">'.$tnmtc.'</td>';
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
						<th class="text-center">Date</th>
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
						<th>Total</th>
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
								echo '<td>'.date('d-M',strtotime($txo[$i]['data_acquisition_time'])).'</td>';
								echo '<td>'.date('H:i',strtotime($txo[$i]['data_acquisition_time'])).'</td>';
								
								$tc = count($txo[$i]['components']);
								$tnmc = 0;
								$tnmtc = 0;

								for($j=0; $j<$th; $j++){//header loop
									$match = false;
									
									for($k=0; $k<$tc; $k++){//component loop
										$header = substr(str_replace($extra_char, "", $headerb[$j]['component_name']),0,10);
										$component = substr(str_replace($extra_char, "", $txo[$i]['components'][$k]['component_name']),0,10);
										$value = $txo[$i]['components'][$k][$view];
										if($component=='METHANE'){
											$methane = $value;
										}

										if($header==$component)
										{
											$match = true;
				
											echo '<td class="'.strtolower($headerb[$j]['component_name']).' value text-center" data-value="'.$value.'">'. $value .'</td>';
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
								echo '<td class="value-" data-value="'.$tnmc.'">'.$tnmc.'</td>';
								echo '<td class="value-" data-value="'.$tnmtc.'">'.$tnmtc.'</td>';
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

		<style type="text/css">
			.cvs-report th,.cvs-report th{ font-size: 13px !important; }
			.cvs-info li{ list-style: none; display: inline; margin-right: 20px; }
			.cvs-info ul{ padding-left: 0;}
		</style>

		<div class="panel panel-default" style="margin-top: 15px;">
			<div class="panel-heading">CVS Concentration</div>
			<div class="panel-body">
				<?php //$this->txo_data->printr($cvs); ?>
				<div class="col-sm-12 cvs-info">
					<ul>
						<li><b>Cylinder:</b> <?php echo $cvs['coa']['cylinder']; ?></li>
						<li><b>Date On:</b> <?php echo $cvs['coa']['date_on']; ?></li>
						<li><b>Date Off:</b> <?php echo (! $cvs['coa']['date_off'] ) ? 'In use': $cvs['coa']['date_off']; ?></li>
						<li><b>Dilution Factor:</b> <?php echo $cvs['coa']['value']; ?></li>
					</ul>
				</div>
				
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading">Channel A</div>
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table cvs-report">
									<tr>
										<th>Name</th>
										<!--th>Alias</th-->
										<th>CARBON<br>Number</th>
										<th>Certif<br>Con'c<br>ppmV</th>
										<th>Measur<br>Con'c</th>
										<th>Cal<br>Dilute<br>ppbC</th>
										<th>RUN<br>E<br>%Recovery</th>
									</tr>
									<?php
										//$this->txo_data->printr($cvs);
										$cvsa = $cvs['A'][0];
										$tcvsa = count($cvs['A'][0]);
										
										for($i=0; $i<$tcvsa; $i++){
											if($cvsa[$i]['channel']=="A"){
											
											$carbon = $cvsa[$i]['carbon_no'];
											$method_name = $cvsa[$i]['component_name'];
											$concentration = $cvsa[$i]['value'];
											$amount = $cvsa[$i]['amount'];
											$ppbc = $concentration * $cvs['coa']['value'] * $carbon * 1000;
											$r = ($ppbc>0) ? ($amount / $ppbc * 100 ) : 0;

											if($cvsa[$i]['component_name']=='PROPANE'){
												$err = ($r<75 || $r>125) ? 'fail' : '';
											}else{
												$err = ($r<55 || $r>145) ? 'fail' : '';
											}
									?>
											<tr>
												<td><?php echo $cvsa[$i]['component_name']; ?></td>
												<!--td><?php echo $cvsa[$i]['alias']; ?></td-->
												<td><?php echo $cvsa[$i]['carbon_no']; ?></td>
												<td><?php echo $cvsa[$i]['value']; ?></td>
												<td><?php echo $cvsa[$i]['amount']; ?></td>
												<td><?php echo number_format((float) $ppbc, 2, '.', ''); ?></td>
												<td class="<?php echo  $err; ?>">
													<?php echo number_format((float) $r, 2, '.', '');?>
												</td>
											</tr>
										<?php
											}//channel-a condition
										}//channel-a loop
									?>
								</table>
							</div>
						</div>
					</div>
				</div><!--cvs-col-sm-6-->

				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading">Channel B</div>
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table cvs-report">
									<tr>
										<th>Name</th>
										<!--th>Alias</th-->
										<th>CARBON<br>Number</th>
										<th>Certif<br>Con'c<br>ppmV</th>
										<th>Measur<br>Con'c</th>
										<th>Cal<br>Dilute<br>ppbC</th>
										<th>RUN<br>E<br>%Recovery</th>
									</tr>
									<?php
										//$this->txo_data->printr($cvs);
										$cvsb = $cvs['B'][0];
										$tcvsb = count($cvs['B'][0]);
										
										for($i=0; $i<$tcvsb; $i++){
											if($cvsb[$i]['channel']=="B"){
											
											$carbon = $cvsb[$i]['carbon_no'];
											$method_name = $cvsb[$i]['component_name'];
											$concentration = $cvsb[$i]['value'];
											$amount = $cvsb[$i]['amount'];
											$ppbc = $concentration * $cvs['coa']['value'] * $carbon * 1000;
											$r = ($ppbc>0) ? ($amount / $ppbc * 100 ) : 0;

											if($cvsb[$i]['component_name']=='BENZENE'){
												$err = ($r<75 || $r>125) ? 'fail' : '';
											}else{
												$err = ($r<55 || $r>145) ? 'fail' : '';
											}										
									?>
											<tr>
												<td><?php echo $cvsb[$i]['component_name']; ?></td>
												<!--td><?php echo $cvsb[$i]['alias']; ?></td-->
												<td><?php echo $cvsb[$i]['carbon_no']; ?></td>
												<td><?php echo $cvsb[$i]['value']; ?></td>
												<td><?php echo $cvsb[$i]['amount']; ?></td>
												<td><?php echo number_format((float) $ppbc, 2, '.', ''); ?></td>
												<td class="<?php echo ($cvsb[$i]['component_name']=='BENZENE' && ($r<70 || $r>130)) ? 'fail' : ''; ?>">
													<?php echo number_format((float) $r, 2, '.', ''); ?>
												</td>
											</tr>
										<?php
											}//channel-a condition
										}//channel-a loop
									?>
								</table>
							</div>
						</div>
					</div>
				</div><!--cvs-col-sm-6-->

			</div>
		</div>

<div class="panel panel-default" style="margin-top: 15px">
	<div class="panel-heading">LCS Compounds</div>
	<div class="panel-body">
		<?php 
		$lcsa_total = count($lcs_concentration[0]);
		$lcsb_total = count($lcs_concentration[1]);
		?>
		<style type="text/css">
			.lcs-report th,.lcs-report th{ font-size: 13px !important; }
			.lcs-info li{ list-style: none; display: inline; margin-right: 20px; }
			.lcs-info ul{ padding-left: 0; }
		</style>

		<div class="panel panel-default">
			<div class="panel-heading">LCS Concentration</div>
			<div class="panel-body">
				<div class="col-sm-12 lcs-info">
					<ul>
						<li><b>Cylinder:</b> <?php echo $lcs['coa']['cylinder']; ?></li>
						<li><b>Date On:</b> <?php echo $lcs['coa']['date_on']; ?></li>
						<li><b>Date Off:</b> <?php echo (! $lcs['coa']['date_off'] ) ? 'In use': $lcs['coa']['date_off']; ?></li>
						<li><b>Dilution Factor:</b> <?php echo $lcs['coa']['value']; ?></li>
					</ul>
				</div>
				
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading">Channel A</div>
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table lcs-report">
									<tr>
										<th>Name</th>
										<!--th>Alias</th-->
										<th>CARBON<br>Number</th>
										<th>Certif<br>Con'c<br>ppmV</th>
										<th>Measur<br>Con'c</th>
										<th>Cal<br>Dilute<br>ppbC</th>
										<th>RUN<br>E<br>%Recovery</th>
									</tr>
									<?php
										//$this->txo_data->printr($lcs);
										$lcsa = $lcs['A'][0];
										$tlcsa = count($lcs['A'][0]);
										
										for($i=0; $i<$tlcsa; $i++){
											if($lcsa[$i]['channel']=="A"){
											
											$carbon = $lcsa[$i]['carbon_no'];
											$method_name = $lcsa[$i]['component_name'];
											$concentration = $lcsa[$i]['value'];
											$amount = $lcsa[$i]['amount'];
											$ppbc = $concentration * $lcs['coa']['value'] * $carbon * 1000;
											$r = ($ppbc>0) ? ($amount / $ppbc * 100 ) : 0;
									?>
											<tr>
												<td><?php echo $lcsa[$i]['component_name']; ?></td> <!--Name-->
												<!--td><?php echo $lcsa[$i]['alias']; ?></td-->
												<td><?php echo $lcsa[$i]['carbon_no']; ?></td> <!--Carbon No-->
												<td><?php echo $lcsa[$i]['value']; ?></td> <!--ppmV-->
												<td><?php echo $lcsa[$i]['amount']; ?></td> <!--Calc ppbc-->
												<td><?php echo number_format((float) $ppbc, 2, '.', ''); ?></td> <!--Measured Cons-->
												<td class="<?php echo ($lcsa[$i]['component_name']=='PROPANE' && ($r<70 || $r>130)) ? 'fail' : ''; ?>">
													<?php echo number_format((float) $r, 2, '.', '');?>
												</td> <!--Name-->
											</tr>
										<?php
											}//channel-a condition
										}//channel-a loop
									?>
								</table>
							</div>
						</div>
					</div>
				</div><!--lcs-col-sm-6-->

				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading">Channel B</div>
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table lcs-report">
									<tr>
										<th>Name</th>
										<!--th>Alias</th-->
										<th>CARBON<br>Number</th>
										<th>Certif<br>Con'c<br>ppmV</th>
										<th>Measur<br>Con'c</th>
										<th>Cal<br>Dilute<br>ppbC</th>
										<th>RUN<br>E<br>%Recovery</th>
									</tr>
									<?php
										//$this->txo_data->printr($lcs);
										$lcsb = $lcs['B'][0];
										$tlcsb = count($lcs['B'][0]);
										
										for($i=0; $i<$tlcsb; $i++){
											if($lcsb[$i]['channel']=="B"){
											
											$carbon = $lcsb[$i]['carbon_no'];
											$method_name = $lcsb[$i]['component_name'];
											$concentration = $lcsb[$i]['value'];
											$amount = $lcsb[$i]['amount'];
											$ppbc = $concentration * $lcs['coa']['value'] * $carbon * 1000;
											$r = ($ppbc>0) ? ($amount / $ppbc * 100 ) : 0;											
									?>
											<tr>
												<td><?php echo $lcsb[$i]['component_name']; ?></td>
												<!--td><?php echo $lcsb[$i]['alias']; ?></td-->
												<td><?php echo $lcsb[$i]['carbon_no']; ?></td>
												<td><?php echo $lcsb[$i]['value']; ?></td>
												<td><?php echo $lcsb[$i]['amount']; ?></td>
												<td><?php echo number_format((float) $ppbc, 2, '.', ''); ?></td>
												<td class="<?php echo ($lcsb[$i]['component_name']=='BENZENE' && ($r<70 || $r>130)) ? 'fail' : ''; ?>">
													<?php echo number_format((float) $r, 2, '.', ''); ?>
												</td>
											</tr>
										<?php
											}//channel-a condition
										}//channel-a loop
									?>
								</table>
							</div>
						</div>
					</div>
				</div><!--lcs-col-sm-6-->

			</div>
		</div>

	</div>
</div>
