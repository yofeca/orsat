<?php
@session_start();
$sid = session_id()."_".time();
?>
<script>

function saveRecord(approve){	
	extra = "";
	jQuery("#savebutton").val("Saving...");
	formdata = jQuery("#record_form").serialize();
	jQuery("#record_form *").attr("disabled", true);
	jQuery.ajax({
		<?php
		if($header['id']){
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

$(document).ready(function(){
	$('.toggle-row').on('click', function(){
		var elem = $(this).attr('id');
		if($('#'+elem).prop('checked')){
			$('.'+elem).fadeOut(400);
		}else{
			$('.'+elem).fadeIn(400);
		}
	});

	mask_values('.amount','td-not-detected','ND');
	mask_values('.time','td-zero','');
	mask_values('.area','td-zero','');
	mask_values('.method-rt','td-zero','');
});

</script>

<style>
	.table-footer td{ font-weight: bold; background: #ddd; }
</style>
<input type='hidden' id='tempcreatelabel' />
<div class="row">
	<div class="col-sm-5" id="txo-field-info">
		<div class="panel panel-default">
			<div class="panel-heading">
				<?php
					if(!$header['id']){
						echo "Header (Add a New Record)";
					}else{
						echo "Header (Edit Record)";
					}
				?>
			</div>
			<div class="panel-body">
				<form id='record_form' class="form-horizontal" role="form">
					<?php
					if($header['id']){
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
							<label for="data" class="col-sm-4 control-label">Filename</label>
							<div class="col-sm-8">
								<input type="text" name="filename" size="40" class="form-control" placeholder="Enter Date">
							</div>
						</div>
					<div class="form-group">
							<label for="data" class="col-sm-4 control-label">Date</label>
							<div class="col-sm-8">
								<input type="text" name="date" size="40" class="form-control" placeholder="Enter Date">
							</div>
						</div>
						<!--div class="form-group">
							<label for="sample_type_id" class="col-sm-4 control-label">Sample ID</label>
							<div class="col-sm-8">
								<input type="text" name="sample_type_id" size="40" class="form-control" placeholder="Enter Sample ID">
							</div>
						</div-->
						<div class="form-group">
							<label for="sample_name" class="col-sm-4 control-label">Sample Name</label>
							<div class="col-sm-8">
								<input type="text" name="sample_name" size="40" class="form-control" placeholder="Enter Sample Name">
							</div>
						</div><div class="form-group">
							<label for="sample_number" class="col-sm-4 control-label">Sample Number</label>
							<div class="col-sm-8">
								<input type="text" name="sample_number" size="40" class="form-control" placeholder="Enter Sample Number">
							</div>
						</div>
						<!--div class="form-group">
							<label for="site_id" class="col-sm-4 control-label">Site ID</label>
							<div class="col-sm-8">
								<input type="text" name="site_id" size="40" class="form-control" placeholder="Enter Site ID">
							</div>
						</div-->
						<div class="form-group">
							<label for="instrument_name" class="col-sm-4 control-label">Instrument Name</label>
							<div class="col-sm-8">
								<input type="text" name="instrument_name" size="40" class="form-control" placeholder="Enter Instrument Name">
							</div>
						</div><div class="form-group">
							<label for="channel" class="col-sm-4 control-label">Channel</label>
							<div class="col-sm-8">
								<input type="text" name="channel" size="40" class="form-control" placeholder="Enter Channel">
							</div>
						</div><div class="form-group">
							<label for="data_acquisition_time" class="col-sm-4 control-label">Data Acquisition Time</label>
							<div class="col-sm-8">
								<input type="text" name="data_acquisition_time" size="40" class="form-control" placeholder="Enter Data Acquisition Time">
							</div>
						</div><div class="form-group">
							<label for="cycle" class="col-sm-4 control-label">Cycle</label>
							<div class="col-sm-8">
								<input type="text" name="cycle" size="40" class="form-control" placeholder="Enter Cycle">
							</div>
						</div><div class="form-group">
							<label for="raw_data_file" class="col-sm-4 control-label">Raw Data File</label>
							<div class="col-sm-8">
								<input type="text" name="raw_data_file" size="40" class="form-control" placeholder="Enter Raw Data File">
							</div>
						</div><div class="form-group">
							<label for="inst_method" class="col-sm-4 control-label">Instrument Method</label>
							<div class="col-sm-8">
								<input type="text" name="inst_method" size="40" class="form-control" placeholder="Enter Instrument Method">
							</div>
						</div><div class="form-group">
							<label for="sequence_file" class="col-sm-4 control-label">Sequence File</label>
							<div class="col-sm-8">
								<input type="text" name="sequence_file" size="40" class="form-control" placeholder="Enter Sequence File">
							</div>
						</div><div class="form-group">
							<label for="noise_threshold" class="col-sm-4 control-label">Noise Treshold</label>
							<div class="col-sm-8">
								<input type="text" name="noise_threshold" size="40" class="form-control" placeholder="Enter Noise Treshold">
							</div>
						</div><div class="form-group">
							<label for="area_threshold" class="col-sm-4 control-label">Area Treshold</label>
							<div class="col-sm-8">
								<input type="text" name="area_threshold" size="40" class="form-control" placeholder="Enter Area Treshold">
							</div>
						</div><div class="form-group">
							<label for="bunch_factor" class="col-sm-4 control-label">Bunch Factor</label>
							<div class="col-sm-8">
								<input type="text" name="bunch_factor" size="40" class="form-control" placeholder="Enter Bunch Factor">
							</div>
						</div>

					<div class="form-group">
						<div class="col-sm-offset-4 col-sm-8">
							<input type="button" id='savebutton' value="Save" onclick="saveRecord()" class="btn btn-default"/>
							<?php 
							if($header['id']){ ?>
								<input type="button" class="btn btn-default" style='background:red; color:white' value="Delete" onclick="deleteRecord('<?php echo $header['id']; ?>')" />
							<?php
							} ?>
							<a href="<?php echo site_url(); echo $controller ?>" class="btn btn-default pull-right">Back</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-sm-7">
		<div class="row">
			<div class="container-fluid">
				<div class="panel panel-default">
					<div class="panel-heading">Components</div>
					<div class="panel-body">
						<div class="col-sm-12 table-responsive">
							<ul class="list-group">
								<li class="list-group-item">
									<b>ND</b> - None Detected
								</li>
								<li class="list-group-item">
		                            <label class="cr-styled">
		                            	<input type="checkbox" name="toggle-no-peaks" id="no-peak" class="toggle-row">
		                                <i class="fa"></i>
		                            </label>
		                            <span>Toggle No Peaks</span>
	                            </li>
	                            <li class="list-group-item">
		                            <label class="cr-styled">
		                            	<input type="checkbox" name="toggle-no-names" id="no-name" class="toggle-row">
		                                <i class="fa"></i>
		                            </label>
		                            <span>Toggle No Component Names</span>
	                            </li>
							</ul>
						<table class="table table-striped table-bordered">
							<thead>
							<tr>
								<th>Peak #</th>
								<th>Component Name</th>
								<th>Amount (PPB Carbon)</th>
								<th>Time</th>
								<th>Area</th>
								<th>Method RT</th>
							</tr>
							</thead>
							<tbody>
								<?php 
									$tc = count($components); 
									for($i=0; $i<$tc; $i++){
										?>
										<tr class="<?php if(!$components[$i]['peak']) echo 'no-peak'; else if(!$components[$i]['component_name']) echo 'no-name'; ?>">
										<td class="peak"><?php echo ($components[$i]['peak']) ? ($components[$i]['peak']):'-'; ?></td>
										<td class="component"><?php echo $components[$i]['component_name']; ?></td>
										<td class="amount"><?php echo $components[$i]['amount']; ?></td>
										<td class="time"><?php echo $components[$i]['time']; ?></td>
										<td class="area"><?php echo $components[$i]['area']; ?></td>
										<td class="method-rt"><?php echo $components[$i]['method_rt']; ?></td>
										</tr>
										<?php
									}
								?>
									<tr class="table-footer">
										<td colspan="2">TOTAL: </td>
										<td><?php echo $total_components['pp_carbon']?></td>
										<td><?php echo $total_components['time']?></td>
										<td><?php echo $total_components['area']?></td>
										<td><?php echo $total_components['method_rt']?></td>
									</tr>
									<tr class="table-footer">
										<td colspan="6">Ascii File: <?php echo $total_components['ascii_file']; ?></td>
									</tr>
							</tbody>
						</table>
						</div>
					</div>
				</div><!--/.panel-->
			</div><!--/.container-fluid-->
		</div><!--/.row-->
	</div>
</div>

<script>
<?php
	if($header){
		foreach($header as $key=>$value){	
			if(trim($value)||1){
				?>
				jQuery('[name="<?php echo $key; ?>"]').val("<?php echo sanitizeX($value); ?>");
				<?php
			}		
		}
	}
	?>
</script>
