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

					<div class="form-group">
						<label for="aqi_no" class="col-sm-4 control-label">AQI</label>
						<div class="col-sm-8">
							<input type="text" name="aqi_no" size="40" class="form-control" placeholder="Enter AQI">
						</div>
					</div>
					<div class="form-group">
						<label for="component_name" class="col-sm-4 control-label">Component</label>
						<div class="col-sm-8">
							<input type="text" name="component_name" size="40" class="form-control" placeholder="Enter Component">
						</div>
					</div>
					<div class="form-group">
						<label for="component_name" class="col-sm-4 control-label">Alias</label>
						<div class="col-sm-8">
							<input type="text" name="alias" size="40" class="form-control" placeholder="Enter Alias">
						</div>
					</div>
					<div class="form-group">
						<label for="component_name" class="col-sm-4 control-label">Carbon No</label>
						<div class="col-sm-8">
							<input type="number" name="carbon_no" size="40" class="form-control" placeholder="Enter Carbon No">
						</div>
					</div>
					<div class="form-group">
						<label for="cas" class="col-sm-4 control-label">CAS</label>
						<div class="col-sm-8">
							<input type="text" name="cas" size="40" class="form-control" placeholder="Enter CAS">
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
				jQuery('[name="<?php echo $key; ?>"]').val("<?php echo sanitizeX($value); ?>");
				<?php
			}		
		}
	}
	?>
</script>
