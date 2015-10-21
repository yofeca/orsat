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
</style>

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
	<div class="panel-body">
	<!-- Channel A Compounds -->

		<div class="panel-body table-responsive">

			<div class="container-fluid">
				<canvas id="myChart-a" width="1200px" height="400"></canvas>
			</div>
			<?php if($headera){ ?>
			<table class="table table-bordered" id="quicklooka">
				<thead>
					<tr style="font-size: 9px;">
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
						<th>TNMC</th>
						<th>TNMTC</th>
					</tr>
				</thead>
				<tbody>

					<?php 
						$tt = count($txo);
						$extra_char = array(" ","-",",");
						$view = $_GET['v'];

						

						for($i=0; $i<$tt; $i++){
							if($txo[$i]['channel']=='A'){
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

								echo '<tr class="'.$standard.' row-channel-a">';
								echo '<td><a href="' .site_url().'txo_dumps/edit/'.$filename.'.TX0'.'">'.$filename.'</a></td>';
								echo '<td>'.date('d-M',strtotime($txo[$i]['data_acquisition_time'])).'</td>';
								echo '<td>'.date('H:i',strtotime($txo[$i]['data_acquisition_time'])).'</td>';
								
								$tc = count($txo[$i]['components']);
								$tnmc = 0;
								$tnmtc = 0;
								for($j=0; $j<$th; $j++){
									$match = false;
									
									
									for($k=0; $k<$tc; $k++){
										$header = substr(str_replace($extra_char, "", $headera[$j]['component_name']),0,10);
										$component = substr(str_replace($extra_char, "", $txo[$i]['components'][$k]['component_name']),0,10);
										$value = $txo[$i]['components'][$k][$view];
										if($component=='METHANE'){
											$methane = $value;
										}

										

										if($header==$component){
											$match = true;
											
											echo '<td class="'.strtolower($headera[$j]['component_name']).' value text-center" data-value="'.$value.'">'. $value .'</td>';
											$tnmtc += (float) $value;
											break;
										}
										if($k==$tc-1){
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
											
										}
									}
									if(!$match){
										echo '<td class="'.strtolower($headera[$j]['component_name']).' value text-center">n/a</td>';
									}
								}
								$tnmc = ($tnmc<=0) ? 0: $tnmc;
								echo '<td class="value" data-value="'.$tnmc.'">'.$tnmc.'</td>';
								echo '<td class="value" data-value="'.$tnmtc.'">'.$tnmtc.'</td>';
								echo '</tr>';
							}
						}
					?>
				</tbody>
			</table>
			<?php } ?>
		</div>

	<!-- End of Channel A compounds -->
	</div>
	</div>

	<div class="panel panel-default" style="margin-top: 15px">
	<div class="panel-heading">Channel B</div>
	<div class="panel-body">
	<!-- Channel B Compounds -->

		<div class="panel-body table-responsive">

			<div class="container-fluid">
				<canvas id="myChart-b" width="1200px" height="400"></canvas>
			</div>
			<?php if($headerb){ ?>
			<table class="table table-bordered" id="quicklookb">
				<thead>
					<tr style="font-size: 9px;">
						<th class="text-center">File</th>
						<th class="text-center">Date</th>
						<th class="text-center">Time</th>
					<?php
						
						$th = count($headerb);
						$st = count($standards);
						echo $st;
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
						<th>TNMC</th>
						<th>TNMTC</th>
					</tr>
				</thead>
				<tbody>

					<?php 
						$tt = count($txo);
						$extra_char = array(" ","-",",");
						$view = $_GET['v'];

						

						for($i=0; $i<$tt; $i++){
							if($txo[$i]['channel']=='B'){
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

								echo '<tr class="'.$standard.' row-channel-b">';
								echo '<td><a href="' .site_url().'txo_dumps/edit/'.$filename.'.TX0'.'">'.$filename.'</a></td>';
								echo '<td>'.date('d-M',strtotime($txo[$i]['data_acquisition_time'])).'</td>';
								echo '<td>'.date('H:i',strtotime($txo[$i]['data_acquisition_time'])).'</td>';
								
								$tc = count($txo[$i]['components']);
								$tnmc = 0;
								$tnmtc = 0;
								for($j=0; $j<$th; $j++){
									$match = false;
									
									
									for($k=0; $k<$tc; $k++){
										$header = substr(str_replace($extra_char, "", $headerb[$j]['component_name']),0,10);
										$component = substr(str_replace($extra_char, "", $txo[$i]['components'][$k]['component_name']),0,10);
										$value = $txo[$i]['components'][$k][$view];
										if($component=='METHANE'){
											$methane = $value;
										}

										

										if($header==$component){
											$match = true;
											
											echo '<td class="'.strtolower($headerb[$j]['component_name']).' value text-center" data-value="'.$value.'">'. $value .'</td>';
											$tnmtc += (float) $value;
											break;
										}
										if($k==$tc-1){
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
											
										}
									}
									if(!$match){
										echo '<td class="'.strtolower($headerb[$j]['component_name']).' value text-center">n/a</td>';
									}
								}
								$tnmc = ($tnmc<=0) ? 0: $tnmc;
								echo '<td class="value" data-value="'.$tnmc.'">'.$tnmc.'</td>';
								echo '<td class="value" data-value="'.$tnmtc.'">'.$tnmtc.'</td>';
								echo '</tr>';
							}
						}
					?>
				</tbody>
			</table>
			<?php } ?>
		</div>
	
	<!-- End of Channel B compounds -->
	</div>
	</div>
		

<!-- End of panel wrapper -->
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

		<style type="text/css">
			.cvs-report th,.cvs-report th{ font-size: 13px !important; }
			.cvs-info li{ list-style: none; display: inline; margin-right: 20px; }
			.cvs-info ul{ padding-left: 0;}
		</style>

		<div class="panel panel-default" style="margin-top: 15px;">
			<div class="panel-heading">cvs Concentration</div>
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
