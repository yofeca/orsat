<?php
@session_start();
$sid = session_id()."_".time();
?>
<script>
function saveRecord(approve){

	var cyl = $('input[name="cylinder"]');
	var stype = $('select[name="standard_type"]');

	if(cyl.val().length<1){
		cyl.focus(); return;
	}

	if(stype.val().length<1){
		$('.chosen-container').css({"border":"1px solid red","border-radius":"4px"}); return;
	}

	extra = "";
	jQuery("#savebutton").val("Saving...");
	cylinder = $("#record_form").serialize();
	var values;

	if(stype.val() =='rts'){
		values = $("#form-rts-standards").serialize();
		console.log('rts');
	}else{
		values = $("#form-lcs_cvs-standards").serialize();
		console.log('lcs');
	}
	
	jQuery("#record_form *").attr("disabled", true);
	jQuery.ajax({
		<?php
		if($record['id']){
			?>url: "<?php  echo site_url(); echo $controller ?>/ajax_edit/<?php echo $record['id']; ?>"+extra,<?php
		}
		else{
			?>url: "<?php echo site_url(); echo $controller ?>/ajax_add"+extra,<?php
		}
		?>
		type: "POST",
		data: { cyl: cylinder, val: values },
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
									<label for="cylinder" class="col-sm-4 control-label">Cylinder Name: </label>
									<div class="col-sm-8">
										<input type="text" name="cylinder" size="40" class="form-control" placeholder="Enter Cylinder">
									</div>
								</div>
								<div class="form-group">
									<label for="cylinder" class="col-sm-4 control-label">Standard Type: </label>
									<div class="col-sm-8">
										<select name="standard_type" class="chosen-select" data-placeholder="Choose Standard Type" <?php echo ($record['id']) ? 'disabled':'' ?>>
											<option></option>
											<option value="lcs">LCS</option>
											<option value="cvs">CVS</option>
											<option value="rts">RTS</option>
										</select>
										<input type="hidden" name="type" size="40" class="form-control" value="">
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
								<form id="form-lcs_cvs-standards" role="form">									
			    					<table class="table table-striped" id="lcs_cvs_standards" style="display: <?php echo ($record['type']=='LCS' || $record['type']=='CVS') ? 'block' : 'none'?>">
										<tr>
											<th>Component</th>
											<th>Standard Concentration</th>
										</tr>
										<tr><td colspan="2" style="background: #ddd"><b>Channel A</b></td></tr>
										<?php
										if($values && ($record['type']=='LCS' || $record['type']=='CVS')){
											$lcs_cvs = $values;
										}
										$tlc = count($lcs_cvs); 
										for($i=0; $i<$tlc; $i++){
											if($lcs_cvs[$i]['channel']=='A'){
											?>
											<tr id="lcs_cvs-tr-<?php echo $lcs_cvs[$i]['airs_list_id'] ?>" class="lcs_cvs">
												<td><?php echo $lcs_cvs[$i]['component_name']; ?></td>
												<td><input type="number" step="any" name="values-<?php echo $lcs_cvs[$i]['airs_list_id'] ?>" value="<?php echo (isset($lcs_cvs[$i]['value']))? $lcs_cvs[$i]['value'] : '' ?>"></td>
											</tr>
											<?php
											}
										}
										?>
										<tr><td colspan="2" style="background: #ddd"><b>Channel B</b></td></tr>
										<?php
										$tlc = count($lcs_cvs); 
										for($i=0; $i<$tlc; $i++){
											if($lcs_cvs[$i]['channel']=='B'){
											?>
											<tr id="lcs_cvs-tr-<?php echo $lcs_cvs[$i]['airs_list_id'] ?>" class="lcs_cvs">
												<td><?php echo $lcs_cvs[$i]['component_name']; ?></td>
												<td><input type="number" step="any" name="values-<?php echo $lcs_cvs[$i]['airs_list_id'] ?>" value="<?php echo (isset($lcs_cvs[$i]['value']))? $lcs_cvs[$i]['value'] : '' ?>"></td>
											</tr>
											<?php
											}
										}
										?>
			    					</table>
								</form>
								<form id="form-rts-standards" role="form">
			    					<table class="table table-striped" id="rts_standards" style="display: <?php echo ($record['type']=='RTS') ? 'block' : 'none'?>">
										<tr>
											<th>Component</th>
											<th>Standard Concentration</th>
										</tr>
										<tr><td colspan="2" style="background: #ddd"><b>Channel A</b></td></tr>
										<?php
										if($values && $record['type']=='RTS'){
											$rts = $values;
										}
										$tlc = count($rts); 
										for($i=0; $i<$tlc; $i++){
											if($rts[$i]['channel']=='A'){
											?>
											<tr id="rts-tr-<?php echo $rts[$i]['airs_list_id'] ?>" class="rts">
												<td><?php echo $rts[$i]['component_name']; ?></td>
												<td><input type="number" step="any" name="values-<?php echo $rts[$i]['airs_list_id'] ?>" value="<?php echo (isset($rts[$i]['value']))? $rts[$i]['value'] : '' ?>"></td>
											</tr>
											<?php
											}
										}
										?>
										<tr><td colspan="2" style="background: #ddd"><b>Channel B</b></td></tr>
										<?php
										$tlc = count($rts); 
										for($i=0; $i<$tlc; $i++){
											if($rts[$i]['channel']=='B'){
											?>
											<tr id="rts-tr-<?php echo $rts[$i]['airs_list_id'] ?>" class="rts">
												<td><?php echo $rts[$i]['component_name']; ?></td>
												<td><input type="number" step="any" name="values-<?php echo $rts[$i]['airs_list_id'] ?>" value="<?php echo (isset($rts[$i]['value']))? $rts[$i]['value'] : '' ?>"></td>
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
$('select[name="standard_type"]').change(function(){
	var standard = $(this).val();
	if(standard =='lcs' || standard=='cvs'){
		$('#lcs_cvs_standards').show(400);
		$('#rts_standards').hide();
	}else{
		$('#rts_standards').show(400);
		$('#lcs_cvs_standards').hide();
	}
});

$('select[name="standard_type"]').val($('input[name="type"]').val().toLowerCase());
</script>
