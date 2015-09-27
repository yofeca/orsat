<script>
	function deleteRecord(co_id){
		if(confirm("Are you sure you want to delete this record?")){
			formdata = "id="+co_id;
			jQuery.ajax({
				url: "<?php echo site_url(); echo $controller ?>/ajax_delete_tceq_component/"+co_id,
				type: "POST",
				data: formdata,
				dataType: "json",
				success: function(){
					$("#list-item-"+co_id).fadeOut(200);
					self.location = "<?php echo site_url(); echo $controller ?>";
				}
			});
			
		}
	}

	function deleteAllRecord(co_id){
		var chVal = $('input[name="'+co_id+'"]:checked').map(function(){
			return $(this).val();
		}).get();
		console.log(co_id);
		console.log(chVal);

		if(confirm("Are you sure you want to delete all selected records?")){
			formdata = {};
			formdata['chVal'] = chVal;
			data = JSON.stringify(formdata);
			id = co_id.replace('standard-','');
			jQuery.ajax({
				url: "<?php echo site_url(); echo $controller ?>/ajax_delete_all_tceq_component/"+id,
				type: "POST",
				data: formdata,
				dataType: "json",
				success: function(){
					$("#list-item-"+co_id).fadeOut(200);
					self.location = "<?php echo site_url(); echo $controller ?>";
				}
			});
		}
	}
</script>

<style type="text/css">
	.tab-content .panel ul{ padding-left: 0; }
	.tab-content .panel ul li{
		list-style-type: none;
		margin: 0 5px;
		padding: 5px; 
		font-size: 13px; 
		height: 2em;
		line-height: 1.2em;
		border-bottom: 1px solid #ccc;
	}
	.options{ float: right; width: 15%; }
  	.options a{ float: right; margin-left: 5px;}
  	.description{ float: left; width: 65%; }
	.list-group-item{ padding-top: 0; padding-bottom: 0; }
	.def{ background: #ccc; }
</style>
<?php 
?>
<div class="row">
	<div class="container-fluid">
		<div class="panel panel-default">
			<div class="panel-heading">Default Standard	Components</div>
			<div class="panel-body">
				<ul class="nav nav-tabs" role="tablist">
				    <li role="presentation" class="active"><a href="#lcs_cvs-tab" aria-controls="lcs_cvs-tab" role="tab" data-toggle="tab">Default LCS/CVS Components</a></li>
				    <li role="presentation"><a href="#rt-tab" aria-controls="rt-tab" role="tab" data-toggle="tab">Default Retention Time Components</a></li>
				</ul>
				
				<!-- Tab panes -->
				<div class="tab-content">
				    <div role="tabpanel" class="tab-pane active" id="lcs_cvs-tab">
						<div class="panel panel-default">
							<div class="panel-heading">LCS/CVS</div>
							<div class="panel-body">
								<div class="col-sm-6" id="lcs_cvs-standard-a">
									<div class="container-fluid">
										<div class="panel panel-default">
											<div class="panel-heading">Channel A <button class="btn btn-default btn-sm pull-right btn-add" style="margin-top: -6px" id="add-lcs_cvs-a" data-toggle="modal" >Add</button></div>
											<div class="panel-body">
												<ul>
													<?php
													$tlcs_cvs = count($lcs_cvs);
													for($i=0; $i<$tlcs_cvs; $i++){
														if($lcs_cvs[$i]['channel']=='A'){
													?>
														<li id="lcs_cvs-<?php echo htmlentitiesX($lcs_cvs[$i]['id']); ?>-<?php echo htmlentitiesX($lcs_cvs[$i]['sort']); ?>">
															<input type="checkbox" value="<?php echo htmlentitiesX($lcs_cvs[$i]['id']); ?>" name="standard-lcs_cvs-a" id="standard-lcs_cvs-a-<?php echo $lcs_cvs[$i]['id'];?>" style="float: left; margin-right: 5px; margin-top: 0;">
															<div class="description"><?php echo $lcs_cvs[$i]['component_name']; echo ($lcs_cvs[$i]['alias']) ? '[ '.$lcs_cvs[$i]['alias'].' ]': ""; ?></div>
															<div class="options">
																<a style='color: red; cursor:pointer; text-decoration: underline' onclick='deleteRecord("lcs_cvs-<?php echo htmlentitiesX($lcs_cvs[$i]['id']) ?>"); ' ><i class="fa fa-trash-o"></i></a>
																<a href="<?php echo site_url(); ?>airs_list/edit/<?php echo $lcs_cvs[$i]['airs_list_id']?>" ><i class="fa fa-pencil-square-o"></i></a>
															</div>
															
														</li>
														<?php
														}
													}
													?>
												</ul>
											</div><!--/.panel-body-->
											<div class="panel-footer">
												<input type="checkbox" class="standard-check-all" id="check-all-standard-lcs_cvs-a" style="float: left; margin-right: 5px;"> Check All
												<a style='color: red; cursor:pointer; text-decoration: none' onclick='deleteAllRecord("standard-lcs_cvs-a"); ' ><i class="fa fa-trash-o"></i> Delete All</a>
											</div>
										</div><!--/.panel-->
									</div><!--/.container-fluid-->
								</div><!--/.row-->

								<div class="col-sm-6" id="lcs_cvs-standard-b">
									<div class="container-fluid">
										<div class="panel panel-default">
											<div class="panel-heading">Channel B <button class="btn btn-default btn-sm pull-right btn-add" style="margin-top: -6px" id="add-lcs_cvs-b" data-toggle="modal" >Add</button></div>
											<div class="panel-body">
												<ul>
													<?php
													for($i=0; $i<$tlcs_cvs; $i++){
														if($lcs_cvs[$i]['channel']=='B'){
													?>
														<li id="lcs_cvs-<?php echo htmlentitiesX($lcs_cvs[$i]['id']); ?>-<?php echo htmlentitiesX($lcs_cvs[$i]['sort']); ?>">
															<input type="checkbox" value="<?php echo htmlentitiesX($lcs_cvs[$i]['id']); ?>" name="standard-lcs_cvs-b" id="standard-lcs_cvs-b-<?php echo $lcs_cvs[$i]['id'];?>" style="float: left; margin-right: 5px; margin-top: 0;">
															<div class="description"><?php echo $lcs_cvs[$i]['component_name']; echo ($lcs_cvs[$i]['alias']) ? '[ '.$lcs_cvs[$i]['alias'].' ]': ""; ?></div>
															<div class="options">
																<a style='color: red; cursor:pointer; text-decoration: underline' onclick='deleteRecord("lcs_cvs-<?php echo htmlentitiesX($lcs_cvs[$i]['id']) ?>"); ' ><i class="fa fa-trash-o"></i></a>
																<a href="<?php echo site_url(); ?>airs_list/edit/<?php echo $lcs_cvs[$i]['airs_list_id']?>" ><i class="fa fa-pencil-square-o"></i></a>
															</div>
															
														</li>
														<?php
														}
													}
													?>
												</ul>
											</div>
											<div class="panel-footer">
												<input type="checkbox" class="standard-check-all" id="check-all-standard-lcs_cvs-b" style="float: left; margin-right: 5px;"> Check All
												<a style='color: red; cursor:pointer; text-decoration: none' onclick='deleteAllRecord("standard-lcs_cvs-b"); ' ><i class="fa fa-trash-o"></i> Delete All</a>
											</div>
										</div><!--/.panel-->
									</div><!--/.container-fluid-->
								</div><!--/.row-->

							</div><!--/.panel-body-->
						</div><!--/.panel-->
				    </div><!--/.tab-lcs_cvs-->
				    <div role="tabpanel" class="tab-pane" id="rt-tab">
						<div class="panel panel-default">
							<div class="panel-heading">RT Standards</div>
							<div class="panel-body">
								<div class="col-sm-6" id="rt-standard-a">
									<div class="container-fluid">
										<div class="panel panel-default">
											<div class="panel-heading">Channel A <button class="btn btn-default btn-sm pull-right btn-add" style="margin-top: -6px" id="add-rts-a" data-toggle="modal" >Add</button></div>
											<div class="panel-body">
												<ul>
													<?php
													$trt = count($rt);
													for($i=0; $i<$trt; $i++){
														if($rt[$i]['channel']=='A'){
													?>
														<li id="rt-<?php echo htmlentitiesX($rt[$i]['id']); ?>-<?php echo htmlentitiesX($rt[$i]['sort']); ?>">
															<input type="checkbox" value="<?php echo htmlentitiesX($rt[$i]['id']); ?>" name="standard-rt-a" id="standard-rt-a-<?php echo $rt[$i]['id'];?>" style="float: left; margin-right: 5px; margin-top: 0;">
															<div class="description"><?php echo $rt[$i]['component_name']; echo ($rt[$i]['alias']) ? '[ '.$rt[$i]['alias'].' ]': ""; ?></div>
															<div class="options">
																<a style='color: red; cursor:pointer; text-decoration: underline' onclick='deleteRecord("rt-<?php echo htmlentitiesX($rt[$i]['id']) ?>"); ' ><i class="fa fa-trash-o"></i></a>
																<a href="<?php echo site_url(); ?>airs_list/edit/<?php echo $rt[$i]['airs_list_id']?>" ><i class="fa fa-pencil-square-o"></i></a>
															</div>
															
														</li>
														<?php
														}
													}
													?>
												</ul>
											</div><!--/.panel-body-->
											<div class="panel-footer">
												<input type="checkbox" class="standard-check-all" id="check-all-standard-rt-a" style="float: left; margin-right: 5px;"> Check All
												<a style='color: red; cursor:pointer; text-decoration: none' onclick='deleteAllRecord("standard-rt-a"); ' ><i class="fa fa-trash-o"></i> Delete All</a>
											</div>
										</div><!--/.panel-->
									</div><!--/.container-fluid-->
								</div><!--/.row-->

								<div class="col-sm-6" id="rt-standard-b">
									<div class="container-fluid">
										<div class="panel panel-default">
											<div class="panel-heading">Channel B <button class="btn btn-default btn-sm pull-right btn-add" style="margin-top: -6px" id="add-rts-b" data-toggle="modal" >Add</button></div>
											<div class="panel-body">
												<ul>
													<?php
													for($i=0; $i<$trt; $i++){
														if($rt[$i]['channel']=='B'){
													?>
														<li id="rt-<?php echo htmlentitiesX($rt[$i]['id']); ?>-<?php echo htmlentitiesX($rt[$i]['sort']); ?>">
															<input type="checkbox" value="<?php echo htmlentitiesX($rt[$i]['id']); ?>" name="standard-rt-b" id="standard-rt-b-<?php echo $rt[$i]['id'];?>" style="float: left; margin-right: 5px; margin-top: 0;">
															<div class="description"><?php echo $rt[$i]['component_name']; echo ($rt[$i]['alias']) ? '[ '.$rt[$i]['alias'].' ]': ""; ?></div>
															<div class="options">
																<a style='color: red; cursor:pointer; text-decoration: underline' onclick='deleteRecord("rt-<?php echo htmlentitiesX($rt[$i]['id']) ?>"); ' ><i class="fa fa-trash-o"></i></a>
																<a href="<?php echo site_url(); ?>airs_list/edit/<?php echo $rt[$i]['airs_list_id']?>" ><i class="fa fa-pencil-square-o"></i></a>
															</div>
															
														</li>
														<?php
														}
													}
													?>
												</ul>
											</div>
											<div class="panel-footer">
												<input type="checkbox" class="standard-check-all" id="check-all-standard-rt-b" style="float: left; margin-right: 5px;"> Check All
												<a style='color: red; cursor:pointer; text-decoration: none' onclick='deleteAllRecord("standard-rt-b"); ' ><i class="fa fa-trash-o"></i> Delete All</a>
											</div>
										</div><!--/.panel-->
									</div><!--/.container-fluid-->
								</div><!--/.row-->

							</div><!--/.panel-body-->
						</div><!--/.panel-->
				    </div><!--/.tab-rt-->
				</div><!--/.tab-contents-->
			</div>
			<div class="panel-footer"></div>
		</div><!--/.panel-->
	</div><!--/.container-fluid-->
</div><!--/.row-->

<div class="modal fade">
	<div class="modal-dialog" id="modal-standards">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Choose Default Components</h4>
			</div>
			<div class="modal-body">

				<ul id="list-standards">
					<li class="list-group-item"><input type="checkbox" class="check-all"> Check all</li>
				</ul>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="save">Save changes</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /#add-compound-a -->

<script>

	$('.btn-add').on('click',function(){
		std_array = ['ETHANE', 'PROPANE', 'N-BUTANE', 'ACETYLENE', 'N-PENTANE', '1,3-BUTADIENE', '2-METHYLPENTANE','N-HEXANE', 'BENZENE', 'TOLUENE', 'M&P-XYLENE', 'N-PROPYLBENZENE', '1,2,4-TRI-M-BENZENE'];
		$('.li-standards').remove();
		var id = $(this).attr('id').replace('add-','');
		formdata = "id="+id;
		console.log(formdata);
		jQuery.ajax({
			url: "<?php echo site_url(); echo $controller ?>/ajax_fetch_tceq_components/"+id,
			type: "POST",
			data: formdata,
			dataType: "json",
			success: function(data){
				for(i in data){
					def = '';
					if($.inArray(data[i].component_name,std_array) > -1){
						def = 'def';
					}
					$('#list-standards').append('<li class="list-group-item li-standards '+def+'"><input type="checkbox" name="standards" class="standards" id="'+id+'-'+data[i].airs_list_id+'" value="'+id+'-'+data[i].airs_list_id+'">'+data[i].component_name+'</li>');
				}

			}
		});

		$('.modal').modal('show');
	});

	$('.standard-check-all').on("click", function(){
		var id = $(this).attr('id').replace('check-all-','');
		$('input[name="'+id+'"]').prop('checked', ! $('input[name="'+id+'"]').is(':checked'));
	});

	$('.check-all').on("click", function(){
		$('input[name="standards"]').prop('checked', $(this).is(':checked'));
	});

	$('#save').on("click", function(){
		var chVal = $('input[name="standards"]:checked').map(function(){
			return $(this).val();
		}).get();

		addStandardComponents(chVal);
	});


	function addStandardComponents(chVal){
		$.post(
			'<?php echo site_url(); echo $controller; ?>/ajax_add_standard_components',
			{'data':chVal}
		).done(function(){
			self.location = "<?php echo site_url(); echo $controller; ?>";
		});
	}
</script>