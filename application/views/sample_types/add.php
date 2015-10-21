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
							<label for="name" class="col-sm-4 control-label">Name</label>
							<div class="col-sm-8">
								<input type="text" name="name" size="40" class="form-control" placeholder="Enter Name">
							</div>
							</div><div class="form-group">
							<label for="abbr" class="col-sm-4 control-label">Alias</label>
							<div class="col-sm-8">
								<input type="text" name="abbr" size="40" class="form-control" placeholder="Enter Alias">
							</div>
							</div><div class="form-group">
							<label for="file_designator" class="col-sm-4 control-label">File Designator</label>
							<div class="col-sm-8">
								<input type="text" name="file_designator" size="40" class="form-control" placeholder="Enter File Designator">
							</div>
							</div><div class="form-group">
							<label for="method" class="col-sm-4 control-label">Method</label>
							<div class="col-sm-8">
								<input type="text" name="method" size="40" class="form-control" placeholder="Enter Method">
							</div>
							</div><div class="form-group">
							<label for="description" class="col-sm-4 control-label">Description</label>
							<div class="col-sm-8">
								<input type="text" name="description" size="40" class="form-control" placeholder="Enter Description">
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
