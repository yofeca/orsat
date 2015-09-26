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
							<option value="site_name">Site Name</option>	
							<option value="validator">Validator</option>	
							<option value="operator">Operator</option>	
							<option value="data_validated_thru">Data Validated Thru</option>	
							<option value="channel_a_rf">Channel A RF</option>	
							<option value="channel_b_rf">Channel B RF</option>	

							</select>
						</div>
					</div>
					<input type='button' class='btn btn-default' value='search' onclick='searchRecord()'>
					<!--input type='button' class='btn btn-default' value='add' onclick='addRecord()'-->
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
								<th>Site Name</th>
								<th>Validator</th>
								<th>Operator</th>
								<th>Data Validated Thru</th>
								<th>Channel A RF</th>
								<th>Channel B RF</th>
								<th></th>
							</tr>
						</thead>
						
						<tbody>
						<?php
						for($i=0; $i<$t; $i++){
							?>
							<tr id="tr<?php echo htmlentitiesX($records[$i]['id']); ?>">
								<td><a href="<?php echo site_url(); ?>sites/edit/<?php echo $records[$i]['site_id'];?>" ><?php echo $records[$i]['site_name'];?></a></td>
								<td><?php echo $records[$i]['validator'];?></td>
								<td><?php echo $records[$i]['operator'];?></td>
								<td><?php echo $records[$i]['data_validated_thru'];?></td>
								<td><?php echo $records[$i]['channel_a_rf'];?></td>
								<td><?php echo $records[$i]['channel_b_rf'];?></td>
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
