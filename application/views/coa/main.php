<script>
$(document).ready(function(){
	$('input[name="add"]').on('click',function(){ coa.add(); });
	$('input[name="search"]').on('click',function(){ coa.search(); });
	$('.delete').on('click',function(){
		var id = $(this).attr('id').replace('rec-','');
		coa.delete(id);
	});
	$('.cylinder').on('click',function(){
		var id = $(this).attr('id').replace('cylinder-','');
		coa.fetchStandards(id);
	});
	var coa = {
		delete: function(co_id){
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
		},
		search: function(){
			if($('#sfilter').val()!=''){
				self.location = "<?php echo site_url(); ?><?php echo $controller; ?>/search/?search="+$("#search").val()+"&filter="+$("#sfilter").val();
			}else{
				$('.chosen-single').addClass('err-filter').focus();
			}
		},
		add: function(){
			self.location = "<?php echo site_url(); echo $controller; ?>/add";
		},
		fetchStandards: function(id){

			$('#lcs-components').DataTable({
				'sAjaxSource': '<?php echo site_url(); ?><?php echo $controller; ?>/ajax_fetch_standards?cylinder=' + id+'&type=lcs',
				"sAjaxDataProp": "",
				'destroy': true,
				'deferRender': true,
				'searching': false,
				'ordering': false,
				'paging': false,
				"aoColumns":[
					{"mData": "component_name"},
					{"mData": "value"}
				]
			});
			$('#cvs-components').DataTable({
				'sAjaxSource': '<?php echo site_url(); ?><?php echo $controller; ?>/ajax_fetch_standards?cylinder=' + id+'&type=cvs',
				"sAjaxDataProp": "",
				'destroy': true,
				'deferRender': true,
				'searching': false,
				'ordering': false,
				'paging': false,
				'aoColumns':[
					{'mData': 'component_name'},
					{'mData': 'value'}
				]
			});
		}
	};
/*					'deferRender': true,
				'searching': false,
				'ordering': false,
				'paging': false,*/
});
</script>
<div class="row content-filter">
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
							<option value="cylinder">Cylinder</option>	

							</select>
						</div>
					</div>
					<input type='button' class='btn btn-default' value='search' name="search">
					<input type='button' class='btn btn-default' value='add' name="add">
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
	</div><!--/.container-fluid-->
</div><!--/.content-filter-->

<div class="row">
	<div class="container-fluid">
		<div class="panel panel-default" id="panel-table">
			<div class="panel-heading">Cyliner</div>
			<div class="panel-body">
				<div class="col-sm-6">
					<div class="panel panel-default" id="panel-table">
						<div class="panel-heading" style="display: block;">Certificate of Analysis</div>
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table table-striped table-bordered" id="dt-wrapper">
									<thead>
										<tr>
											<th>Cylinder</th>
											<th></th>
										</tr>
									</thead>
									
									<tbody>
									<?php
									for($i=0; $i<$t; $i++){
										?>
										<tr id="tr<?php echo htmlentitiesX($records[$i]['id']); ?>">
											<td><a href="javascript:;" id="cylinder-<?php echo $records[$i]['id']?>" class="cylinder" ><?php echo $records[$i]['cylinder'];?></a></td>
											<td width='50px'>
												<a href="<?php echo site_url(); ?><?php echo $controller; ?>/edit/<?php echo $records[$i]['id']?>" ><i class="fa fa-pencil-square-o"></i></a>
												<a style='color: red; cursor:pointer;' id="rec-<?php echo htmlentitiesX($records[$i]['id']) ?>" class="delete" ><i class="fa fa-trash-o"></i></a>
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
						</div><!--/.panel-footer-->
					</div><!--/.panel-default-->
				</div><!--/.col-sm-6-->
				<div class="col-sm-6">
					<div class="row">
						<div class="container-fluid">
							<div class="panel panel-default">
								<div class="panel-heading" style="display: block;">Components</div>
								<div class="panel-body">
									<div clas="table-responsive">
										<table class="table table-striped table-bordered" id="lcs-components">
											<thead>
												<tr>
													<th>Name</th>
													<th>Standard Value</th>
												</tr>
											</thead>
										</table>
										<table class="table table-striped table-bordered" id="cvs-components">
											<thead>
												<tr>
													<th>Name</th>
													<th>Standard Value</th>
												</tr>
											</thead>
										</table>
									</div><!--/.table-responsive-->	
								</div><!--/.panel-body-->
							</div><!--/.panel-->
						</div><!--/.container-fluid-->
					</div><!--/.row-->
				</div>
			</div><!--/.panel-body-->
		</div><!--/.panel-->
	</div><!--/.container-fluid-->
</div><!--/.row-->
