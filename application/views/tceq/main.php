<script>
	$(function(){
		$(".sortable").sortable({
			placeholder: "ui-state-highlight",
			cancel: ".ui-state-disabled",
			items: 'li:not(.ui-state-disabled)'
		});
		$( ".sortable" ).disableSelection();

		$('#sortable-a').sortable({
			update: function(){
				var data = $(this).sortable('serialize');
				$.post(
					'<?php echo site_url(); ?><?php echo $controller; ?>/ajax_sortable',
					{'data':data}
				);
			}
		});
		$('#sortable-b').sortable({
			update: function(){
				var data = $(this).sortable('serialize');
				$.post(
					'<?php echo site_url(); ?><?php echo $controller; ?>/ajax_sortable',
					{'data':data}
				);
			}
		});
	});

	function deleteRecord(co_id){
		if(confirm("Are you sure you want to delete this record?")){
			formdata = "id="+co_id;
			jQuery.ajax({
				url: "<?php echo site_url(); echo $controller ?>/ajax_delete_tceq_component/"+co_id,
				type: "POST",
				data: formdata,
				dataType: "json",
				success: function(){
					jQuery("#list-item-"+co_id).fadeOut(200);
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
				url: "<?php echo site_url(); echo $controller ?>/ajax_delete_all_tceq_component/"+id.replace(/\-/g,'_'),
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
	.sortable { list-style-type: none; margin: 0; padding: 0; }
	.sortable li { 
		margin: 0 5px;
		padding: 5px; 
		font-size: 13px; 
		height: 2em;
		line-height: 1.2em;
		border: 1px solid #ccc;
	}
  	.ui-state-highlight { height: 2em; line-height: 1.2em; }
  	.airs-id{ float: left; width: 15%; }
  	.description{ float: left; width: 65%; }
  	.options{ float: right; width: 15%; }
  	.options a{ float: right; margin-left: 5px;}
  	.sortable li > div{ box-sizing: border-box; }
  	.list-group-item{ padding-top: 0; padding-bottom: 0; }
  	.ui-state-disabled{ background: #333333 !important; }
  	.ui-state-disabled button{ float: right; }
</style>

<div class="row">
	<div class="container-fluid">
		<div class="panel panel-default">
			<div class="panel-heading">State Of Texas Components</div>
			<div class="panel-body">
				<div class="alert alert-success alert-dismissible" role="alert">
                  	<button class="close" type="button" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                  	This page is used to set the <strong>default target components</strong> to be used by all networks as default components.
                </div>
				<div class="col-sm-6">
					<div class="panel panel-default">
						<div class="panel-heading">Channel A Components <button class="btn btn-default btn-sm pull-right" style="margin-top: -6px" id="add-a" data-toggle="modal" data-target="#add-compound-a">Add</button></div>
						<div class="panel-body">
							<ul id="sortable-a" class="sortable">
								<?php
								$t = count($tceq_a);
								for($i=0; $i<$t; $i++){
								?>
									<li id="list-item-<?php echo htmlentitiesX($tceq_a[$i]['id']); ?>">
										<input type="checkbox" value="<?php echo htmlentitiesX($tceq_a[$i]['id']); ?>" name="tceq-a" id="tceq-a-<?php echo $tceq_a[$i]['id'];?>" style="float: left; margin-right: 5px; margin-top: 0;">
										<i class="fa fa-bars pull-left" style="cursor: grab"></i> 
										<div class="description"><?php echo $tceq_a[$i]['component_name']; echo ($tceq_a[$i]['alias']) ? '[ '.$tceq_a[$i]['alias'].' ]': ""; ?></div>
										<div class="options">
											<a style='color: red; cursor:pointer; text-decoration: underline' onclick='deleteRecord("<?php echo htmlentitiesX($tceq_a[$i]['id']) ?>"); ' ><i class="fa fa-trash-o"></i></a>
											<a href="<?php echo site_url(); ?>airs_list/edit/<?php echo $tceq_a[$i]['id']?>" ><i class="fa fa-pencil-square-o"></i></a>
										</div>
										
									</li>
									<?php
								}
								?>
							</ul>
						</div>
						<div class="panel-footer">
							<input type="checkbox" class="tceq-check-all" id="check-all-tceq-a" style="float: left; margin-right: 5px;"> Check All
							<a style='color: red; cursor:pointer; text-decoration: none' onclick='deleteAllRecord("tceq-a"); ' ><i class="fa fa-trash-o"></i> Delete selected</a>
						</div>
					</div><!--/.panel-->
				</div><!--/.col-sm-6-->
				<div class="col-sm-6">
					<div class="panel panel-default">
						<div class="panel-heading">Channel B Components <button class="btn btn-default btn-sm pull-right" style="margin-top: -6px" id="add-b" data-toggle="modal" data-target="#add-compound-b">Add</button></div>
						<div class="panel-body">
							<ul id="sortable-b" class="sortable">
								<?php
								
								$t = count($tceq_b);
								for($i=0; $i<$t; $i++){
								?>
									<li id="list-item-<?php echo htmlentitiesX($tceq_b[$i]['id']); ?>">
										<input type="checkbox" value="<?php echo htmlentitiesX($tceq_b[$i]['id']); ?>" name="tceq-b" id="tceq-b-<?php echo $tceq_b[$i]['id'];?>" style="float: left; margin-right: 5px; margin-top: 0;">
										<i class="fa fa-bars pull-left" style="cursor: grab"></i> 
										<div class="description"><?php echo $tceq_b[$i]['component_name']; echo ($tceq_b[$i]['alias']) ? '[ '.$tceq_b[$i]['alias'].' ]': ""; ?></div>
										<div class="options">
											<a style='color: red; cursor:pointer; text-decoration: underline' onclick='deleteRecord("<?php echo htmlentitiesX($tceq_b[$i]['id']) ?>"); ' ><i class="fa fa-trash-o"></i></a>
											<a href="<?php echo site_url(); ?>airs_list/edit/<?php echo $tceq_b[$i]['id']?>" ><i class="fa fa-pencil-square-o"></i></a>
										</div>
										
									</li>
									<?php
								}
								?>
							</ul>
						</div>
						<div class="panel-footer">
							<input type="checkbox" class="tceq-check-all" id="check-all-tceq-b" style="float: left; margin-right: 5px;"> Check All
							<a style='color: red; cursor:pointer; text-decoration: none' onclick='deleteAllRecord("tceq-b"); ' ><i class="fa fa-trash-o"></i> Delete Selected</a>
						</div>
					</div><!--/.panel-->
				</div><!--/.col-sm-6-->
			</div>
			<div class="panel-footer"></div>
		</div><!--/.panel-->
	</div><!--/.container-fluid-->
</div><!--/.row-->

<div class="modal fade" id="add-compound-a">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Add Airs List Compounds</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<input class="form-control" id="searchinput-a" type="search" placeholder="Search..." />
				</div>
				<ul class="list-group" id="modal-components-a">
					<li class="list-group-item"><input type="checkbox" class="check-all-a"> Check all</li>
					<?php 
						$t = count($airsfile_a);

						for($i=0; $i<$t; $i++){
							?>
							<li class="list-group-item">
								<input type="checkbox" name="li-a" id="li-a-<?php echo $airsfile_a[$i]['id']?>" value="<?php echo $airsfile_a[$i]['id'] .'_'.$airsfile_a[$i]['print_name'] .'_A';?>">
								<?php echo $airsfile_a[$i]['aqi_no'] . ' - <span>' .$airsfile_a[$i]['component_name'] . '</span>'; ?>
							</li>
							<?php
						}
					?>
				</ul>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="savea">Save changes</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /#add-compound-a -->

<div class="modal fade" id="add-compound-b">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Add Airs List Compounds</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<input class="form-control" id="searchinput-b" type="search" placeholder="Search..." />
				</div>
				<ul class="list-group" id="modal-components-b">
					<li class="list-group-item"><input type="checkbox" class="check-all-b"> Check all</li>
					<?php 
						$t = count($airsfile_b);

						for($i=0; $i<$t; $i++){
							?>
							<li class="list-group-item">
								<input type="checkbox" name="li-b" id="li-b-<?php echo $airsfile_b[$i]['id']?>" value="<?php echo $airsfile_b[$i]['id'] .'_'.$airsfile_b[$i]['print_name'] .'_B';?>">
								<?php echo $airsfile_b[$i]['aqi_no'] . ' - <span>' .$airsfile_b[$i]['component_name'] .'</span>'; ?>
							</li>
							<?php
						}
					?>
				</ul>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="saveb">Save changes</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /#add-compound-b -->

<script>
	$('#modal-components-a').btsListFilter('#searchinput-a', {itemChild: 'span'});
	$('#modal-components-b').btsListFilter('#searchinput-b', {itemChild: 'span'});

	$('.tceq-check-all').on("click", function(){
		var id = $(this).attr('id').replace('check-all-','');
		$('input[name="'+id+'"]').prop('checked', ! $('input[name="'+id+'"]').is(':checked'));
	});

	$('.check-all-a').on("click", function(){
		$('input[name="li-a"]').prop('checked', $(this).is(':checked'));
	});
	$('.check-all-b').on("click", function(){
		$('input[name="li-b"]').prop('checked',$(this).is(':checked'));
	});

	$('#savea').on("click", function(){
		var chVal = $('input[name="li-a"]:checked').map(function(){
			return $(this).val();
		}).get();
		addAirsCompound(chVal);
	});
	$('#saveb').on("click", function(){
		var chVal = $('input[name="li-b"]:checked').map(function(){
			return $(this).val();
		}).get();
		addAirsCompound(chVal);
	});
	function addAirsCompound(chVal){
		$.post(
			'<?php echo site_url(); echo $controller; ?>/ajax_add_tceq_components',
			{'data':chVal}
		).done(function(){
			self.location = "<?php echo site_url(); echo $controller; ?>";
		});
	}
</script>