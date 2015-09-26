<script>
	function deleteRecord(co_id){
		if(confirm("Are you sure you want to delete this record?")){
			formdata = "id="+co_id;
			$.ajax({
				url: "<?php echo site_url(); echo $controller ?>/ajax_delete/"+co_id,
				type: "POST",
				data: formdata,
				dataType: "script",
				success: function(){
					$("#tr"+co_id).fadeOut(200);
					self.location = "<?php echo site_url(); echo $controller ?>";
				}
			});
		}
	}
	function searchRecord(){
		self.location = "<?php echo site_url(); ?><?php echo $controller; ?>/search/?search="+$("#search").val()+"&filter="+$("#sfilter").val();
	}
	function addRecord(){
		self.location = "<?php echo site_url(); echo $controller; ?>/add";
	}
	function uploadTxo(){
		self.location = "<?php echo site_url(); echo $controller; ?>/add_txo_files";
	}
</script>

<div class="row">
	<div class="container-fluid">
		<div class="panel panel-default">
			<div class="panel-heading">Search</div>
			<div class="panel-body">
				<form action="<?php echo site_url(); ?><?php echo $controller; ?>/search/" class='form-inline' >
					<div class="form-group">
						<label class="sr-only" for="search">Search:</label>
						<input placeholder="Search key" class="form-control" type='text' id='search' value="<?php echo sanitizeX($search); ?>" name='search' />
					</div>
					<div class="form-group">
						<label class="sr-only">Filter:</label>
						<div class="col-sm-4">
							<select name='filter' id='sfilter' class="form-control chosen-select" data-placeholder="Choose a Filter">
								<option></option>
								<option value="filename">Filename</option>
								<option value="date">Date</option>	
								<!--option value="sample_type_id">Sample ID</option-->	
								<option value="sample_name">Sample Name</option>	
								<option value="sample_number">Sample Number</option>	
								<!--option value="site_id">Site ID</option-->	
								<option value="instrument_name">Instrument Name</option>	
								<option value="channel">Channel</option>	
								<option value="data_acquisition_time">Data Acquisition Time</option>	
								<option value="cycle">Cycle</option>	
								<option value="raw_data_file">Raw Data File</option>	
								<option value="inst_method">Instrument Method</option>	
								<option value="sequence_file">Sequence File</option>	
								<option value="noise_threshold">Noise Treshold</option>	
								<option value="area_threshold">Area Treshold</option>	
								<option value="bunch_factor">Bunch Factor</option>	
							</select>
						</div>
					</div>
					<input type='button' class='btn btn-default' value='search' onclick='searchRecord()'>
					<input type='button' class='btn btn-default' value='upload txo' onclick='uploadTxo()'>
				</form>
				<?php
				if(trim($filter)){
					?>
					<script>
					$("#sfilter").val("<?php echo sanitizeX($filter); ?>")
					</script>
					<?php
				}
				$t = count($records);
				?>
			</div><!--/.panel-body-->
		</div><!--/.panel.panel-default-->
	</div>
</div>

<div class="row">
	<div class="container-fluid">
		<div class="panel panel-default" id="panel-table">
			<div class="panel-heading">Records</div>
			<div class="panel-body">
				<div class="col-sm-12 table-responsive">
					<table class="table table-striped table-bordered" id="dt-wrapper">
						<thead>
							<tr>
								<!--<th>#</th>-->
								<!--th>ID</th-->
								<th>Filename</th>
								<th>Date</th>
								<!--th>Sample ID</th-->
								<th>Sample Name</th>
								<th>Sample Number</th>
								<!--th>Site ID</th-->
								<th>Instrument Name</th>
								<th>Channel</th>
								<th>Data Acquisition Time</th>
								<th>Cycle</th>
								<th>Raw Data File</th>
								<th>Instrument Method</th>
								<th>Sequence File</th>
								<th>Noise Treshold</th>
								<th>Area Treshold</th>
								<th>Bunch Factor</th>
								<th></th>
							</tr>
						</thead>
						
						<tbody>
						<?php
						for($i=0; $i<$t; $i++){
							?>
							<tr id="tr<?php echo htmlentitiesX($records[$i]['id']); ?>">
								<!--<td><?php echo $start+$i+1; ?></td>-->
								<!--td><a href="<?php echo site_url(); ?><?php echo $controller; ?>/edit/<?php echo $records[$i]['id']?>" ><?php echo htmlentitiesX($records[$i]['id']); ?></a></td-->	
								<td><a href="<?php echo site_url(); ?><?php echo $controller; ?>/edit/<?php echo $records[$i]['id']?>" ><?php echo $records[$i]['filename'];?></a></td>
								<td><?php echo $records[$i]['date'];?></td>
								<!--td><?php echo $records[$i]['sample_type_id'];?></td-->
								<td><?php echo $records[$i]['sample_name'];?></td>
								<td><?php echo $records[$i]['sample_number'];?></td>
								<!--td><?php echo $records[$i]['site_id'];?></td-->
								<td><?php echo $records[$i]['instrument_name'];?></td>
								<td><?php echo $records[$i]['channel'];?></td>
								<td><?php echo $records[$i]['data_acquisition_time'];?></td>
								<td><?php echo $records[$i]['cycle'];?></td>
								<td><?php echo $records[$i]['raw_data_file'];?></td>
								<td><?php echo $records[$i]['inst_method'];?></td>
								<td><?php echo $records[$i]['sequence_file'];?></td>
								<td><?php echo $records[$i]['noise_threshold'];?></td>
								<td><?php echo $records[$i]['area_threshold'];?></td>
								<td><?php echo $records[$i]['bunch_factor'];?></td>
								<td width='50px'>
									<a href="<?php echo site_url(); ?><?php echo $controller; ?>/edit/<?php echo $records[$i]['id']?>" ><i class="fa fa-pencil-square-o"></i></a>
									<a style='color: red; cursor:pointer;' onclick='deleteRecord("<?php echo htmlentitiesX($records[$i]['id']) ?>"); ' ><i class="fa fa-trash-o"></i></a>
								</td>
							</tr>
							<?php
						}
						?>
						</tbody>
					</table>
				</div><!--/.table-responsive-->
			</div><!--/.panel-body-->
			<div class="panel-footer">
				<?php
					if($pages>0){
						?>
							There is a total of <?php echo $cnt; ?> <?php if($cnt>1) { echo "records"; } else{ echo "record"; }?> in the database. 
								Go to Page:
								<?php
								if($search){
									?>
									<select onchange='self.location="?search=<?php echo sanitizeX($search); ?>&filter=<?php echo sanitizeX($filter); ?>&start="+this.value'>
									<?php
								}
								else{
									?>
									<select onchange='self.location="?start="+this.value'>
									<?php
								}
								for($i=0; $i<$pages; $i++){
									if(($i*$limit)==$start){
										?><option value="<?php echo $i*$limit?>" selected="selected"><?php echo $i+1; ?></option><?php
									}
									else{
										?><option value="<?php echo $i*$limit?>"><?php echo $i+1; ?></option><?php
									}
								}
								?>
								</select>
							</td>
						</tr>
						<?php
					}
				?>
			</div>
		</div><!--/.panel-->
	</div><!--/.container-fluid-->
</div><!--/.row-->
