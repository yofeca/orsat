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
