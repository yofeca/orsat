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
</script>

<input type='hidden' id='tempcreatelabel' />
<div class="row">
	<div class="col-sm-12">
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
				<div class="col-sm-5 site-info">
					<div class="panel panel-default">
						<div class="panel-heading">Site Information</div>
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
										<label for="instrument_name" class="col-sm-4 control-label">Instrument Name</label>
										<div class="col-sm-8">
											<input type="text" name="instrument_name" size="40" class="form-control" placeholder="Enter Instrument Name">
										</div>
								</div>
								<div class="form-group">
									<label for="network" class="col-sm-4 control-label">Network</label>
									<div class="col-sm-8">
										<select name="network" class="chosen-select" data-placeholder="Choose Network">
											<option></option>
											<?php
												$t = count($networks);												
												for($i=0; $i<$t; $i++){
													echo '<option value="'.$networks[$i]['id'].'">'.$networks[$i]['name'].'</option>';
												}
											?>
										</select>
										<input type="hidden" name="network_id">
									</div>
								</div>
								<div class="form-group">
										<label for="site_designator" class="col-sm-4 control-label">Site Designator</label>
										<div class="col-sm-8">
											<input type="text" name="site_designator" size="40" class="form-control" placeholder="Enter Site Designator">
										</div>
								</div>
								<div class="form-group">
										<label for="aqs_no" class="col-sm-4 control-label">AQS Number</label>
										<div class="col-sm-8">
											<input type="text" name="aqs_no" size="40" class="form-control" placeholder="Enter AQS Number">
										</div>
								</div>
								<div class="form-group">
										<label for="short_name" class="col-sm-4 control-label">Short Name</label>
										<div class="col-sm-8">
											<input type="text" name="short_name" size="40" class="form-control" placeholder="Enter Short Name">
										</div>
								</div>
								<div class="form-group">
										<label for="formal_name" class="col-sm-4 control-label">Formal Name</label>
										<div class="col-sm-8">
											<input type="text" name="formal_name" size="40" class="form-control" placeholder="Enter Formal Name">
										</div>
								</div>
								<div class="form-group">
									<label for="address" class="col-sm-4 control-label">Address</label>
									<div class="col-sm-8">
										<input type="text" name="address" size="40" class="form-control" placeholder="Enter Address">
									</div>
								</div>
								<div class="form-group">
									<label for="city" class="col-sm-4 control-label">City</label>
									<div class="col-sm-8">
										<input type="text" name="city" size="40" class="form-control" placeholder="Enter City">
									</div>
								</div>
								<div class="form-group">
									<label for="zip" class="col-sm-4 control-label">Zip</label>
									<div class="col-sm-8">
										<input type="text" name="zip" size="40" class="form-control" placeholder="Enter Zip">
									</div>
								</div>
								<div class="form-group">
									<label for="latitude" class="col-sm-4 control-label">Latitude</label>
									<div class="col-sm-8">
										<input type="text" name="latitude" size="40" class="form-control" placeholder="Enter Latitude">
									</div>
								</div>
								<div class="form-group">
									<label for="longitude" class="col-sm-4 control-label">Longitude</label>
									<div class="col-sm-8">
										<input type="text" name="longitude" size="40" class="form-control" placeholder="Enter Longitude">
									</div>
								</div>
								<div class="form-group">
									<label for="notes" class="col-sm-4 control-label">Notes</label>
									<div class="col-sm-8">
										<input type="text" name="notes" size="40" class="form-control" placeholder="Enter Notes">
									</div>
								</div>
								<div class="form-group">
									<label for="cams_code" class="col-sm-4 control-label">CAMS Code</label>
									<div class="col-sm-8">
										<input type="text" name="cams_code" size="40" class="form-control" placeholder="Enter CAMS Code">
									</div>
								</div>
								<div class="form-group">
									<label for="doc" class="col-sm-4 control-label">Doc</label>
									<div class="col-sm-8">
										<input type="text" name="doc" size="40" class="form-control" placeholder="Enter Doc">
									</div>
								</div>
								<div class="form-group">
									<label for="interval" class="col-sm-4 control-label">Interval</label>
									<div class="col-sm-8">
										<input type="text" name="interval" size="40" class="form-control" placeholder="Enter Interval">
									</div>
								</div>
								<div class="form-group">
									<label for="units_code" class="col-sm-4 control-label">Units Code</label>
									<div class="col-sm-8">
										<input type="text" name="units_code" size="40" class="form-control" placeholder="Enter Units Code">
									</div>
								</div>
								<div class="form-group">
									<label for="method_code" class="col-sm-4 control-label">Method Code</label>
									<div class="col-sm-8">
										<input type="text" name="method_code" size="40" class="form-control" placeholder="Enter Method Code">
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
						</div><!--/.panel-body-->
					</div><!--/.panel-default-->
				</div><!--/.site-info-->

				<div class="col-sm-7">
					<div class="panel panel-default">
						<div class="panel-heading">LCS Standards</div>
						<div class="panel-body">
							<?php echo $lcs_page ?>
						</div>
					</div><!--/.panel-->
				</div>

				<div class="col-sm-7">
					<div class="panel panel-default">
						<div class="panel-heading">CVS Standards</div>
						<div class="panel-body">
							<?php echo $cvs_page ?>
						</div>
					</div><!--/.panel-->
				</div>
			</div><!--/.panel-body-->
		</div><!--/.panel-default-->
	</div><!--/.col-sm-12-->
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
	$('select[name="network"]').val($('input[name="network_id"]').val());
</script>
