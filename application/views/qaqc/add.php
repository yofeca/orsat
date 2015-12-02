<?php
@session_start();
$sid = session_id()."_".time();
?>
<script>
function saveRecord(approve){	
	extra = "";
	$("#savebutton").val("Saving...");
	formdata = $("#record_form").serialize();
	$("#record_form *").attr("disabled", true);

	$.ajax({
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
		$.ajax({
			url: "<?php echo site_url(); echo $controller ?>/ajax_delete/"+co_id,
			type: "POST",
			data: formdata,
			dataType: "script",
			success: function(){
				$("#tr"+co_id).fadeOut(200);
				self.location = "<?php echo site_url();?>site_info?sid=<?php echo $record['site_id'];?>";
			}
		});
		
	}
}
</script>

<input type='hidden' id='tempcreatelabel' />
<div class="row">
	<div class="col-sm-6 col-sm-offset-3">
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

					<!--div class="form-group">
						<label for="site_id" class="col-sm-4 control-label">Site ID</label>
						<div class="col-sm-8">
							<input type="text" name="site_id" size="40" class="form-control" placeholder="Enter Site ID">
						</div>
					</div-->
					<div class="form-group">
						<label for="site_name" class="col-sm-4 control-label">Site</label>
						<div class="col-sm-8">
							<input type="hidden" name="site_id" value="<?php echo $sites['id'] ?>">
							<input type="text" name="site_name" size="40" class="form-control" placeholder="Enter Site Name" value="<?php echo $sites['instrument_name']; ?>" readonly>
						</div>
					</div>

					<div class="form-group">
						<label for="validator" class="col-sm-4 control-label">Validator</label>
						<div class="col-sm-8">
							<input type="text" name="validator" size="40" class="form-control" placeholder="Enter Validator">
						</div>
					</div>
					<div class="form-group">
						<label for="operator" class="col-sm-4 control-label">Operator</label>
						<div class="col-sm-8">
							<input type="text" name="operator" size="40" class="form-control" placeholder="Enter Operator">
						</div>
					</div>
					<div class="form-group">
						<label for="data_validated_thru" class="col-sm-4 control-label">Data Validated Thru</label>
						<div class="col-sm-8">
							<input type="text" name="data_validated_thru" size="40" class="form-control" placeholder="Enter Data Validated Thru">
						</div>
					</div>
					<div class="form-group">
						<label for="channel_a_rf" class="col-sm-4 control-label">Channel A RF</label>
						<div class="col-sm-8">
							<input type="text" name="channel_a_rf" size="40" class="form-control" placeholder="Enter Channel A RF">
						</div>
					</div>
					<div class="form-group">
						<label for="channel_b_rf" class="col-sm-4 control-label">Channel B RF</label>
						<div class="col-sm-8">
							<input type="text" name="channel_b_rf" size="40" class="form-control" placeholder="Enter Channel B RF">
						</div>
					</div>
					<div class="form-group">
						<label for="last_calibration_date" class="col-sm-4 control-label">Last Calibration Date:</label>
						<div class="col-sm-8">
							<input type="text" name="last_calibration_date" size="40" class="form-control" placeholder="Enter Calibration Date">
						</div>
					</div>
					<div class="form-group">
						<label for="last_calibration_by" class="col-sm-4 control-label">Last Calibration By:</label>
						<div class="col-sm-8">
							<input type="text" name="last_calibration_by" size="40" class="form-control" placeholder="Enter Calibration By">
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
	</div>
</div>

<script>
<?php
	if($record){
		foreach($record as $key=>$value){
			if(trim($value)||1){
				?>
				$('[name="<?php echo $key; ?>"]').val("<?php echo sanitizeX($value); ?>");
				<?php
			}		
		}
	}
	?>
</script>
