<?php
	@session_start();
	$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>ORSAT</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- JQUery -->
	<script language="javascript" src="<?php echo site_url("media/js/jquery-1.11.3.min.js"); ?>"></script>
	<script language="javascript" src="<?php echo site_url("media/js/jquery-migrate-1.2.1.js"); ?>"></script>
	
	<link rel="stylesheet" href="<?php echo site_url("media/jquery-ui-1.11.4/jquery-ui.min.css"); ?>" type="text/css" />
	<script src="<?php echo site_url("media/jquery-ui-1.11.4/jquery-ui.min.js"); ?>"></script>
	
	<script type="text/javascript" src="<?php echo site_url("media/plupload/js/plupload.full.min.js"); ?>"></script>

	<script type="text/javascript" src="<?php echo site_url("media/js/jquery.alerts-1.1/jquery.alerts.js"); ?>"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url("media/js/jquery.alerts-1.1/jquery.alerts.css"); ?>" media="screen" />
	
	<link rel="stylesheet" type="text/css" href="<?php echo site_url("media/custom.css?_=".time()); ?>" media="screen" />
	
	<!-- Chart.js -->
	<script src="<?php echo site_url('media/chartsjs/Chart.js'); ?>"></script>

	<script src="<?php echo site_url('media/bootstrap-list-filter/bootstrap-list-filter.src.js'); ?>"></script>
	    
	<!--Bootstrap-->
	<link rel="stylesheet" href="<?php echo site_url('media/bootstrap/css/bootstrap.min.css'); ?>">
	<script src="<?php echo site_url('media/bootstrap/js/bootstrap.min.js'); ?>"></script>

	<!--FontAwesome-->
	<link rel="stylesheet" href="<?php echo site_url('media/font-awesome/css/font-awesome.min.css'); ?>">

	 <!-- Chosen Select  -->
    <link rel="stylesheet" href="<?php echo site_url('media/brio/chosen.css'); ?>" />
	<!-- TagsInput Styling  -->
    <link rel="stylesheet" href="<?php echo site_url('media/brio/bootstrap-tagsinput.css'); ?>" />
	<!-- DateTime Picker  -->
    <link rel="stylesheet" href="<?php echo site_url('media/brio/bootstrap-datetimepicker.css'); ?>" />
	
	<link rel="stylesheet" type="text/css" href="<?php echo site_url("media/app.v1.css?_=".time()); ?>" />
</head>

<body class="<?php echo isset($controller) ? $controller: 'page-login'; ?>">
	
	<div id='imagepreload' class='hidden'>
		<img src='<?php echo site_url("media/check.png"); ?>' />
		<img src='<?php echo site_url("media/x.png"); ?>' />
		<img src='<?php echo site_url("media/new.png"); ?>' />
		<img src='<?php echo site_url("media/ajax-loader.gif"); ?>' />
	</div>
	
	<div id="dialog" title="">
		<div id='dialoghtml'></div>
	</div>


		<?php
			if($user){ //if logged in
		?>

		<!--
			<aside class='left-panel' style='overflow: hidden; outline: none;'>
				<div class="user text-center">
					  <img src="<?php echo site_url('media/cuser-hover.png'); ?>" class="img-circle" alt="...">
					  <h4 class="user-name"><?php echo strtoupper($user['name']); ?></h4>
					  
					  <div class="dropdown user-login">
					  </div>	 
				</div>

				<nav class="navigation">
					<ul class="list-unstyled">
						<li class="active"><a href="<?php echo site_url(); ?>"><i class="fa fa-bookmark-o"></i><span class="nav-label">Dashboard</span></a></li>
					</ul>
				</nav>
			</aside>-->
			<section class='content'>
				<header class='container-fluid'>
				<!--
					<button type="button" class="navbar-toggle pull-left">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>-->

					<nav class='navbar-default navbar-inverse hidden-xs' role='navigation'>
						<ul class='nav navbar-nav'>
							<?php
								$this->load->view("layout/menus");
							?>

						</ul>
					</nav>
					<style>
						.dropdown-menu.filter.lg{
							width: 600px !important;
						}
						header.container-fluid{ margin-bottom: 30px !important; }
					</style>
					<ul class="nav-toolbar">
						<li class="dropdown"><a href="#" data-toggle="dropdown"><i class="fa fa-search"></i></a>
							<div class="dropdown-menu filter lg pull-right arrow panel panel-default arrow-top-right">
								<div class="panel-heading">
									Generate Reports
								</div>
								<div class="panel-body text-center">
									<div class="row">
										<form class="form-inline" action="<?php echo site_url(); ?>filter" method="post">
											<input type="hidden" value="<?php echo $instrument_name; ?>" name="sitename">
												<?php
													$y = date("Y");
												?>

												<div class="row">

													<div class="form-group">
														<label for"site">Site</label>
														<select id="site" name="site" class="form-control">
															<?php
																if($sites){
																	$sc = count($sites);
																	for($i=0; $i<$sc; $i++){
																		echo '<option value="'.$sites[$i]['id'].'">'.$sites[$i]['instrument_name'].'</option>';
																	}
																}
															?>
														</select>
													</div>
													<div class="form-group">
														<select id="start-year" name="year" class="form-control">
															<option value="0">Year</option>
															<?php for($i=$y; $i>=1970; $i--){ ?>
															<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
															<?php } ?>
														</select>
													</div>
												</div>
												<div class="row">
												Start:
													<div class="form-group">
														<?php
															$month_names = array("January","February","March","April","May","June","July","August","September","October","November","December");
															echo '<select id="start-month" name="start-month" class="form-control">';
															echo '<option value="0">Month</option>';
															$i=1;
															foreach($month_names as $month) {
																echo '<option value="' . $i .'">'.$month.'</option>';
																$i++;
															}
															echo "</select>";
														?>
													</div>
													<div class="form-group">
														<select id="start-day" name="start-day" class="form-control">
															<option value="0">Day</option>
															<?php for($i=1; $i<=31; $i++){ ?>
															<option value="<?php echo ($i<=9) ? "0". $i : $i; ?>"><?php echo $i; ?></option>
															<?php } ?>
														</select>
													</div>
													<div class="form-group">
														<select id="start-hour" name="start-hour" class="form-control">
															<option value="0">Hour</option>
															<?php $i=1; foreach(range('A','X') as $c){ ?>
															<option value="<?php echo ($i<=9) ? "0". $i : $i; ?>"><?php echo $c; ?></option>
															<?php $i++; } ?>
														</select>
													</div>
												</div>
												<div class="row">
												End:
													<div class="form-group">
														<?php
															$month_names = array("January","February","March","April","May","June","July","August","September","October","November","December");
															echo '<select id="end-month" name="end-month" class="form-control">';
															echo '<option value="0">Month</option>';
															$i=1;
															foreach($month_names as $month) {
																echo '<option value="' . $i .'">'.$month.'</option>';
																$i++;
															}
															echo "</select>";
														?>
													</div>
													<div class="form-group">
														<select id="end-day" name="end-day" class="form-control">
															<option value="0">Day</option>
															<?php for($i=1; $i<=31; $i++){ ?>
															<option value="<?php echo ($i<=9) ? "0". $i : $i; ?>"><?php echo $i; ?></option>
															<?php } ?>
														</select>
													</div>
													<div class="form-group">
														<select id="end-hour" name="end-hour" class="form-control">
															<option value="0">Hour</option>
															<?php $i=1; foreach(range('A','X') as $c){ ?>
															<option value="<?php echo ($i<=9) ? "0". $i : $i; ?>"><?php echo $c; ?></option>
															<?php $i++; } ?>
														</select>
													</div>
												</div>
												<button type="submit" class="btn btn-success">Search</button>
										</form>
									</div>
								</div>
							</div>
						</li>
						<?php if ($user['id']==1&&$user['name']=="Super Admin"){
							?>
						<li class="dropdown"><a href="#" data-toggle="dropdown"><i class="fa fa-cog"></i></a>
							<div class="dropdown-menu lg pull-right arrow panel panel-default arrow-top-right">
								<div class="panel-heading">
									Settings
								</div>
								<div class="panel-body text-center">
									<div class="row">
										<div class="col-xs-4 col-sm-4"><a href="<?php echo site_url("users");?>" class="text-red"><span class="h2"><i class="fa fa-users"></i></span><p class="text-gray no-margn">Users</p></a></div>
										<div class="col-xs-4 col-sm-4"><a href="<?php echo site_url("user_permissions");?>" class="text-green"><span class="h2"><i class="fa fa-user-secret"></i></span><p class="text-gray no-margn">Permissions</p></a></div>
										<div class="col-xs-4 col-sm-4"><a href="<?php echo site_url("admin/createcms");?>" class="text-blue"><span class="h2"><i class="fa fa-file-text-o"></i></span><p class="text-gray no-margn">Create CMS</p></a></div>
									</div>
								</div>
							</div>
						</li>
						<?php 
						}
						?>
						<li class="dropdown"><a href="#" data-toggle="dropdown"><i class="fa fa-user"></i></a>
							<div class="dropdown-menu lg pull-right arrow panel panel-default arrow-top-right">
								<div class="panel-heading">
									Settings
								</div>
								<div class="panel-body text-center">
									<div class="row">
										<div class="col-xs-6 col-sm-6"><a href="<?php echo site_url("users/edit/".$user['id']); ?>" class="text-purple"><span class="h2"><i class="fa fa-pencil-square-o"></i></span><p class="text-gray no-margn">Edit Account</p></a></div>
										<div class="col-xs-6 col-sm-6"><a href="<?php echo site_url("admin/logout"); ?>" class="text-green"><span class="h2"><i class="fa fa-sign-out"></i></span><p class="text-gray no-margn">Logout</p></a></div>
									</div>
								</div>
							</div>
						</li>
					</ul>
				</header>
				<div class='wrapper container-fluid'>
					<?php
						if($content&&$user){
							echo $content;
						}else if($createcms){
							$this->load->view("layout/createcms");
						}
					?>
				</div>


		<?php
			}else{
				$this->load->view("layout/login");
			}
		?>
		<footer>
			<?php
				if($user){
					echo '<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>';
					?>
					<a href="#" class="pull-right scrollToTop"><i class="fa fa-chevron-up"></i></a>
					<?php
				}
			?>
			
		</footer>
		</section>

		<!-- InputMask -->
	    <script src="<?php echo site_url('media/brio/jquery.inputmask.bundle.js'); ?>"></script>
		<!-- TagsInput -->
	    <script src="<?php echo site_url('media/brio/bootstrap-tagsinput.min.js'); ?>"></script>

	    <!-- Choosen -->
	    <script src="<?php echo site_url('media/brio/chosen.jquery.js'); ?>"></script>

		<!-- Moment -->
	    <script src="<?php echo site_url('media/brio/moment.js'); ?>"></script>

		<!-- DateTime Picker -->
	    <script src="<?php echo site_url('media/brio/bootstrap-datetimepicker.js'); ?>"></script>

	    <!-- NanoScroll -->
	    <script src="<?php echo site_url('media/brio/jquery.nicescroll.min.js'); ?>"></script>
	    
		<!-- Data Table -->
	    <script src="<?php echo site_url('media/brio/jquery.dataTables.js'); ?>"></script>
	    <script src="<?php echo site_url('media/brio/DT_bootstrap.js'); ?>"></script>
	    <script src="<?php echo site_url('media/brio/jquery.dataTables-conf.js'); ?>"></script>

	    <script type="text/javascript" src="<?php echo site_url("media/custom.js?_=".time()); ?>"></script>
	    <script src="<?php echo site_url('media/fp_custom.js'); ?>"></script>
	</body>
</html>