<?php
@session_start();
$sid = session_id()."_".time();
?>
<script>
function saveRecord(approve){
	var name = $('input[name="name"]');

	if(name.val().length < 1){
		name.focus();
		return;
	}

	var formdata = {};
	extra = "";
	jQuery("#savebutton").val("Saving...");
	formdata['form'] = jQuery("#record_form").serialize();
	console.log(formdata['form']);
	jQuery("#record_form *").attr("disabled", true);
	
	var itemsa = [];
	var itemsb = [];

	$('#sortable-a .list-items').each(function(){
		var id = $(this).attr('id').replace('list-item-','');
		itemsa.push(id);
	});
	$('#sortable-b .list-items').each(function(){
		var id = $(this).attr('id').replace('list-item-','');
		itemsb.push(id);
	});
	
	formdata['itemsa'] = itemsa;
	formdata['itemsb'] = itemsb;

	console.log(itemsa);
	console.log(itemsb);

	jQuery.ajax({
		<?php
		if($record['id']){
			?>url: "<?php  echo site_url(); echo $controller ?>/ajax_edit"+extra,<?php
		}
		else{
			?>url: "<?php echo site_url(); echo $controller ?>/ajax_add"+extra,<?php
		}
		?>
		type: "POST",
		data: formdata,
		dataType: "script",
		success: function(data){
			//alert(data);
		}
	});	
	
}
function deleteRecord(co_id){
	if(confirm("Are you sure you want to delete this record?")){
		formdata = "id="+co_id;
		jQuery.ajax({
			url: "<?php echo site_url(); echo $controller ?>/ajax_delete/"+co_id,
			type: "POST",
			data: formdata,
			dataType: "script",
			success: function(){
				jQuery("#tr"+co_id).fadeOut(200);
				self.location = "<?php echo site_url(); echo $controller ?>";
			}
		});
	}
}

function removeAllRecords(co_id){

	var e = '<?php echo $event; ?>';
	console.log(co_id);
	if(e=='add'){
		$('input[name="'+co_id+'"]:checked').each(function(){
			$(this).parent().remove();
		});
	}else{
		var chVal = $('input[name="'+co_id+'"]:checked').map(function(){
			return $(this).val();
		}).get();
		console.log(co_id);
		console.log(chVal);

		if(confirm("Are you sure you want to delete all selected records?")){
			formdata = {};
			formdata['chVal'] = chVal;
			data = JSON.stringify(formdata);
			id = co_id.replace('target-','');
			jQuery.ajax({
				url: "<?php echo site_url(); echo $controller ?>/ajax_remove_all_target_component/"+'<?php echo $record['id']; ?>',
				type: "POST",
				data: formdata,
				dataType: "json",
				success: function(){
					$("#list-item-"+co_id).fadeOut(200);
					self.location = "<?php echo site_url(); echo $controller ?>/edit/<?php echo $network_id; ?>";
				}
			});
		}
	}
}
</script>

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
					'<?php echo site_url(); ?><?php echo $controller; ?>/ajax_target_component_sortable/<?php echo $network_id; ?>',
					{'data':data}
				);
			}
		});
		$('#sortable-b').sortable({
			update: function(){
				var data = $(this).sortable('serialize');
				$.post(
					'<?php echo site_url(); ?><?php echo $controller; ?>/ajax_target_component_sortable/<?php echo $network_id; ?>',
					{'data':data}
				);
			}
		});
	});

	function removeTCEQ(co_id){
		var e = '<?php echo $event; ?>';

		if(e=='add'){
			id = $("#list-item-"+co_id).parent().attr('id');
			$('#add-compound-'+id+' ul.list-group').append('<li class="list-group-item">'+
				'<input type="checkbox" name="'+id+'" id="li-a-'+co_id+'" value="'+co_id+'">'+
				$("#list-item-"+co_id+" .description").html()+'</li>'
			);
			$("#list-item-"+co_id).remove();
		}else{
			if(confirm("Are you sure you want to delete this record?")){
				formdata = "aid="+co_id
				jQuery.ajax({
					url: "<?php echo site_url(); echo $controller ?>/ajax_remove_target_component/<?php echo $network_id; ?>",
					type: "POST",
					data: formdata,
					dataType: "json",
					success: function(){
						self.location = "<?php echo site_url(); echo $controller ?>/edit/<?php echo $network_id; ?>";
					}
				});
			}
		}
	}

</script>

<style type="text/css">
	.sortable { list-style-type: none; margin: 0; padding: 0; }
	.sortable li { 
		margin: 0 5px;
		padding: 5px; 
		font-size: 11px; 
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
<input type='hidden' id='tempcreatelabel' />
<div class="row">
	<div class="col-sm-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<?php
					if(!$record['id']){
						echo "Add a New Record";
					}else{
						echo "Edit Record";
					}
				?>
			</div>
			<div class="panel-body">
				<form id='record_form' class="form-horizontal" role="form">
					<?php
					if($record['id']){
						?>
						<input type='hidden' name='id' id='co_id'  value="" />
						<?php
					}
					else{
						?>
						<input type='hidden' name='sid' value="<?php echo sanitizeX($sid); ?>">
						<?php
					}
					?>

					<div class="form-group">
							<label for="name" class="col-sm-4 control-label">Name</label>
							<div class="col-sm-8">
								<input type="text" name="name" size="40" class="form-control" placeholder="Enter Name">
							</div>
						</div>

					<div class="form-group">
						<div class="col-sm-offset-4 col-sm-8">
							<input type="button" id='savebutton' value="Save" onclick="saveRecord()" class="btn btn-default"/>
							<?php 
							if($record['id']){ ?>
								<input type="button" class="btn btn-default" style='background:red; color:white' value="Delete" onclick="deleteRecord('<?php echo $record['id']; ?>')" />
							<?php
							} ?>
							<a href="<?php echo site_url(); echo $controller ?>" class="btn btn-default pull-right">Back</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div><!--/.col-sm-4-->
	<div class="col-sm-8">
		<div class="row">
			<div class="container-fluid">
				<div class="panel panel-default">
					<div class="panel-heading">Network Target Components</div>
					<div class="panel-body">
						<div class="col-sm-6">
							<div class="panel panel-default">
								<div class="panel-heading">Channel A Components <?php if($network_id){ ?> <button class="btn btn-default btn-sm pull-right" style="margin-top: -6px" id="add-a" data-toggle="modal" data-target="#add-compound-sortable-a">Add</button><?php } ?></div>
								<div class="panel-body">
									<ul id="sortable-a" class="sortable">
										<?php
										$t = count($target_a);
										for($i=0; $i<$t; $i++){
										?>
											<li id="list-item-<?php echo htmlentitiesX($target_a[$i]['id']); ?>" class="list-items">
												<input type="checkbox" value="<?php echo htmlentitiesX($target_a[$i]['id']); ?>" name="target_a" id="target_a-<?php echo $target_a[$i]['id'];?>" style="float: left; margin-right: 5px; margin-top: 0;">
												<i class="fa fa-bars pull-left" style="cursor: grab"></i> 
												<div class="description"><?php echo $target_a[$i]['component_name']; ?></div>
												<div class="options">
													<a style='color: red; cursor:pointer; text-decoration: underline' onclick='removeTCEQ("<?php echo htmlentitiesX($target_a[$i]['id']) ?>"); ' ><i class="fa fa-trash-o"></i></a>
													<a href="<?php echo site_url(); ?>airs_list/edit/<?php echo (isset($target_a[$i]['id'])) ? $target_a[$i]['id'] : $target_a[$i]['id']; ?>" ><i class="fa fa-pencil-square-o"></i></a>
												</div>
												
											</li>
											<?php
										}
										?>
									</ul>
								</div>
								<div class="panel-footer">
									<input type="checkbox" class="check-all" id="check-all-target_a" style="float: left; margin-right: 5px;"> Check All
									<a style='color: red; cursor:pointer; text-decoration: none' onclick='removeAllRecords("target_a"); ' ><i class="fa fa-trash-o"></i> Delete All</a>
								</div>
							</div><!--/.panel-->
						</div><!--/.col-sm-6-->
						<div class="col-sm-6">
							<div class="panel panel-default">
								<div class="panel-heading">Channel B Components <?php if($network_id){ ?> <button class="btn btn-default btn-sm pull-right" style="margin-top: -6px" id="add-b" data-toggle="modal" data-target="#add-compound-sortable-b">Add</button> <?php } ?></div>
								<div class="panel-body">
									<ul id="sortable-b" class="sortable">
										<?php
										$t = count($target_b);
										for($i=0; $i<$t; $i++){
										?>
											<li id="list-item-<?php echo htmlentitiesX($target_b[$i]['id']); ?>" class="list-items">
												<input type="checkbox" value="<?php echo htmlentitiesX($target_b[$i]['id']); ?>" name="target_b" id="target_b-<?php echo $target_b[$i]['id'];?>" style="float: left; margin-right: 5px; margin-top: 0;">
												<i class="fa fa-bars pull-left" style="cursor: grab"></i> 
												<div class="description"><?php echo $target_b[$i]['component_name']; ?></div>
												<div class="options">
													<a style='color: red; cursor:pointer; text-decoration: underline' onclick='removeTCEQ("<?php echo htmlentitiesX($target_b[$i]['id']) ?>"); ' ><i class="fa fa-trash-o"></i></a>
													<a href="<?php echo site_url(); ?>airs_list/edit/<?php echo $target_b[$i]['id']?>" ><i class="fa fa-pencil-square-o"></i></a>
												</div>
												
											</li>
											<?php
										}
										?>
									</ul>
								</div>
								<div class="panel-footer">
									<input type="checkbox" class="check-all" id="check-all-target_b" style="float: left; margin-right: 5px;"> Check All
									<a style='color: red; cursor:pointer; text-decoration: none' onclick='removeAllRecords("target_b"); ' ><i class="fa fa-trash-o"></i> Delete All</a>
								</div>
							</div><!--/.panel-->
						</div><!--/.col-sm-6-->
					</div>
					<div class="panel-footer"></div>
				</div><!--/.panel-->
			</div><!--/.container-fluid-->
		</div><!--/.row-->
	</div><!--/.col-sm-8-->
</div><!--/.row-->

<script>
<?php
	if($record){
		foreach($record as $key=>$value){	
			if(trim($value)||1){
				?>
				jQuery('[name="<?php echo $key; ?>"]').val("<?php echo sanitizeX($value); ?>");
				<?php
			}		
		}
	}
	?>
</script>

<div class="modal fade" id="add-compound-sortable-a">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Add TCEQ Components</h4>
			</div>
			<div class="modal-body">

				<ul class="list-group">
					<li class="list-group-item"><input type="checkbox" class="check-all" id="check-all-a"> Check all</li>
					<?php 
						$t = count($tceq_a);
						for($i=0; $i<$t; $i++){
							?>
							<li class="list-group-item">
								<input type="checkbox" name="li-a" id="li-a-<?php echo $tceq_a[$i]['id']?>" value="<?php echo $tceq_a[$i]['id'] ."-" . $tceq_a[$i]['sort']; ?>">
								<?php echo $tceq_a[$i]['aqi_no'] . ' - ' .$tceq_a[$i]['component_name']; ?>
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

<div class="modal fade" id="add-compound-sortable-b">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Add TCEQ Components</h4>
			</div>
			<div class="modal-body">

				<ul class="list-group">
					<li class="list-group-item"><input type="checkbox" class="check-all" id="check-all-b"> Check all</li>
					<?php
						$t = count($tceq_b);

						for($i=0; $i<$t; $i++){
							?>
							<li class="list-group-item">
								<input type="checkbox" name="li-b" id="li-b-<?php echo $tceq_b[$i]['id']?>" value="<?php echo $tceq_b[$i]['id'] ."-" . $tceq_b[$i]['sort']; ?>">
								<?php echo $tceq_b[$i]['aqi_no'] . ' - ' .$tceq_b[$i]['component_name']; ?>
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
	$('.check-all').on("click", function(){
		var id = $(this).attr('id').replace('check-all-','');
		console.log(id);
		if(id=='target_a' || id=='target_b'){
			$('input[name="'+id+'"]').prop('checked', ! $('input[name="'+id+'"]').is(':checked'));
		}
		else{
			$('input[name="li-'+id+'"]').prop('checked', ! $('input[name="li-'+id+'"]').is(':checked'));
		}
	});
	$('.check-all-a').on("click", function(){
		$('input[name="sortable-a"]').prop('checked',$(this).is(':checked'));
	});
	$('.check-all-b').on("click", function(){
		$('input[name="sortable-b"]').prop('checked',$(this).is(':checked'));
	});

	$('#savea').on("click", function(){
		var chVal = $('input[name="li-a"]:checked').map(function(){
			return $(this).val();
		}).get();
		console.log(chVal);

		addTargetComponents(chVal);
	});
	$('#saveb').on("click", function(){
		var chVal = $('input[name="li-b"]:checked').map(function(){
			return $(this).val();
		}).get();
		console.log(chVal);

		addTargetComponents(chVal);
	});

	function addTargetComponents(chVal){
		$.post(
			'<?php echo site_url(); echo $controller; ?>/ajax_add_target_components/<?php echo $network_id; ?>',
			{'data':chVal}
		).done(function(){
			self.location = "<?php echo site_url(); echo $controller ?>/edit/<?php echo $network_id; ?>";
		});
	}
</script>
