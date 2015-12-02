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
		if($('#sfilter').val().length>0){
			self.location = "<?php echo site_url(); ?><?php echo $controller; ?>/search/?search="+$("#search").val()+"&filter="+$("#sfilter").val();
		}else{
			$('.chosen-single').addClass('err-filter').focus();
		}
	}
	
	function addRecord(){
		self.location = "<?php echo site_url(); echo $controller; ?>/add";
	}
</script>

<style>
	.table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th{ padding: 4px; font-size: 12px;}
</style>
<div class="row">
	<div class="container-fluid">
		<div class="panel panel-default">
			<div class="panel-heading">Site List <button id="bt-delete-selection">Delete</button></div>
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
							<option value="instrument_name">Instrument Name</option>	
							<option value="network_id">Network</option>	
							<option value="site_designator">Site Designator</option>	
							<option value="aqs_no">AQS Number</option>	
							<option value="short_name">Short Name</option>	
							<option value="formal_name">Formal Name</option>	
							<option value="address">Address</option>	
							<option value="city">City</option>	
							<option value="zip">Zip</option>	
							<option value="latitude">Latitude</option>	
							<option value="longitude">Longitude</option>	
							<option value="notes">Notes</option>	
							<option value="cams_code">CAMS Code</option>	
							<option value="doc">Doc</option>	
							<option value="interval">Interval</option>	
							<option value="units_code">Units Code</option>	
							<option value="method_code">Method Code</option>	
							</select>
						</div>
					</div>
					<input type='button' class='btn btn-default' value='search' onclick='searchRecord()'>
					<input type='button' class='btn btn-default' value='add' onclick='addRecord()'>
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
								<!--<th>ID</th>-->
								<th>Instrument Name</th>
								<th>Network Name</th>
								<th>Site Designator</th>
								<th>AQS Number</th>
								<th>Short Name</th>
								<th>Formal Name</th>
								<th>Address</th>
								<th>City</th>
								<th>Zip</th>
								<th>Latitude</th>
								<th>Longitude</th>
								<th>Notes</th>
								<th>CAMS Code</th>
								<th>Doc</th>
								<th>Interval</th>
								<th>Units Code</th>
								<th>Method Code</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
							for($i=0; $i<$t; $i++){
								?>
								<tr id="tr<?php echo htmlentitiesX($records[$i]['id']); ?>">
									<!--<td><?php echo $start+$i+1; ?></td>-->
									<!--<td><?php echo htmlentitiesX($records[$i]['id']); ?></td>-->
									<td data-id="test"><a href="<?php echo site_url(); ?>site_info?sid=<?php echo $records[$i]['id']?>" ><?php echo $records[$i]['instrument_name'];?></a></td>
									<td><a href="<?php echo site_url(); ?>network/edit/<?php echo $records[$i]['network_name']?>" ><?php echo $records[$i]['network_name'];?></a></td>
									<td><?php echo $records[$i]['site_designator'];?></td>
									<td><?php echo $records[$i]['aqs_no'];?></td>
									<td><?php echo $records[$i]['short_name'];?></td>
									<td><?php echo $records[$i]['formal_name'];?></td>
									<td><?php echo $records[$i]['address'];?></td>
									<td><?php echo $records[$i]['city'];?></td>
									<td><?php echo $records[$i]['zip'];?></td>
									<td><?php echo $records[$i]['latitude'];?></td>
									<td><?php echo $records[$i]['longitude'];?></td>
									<td><?php echo $records[$i]['notes'];?></td>
									<td><?php echo $records[$i]['cams_code'];?></td>
									<td><?php echo $records[$i]['doc'];?></td>
									<td><?php echo $records[$i]['interval'];?></td>
									<td><?php echo $records[$i]['units_code'];?></td>
									<td><?php echo $records[$i]['method_code'];?></td>
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