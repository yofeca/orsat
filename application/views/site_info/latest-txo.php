<div class="row" id="site-data">
	<div class="container-fluid">
		<div class="panel panel-default" id="panel-table">
			<div class="panel-heading">Records</div>
			<div class="panel-body">
				<div class="col-sm-12 table-responsive">
					<table class="table table-striped table-bordered" id="dt-wrapper">
						<thead>
							<tr>
								<th>Date</th>
								<th>Hour(s)</th>
								<th>View</th>
							</tr>
						</thead>
						
						<?php
						$t = count($txo);
						for($i=0; $i<$t; $i++){
							?>
							<tr>
								<td><?php echo $txo[$i]['dd'];?></td>

								<td>
									<?php
										$total_txo = count($txo[$i]['list']);
										echo 'Channel A:';
										for($j=0; $j<$total_txo; $j++){
											$filename = $txo[$i]['list'][$j]['filename'];
											if(substr($filename,-10,1)=='P'){
											?>
												<a href="<?php echo site_url(); ?>txo_dumps/edit/<?php echo $txo[$i]['list'][$j]['filename']; ?>"><?php echo substr($filename,-5,1); ?></a>
											<?php
											}
										}
										echo '<br/>';
										echo 'Channel B:';
										for($j=0; $j<$total_txo; $j++){
											$filename = $txo[$i]['list'][$j]['filename'];
											if(substr($filename,-10,1)=='B'){
											?>
												<a href="<?php echo site_url(); ?>txo_dumps/edit/<?php echo $txo[$i]['list'][$j]['filename']; ?>"><?php echo substr($filename,-5,1); ?></a>
											<?php
											}
										}
									?>
								</td>
								<td>
									<a class="btn btn-default" href="<?php echo site_url(); ?>site_quick_look?dd=<?php echo urlencode($txo[$i]['dd']); ?>&sid=<?php echo urlencode($txo[$i]['site_id']);?>&v=amount">Amount</a>
									<a class="btn btn-default" href="<?php echo site_url(); ?>site_quick_look?dd=<?php echo urlencode($txo[$i]['dd']); ?>&sid=<?php echo urlencode($txo[$i]['site_id']);?>&v=area">Area</a>
									<a class="btn btn-default" href="<?php echo site_url(); ?>site_quick_look?dd=<?php echo urlencode($txo[$i]['dd']); ?>&sid=<?php echo urlencode($txo[$i]['site_id']);?>&v=time">Retention Time</a>
									<a class="btn btn-default" href="<?php echo site_url(); ?>site_quick_look?dd=<?php echo urlencode($txo[$i]['dd']); ?>&sid=<?php echo urlencode($txo[$i]['site_id']);?>&v=responsefactor">Response Factor</a>
								</td>
							</tr>
							<?php
						}
						?>
					</table>
				</div><!--/.table-responsive-->
			</div><!--/.panel-body-->
		</div><!--/.panel-->
	</div><!--/.container-fluid-->
</div><!--/.row-->