<center>
<form method="post" action="<?php echo site_url(); ?>admin/createcms">
	<table cellpadding="3">
		<?php
		if($_GET['message']){
			?>
			<tr>
				<td class='center bold font14' colspan="2"><?php echo $_GET['message']; ?></td>
			</tr>
			<?php
		}
		?>
		<tr>
			<td class='font14'>Table: </td>
			<td><input class='font14' type='text' id='table' name='table'></td>
		</tr>
		<tr>
			<td class='font14'>Controller Filename / Views Folder Name: </td>
			<td><input class='font14' type='text' id='folder' name='folder'></td>
		</tr>
		<tr>
			<td class='font14'>Controller Display Name: </td>
			<td><input class='font14' type='text' id='display' name='display'></td>
		</tr>
		<tr>
			<td class='font14'>Edit Fields and Labels:</td>
			<td>
			e.g. name|Name (new line separated for multiple entry)
			<br>
			<textarea name='edit_fields'></textarea></td>
		</tr>
		<tr>
			<td class='font14'>Display Fields and Labels:</td>
			<td>
			e.g. name|Name (new line separated for multiple entry)
			<br>
			<textarea name='display_fields'></textarea></td>
		</tr>
		<tr>
			<td class='font14'>Filter Search Fields and Labels:</td>
			<td>
			e.g. name|Name (new line separated for multiple entry)
			<br>
			<textarea name='filter_fields'></textarea></td>
		</tr>
		<tr>
			<td class='center' colspan="2">
			<input class='font14' type='submit' value='Create' >
			</td>
		</tr>
	</table>
</form>
</center>