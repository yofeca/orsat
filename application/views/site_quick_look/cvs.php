<style type="text/css">
	.cvs-report th,.cvs-report th{ font-size: 13px !important; }
	.cvs-info li{ list-style: none; display: inline; margin-right: 20px; }
	.cvs-info ul{ padding-left: 0;}
</style>
<?php //echo '<pre>'; print_r($cvs); echo '</pre>'; ?>
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
						[channel_a_rf] => 5068 [channel_b_rf] => 5365 
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
							$cvs_count = count($cvs['A']);
							if($cvs_count){
								for($h=0; $h<$cvs_count; $h++){
								//$this->txo_data->printr($cvs);
								$cvsa = $cvs['A'][$h];
								$tcvsa = count($cvs['A'][$h]);
								
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
							} //end of cvs loop
							} //end of cvs_count check
							?>
						</table>
					</div><!--/.end of table-responsive -->
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