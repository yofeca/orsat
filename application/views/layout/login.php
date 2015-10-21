<style>
	.login{ margin-top: 100px; padding: 15px; border: 1px solid #ddd; }
	#submit-login{ width: 40%; padding: 5px; margin-left: auto; margin-right: auto; }
	.login-container{ text-align: center; width: 100%; }
</style>
<div class="login col-sm-4 col-sm-offset-4">
	<div class="row">
		<div class="col-lg-8 col-lg-offset-2">
		<h3 class="text-center">ORSAT</h3>
		<p class="text-center">Sign in to get in touch</p>
		<hr class="clean">
			<form method="post" action="<?php echo site_url(); ?>admin/login">
					<?php
					if($_GET['error']){
						echo $_GET['error'];
					}
					?>
				<div class="form-group input-group">
					<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
					<input placeholder="Username" class='form-control' type='text' id='login_email' name='login_email' value="<?php echo htmlentities($_GET['login_email']); ?>">
				</div>
				<div class="form-group input-group">
				<span class="input-group-addon"><i class="fa fa-key"></i></span>
					<input placeholder="Password" class='form-control' type='password' id='password' name='password'>
				</div>
				<!--div class="form-group">
					<label class="cr-styled">
						<input type="checkbox" ng-model="todo.done">
						<i class="fa"></i> 
					</label>
						Remember me
				</div-->
				<input id="submit-login" class='btn btn-default btn-block btn-xs' type='submit' value='Log In'>
			</form>
		</div>
	</div>
</div>
