<?php
@session_start();
$sid = session_id()."_".time();
?>
<script>
function saveRecord(approve){

	var cyl = $('input[name="cylinder"]');

	if(cyl.val().length<1){
		cyl.focus();
	}else{
		extra = "";
		jQuery("#savebutton").val("Saving...");
		cylinder = $("#record_form").serialize();
		lcs_values = $("#form-cylinder-lcs-standards").serialize();
		cvs_values = $("#form-cylinder-cvs-standards").serialize();

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
			data: { cyl: cylinder, lcs: lcs_values, cvs: cvs_values },
			dataType: "script",
			success: function(data){
				//alert(data);
			}
		});	
	}
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
				<div class="col-sm-5 cylinder-info">
					<div class="panel panel-default">
						<div class="panel-heading">Cylinder Information:</div>
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
										<label for="cylinder" class="col-sm-4 control-label">Cylinder</label>
										<div class="col-sm-8">
											<input type="text" name="cylinder" size="40" class="form-control" placeholder="Enter Cylinder">
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
				</div><!--/.cylinder-info-->

				<div class="col-sm-7 cylinder-standards">
					
						<div class="panel panel-default">
							<div class="panel-heading">Cylinder Standards</div>
							<div class="panel-body">
								<form id="from-standards" role="form">
									<div class="container">
										<label class="checkbox-inline"><input type="radio" value="LCS" name="standard"> LCS</label>
										<label class="checkbox-inline"><input type="radio" value="CVS" name="standard"> CVS</label>
										<label class="checkbox-inline"><input type="radio" value="RTS" name="standard"> RTS</label>
									</div>

			    					<table class="table table-striped">
										<tr>
											<th>Component</th>
											<th>Standard Concentration</th>
										</tr>
										<?php
										print_r($lcs_cvs);
										$tlc = count($lcs_cvs); 
										for($i=0; $i<$tlc; $i++){
											if($lcs_cvs[$i]['channel']=='A'){
											?>
											<tr id="lcs_cvs-tr-<?php echo $lcs_cvs[$i]['tceq_id'] ?>" class="lcs_cvs">
												<td><?php echo $lcs_cvs[$i]['component_name']; ?></td>
												<td><input type="number" step="any" name="lcs_cvs-<?php echo $lcs_cvs[$i]['tceq_id'] ?>" value="<?php echo (isset($lcs_cvs[$i]['value']))? $lcs_cvs[$i]['value'] : '' ?>"></td>
											</tr>
											<?php
											}
										}
										?>
			    					</table>
								</form>
							</div><!--/.panel-body-->
						</div><!--/.panel-->
				</div><!--/.cylinder-standards-->
			</div><!--/.panel-body-->
		</div><!--.panel-default-->
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
$('input[name="standard"]').on('click', function(){
	var standard = $(this).val();
	$.ajax({
		url: "<?php echo site_url(); echo $controller ?>/ajax_delete/"+co_id,
		type: "POST",
		data: formdata,
		dataType: "script",
		success: function(){
			jQuery("#tr"+co_id).fadeOut(200);
			self.location = "<?php echo site_url(); echo $controller ?>";
		}
	});
});
</script>
