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
<form id='record_form'>

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

<table width="100%" cellpadding="10px">
<?php
	if(!$record['id']){
		?>
		<tr>
		<td class='font18 bold'>Add a New User</td>
		<td></td>
		</tr>
		<?php
	}
	else{
		?>
		<tr>
		<td class='font18 bold'>Edit User</td>
		<td></td>
		</tr>
		<?php
	}
?>
<tr>
<td width='50%'> 
	<table width="100%">
		<!--
		<tr class="even required">
		  <td>* Name:</td>
		  <td><input type="text" name="name" size="40"></td>
		</tr>
		-->
		<tr class="even required"><td>Name:</td><td><input type="text" name="name" size="40"></td></tr>
		<?php
		if(trim($record['id'])){
			?>
			<tr class="even required"><td>* Login Name:</td><td><?php echo $record['email']; ?></td></tr>
			<?php
		}
		else{
			?>
				<tr class="even required"><td>* Login Name:</td><td><input type="text" name="email" size="40"></td></tr>
			<?php
		}
		if($record['id']){
			?>
			<tr class="even required"><td>* Password:</td><td><input type="text" name="password" size="40" placeholder='Enter new password to change current password'></td></tr>
			<?php
		}
		else{
			?>
			<tr class="even required"><td>* Password:</td><td><input type="text" name="password" size="40"></td></tr>
			<?php
		}
		?>	
	</table>
</td>
<td width='50%'>
	<table width="100%">
	<?php
	if($record['email']!="admin"){
		?>
		<tr class="even required"><td style="width:110px;">Permissions:</td><td>
			<?php
			$t = count($user_groups);
			$can_update_usergroup = $this->user_validation->validate("users", "setUserGroups", false);
			//echo $can_update_usergroup;
			for($i=0; $i<$t; $i++){
				if(in_array($user_groups[$i]['user_group'], $user_user_groups)){
					if($can_update_usergroup){
						?><input checked name="user_groups[]" type="checkbox" value="<?php echo htmlentitiesX($user_groups[$i]['user_group']); ?>" ><?php echo htmlentitiesX($user_groups[$i]['user_group']); ?><br /><?php
					}
					else{
						?><input style='display:none' checked name="user_groups[]" type="checkbox" value="<?php echo htmlentitiesX($user_groups[$i]['user_group']); ?>" ><?php echo htmlentitiesX($user_groups[$i]['user_group']); ?><br /><?php
					}
				}
				else if($can_update_usergroup){
					?><input name="user_groups[]" type="checkbox" value="<?php echo htmlentitiesX($user_groups[$i]['user_group']); ?>" ><?php echo htmlentitiesX($user_groups[$i]['user_group']); ?><br /><?php
				}
			}
			?>
		</td></tr>
		<?php
	}
	if($record['id']){
		?>
		<tr class="even required"><td style="width:90px;">Date/Time Added:</td><td><?php echo date("M d, Y H:i:s", strtotime($record['dateadded']) ); ?></td></tr>
		<?php
	}
	?>
	</table>
</td>
</tr>

<tr>
	<td colspan="2" class='center'>
		<table width='100%'>
		<tr>
			<td width=100%>
				<input type="button" id='savebutton' value="Save" onclick="saveRecord()" />
			</td>
			<?php 
			if($record['id']&&$record['id']!=$_SESSION['user']['id']&&$record['email']!="admin"){ //should not be able to delete self and admin
				?><td><input type="button" style='background:red; color:white' value="Delete" onclick="deleteRecord('<?php echo $record['id']; ?>')" /></td><?php
			}
			?>
		</tr>
		</table>
	</td>
</tr>
</td>
</table>
</form>

<script>
<?php

if(is_array($pictures)){
	?>
	html = "";
	<?php
}
if($record){
	foreach($record as $key=>$value){	
		if($key=='password'){
		}
		else if($key=='email'){
		}
		else if(trim($value)||1){
			?>
			jQuery('[name="<?php echo $key; ?>"]').val("<?php echo sanitizeX($value); ?>");
			<?php
		}		
	}
}
?>
</script>
