<?php
	$controller = $this->router->class;
	$method = $this->router->method;
?>

	<?php
	if($this->user_validation->validate("sites", "index", false)){
		?>
		<li <?php if($controller=="sites"){ echo "class='selected'"; } ?> onclick='self.location="<?php echo site_url("sites");?>"'>
			<a href='<?php echo site_url("sites");?>'>Site List</a>
		</li>
		<?php
	}
	if($this->user_validation->validate("txo_dumps", "index", false)){
		?>
		<li <?php if($controller=="txo_dumps"){ echo "class='selected'"; } ?> onclick='self.location="<?php echo site_url("txo_dumps");?>"'>
			<a href='<?php echo site_url("txo_dumps");?>'>Data</a>
		</li>
		<?php
	}
	
	if($this->user_validation->validate("airs_list", "index", false)){
		?>
		<li <?php if($controller=="airs_list"){ echo "class='selected'"; } ?> onclick='self.location="<?php echo site_url("airs_list");?>"'>
			<a href='<?php echo site_url("airs_list");?>'>Airs List</a>
		</li>
		<?php
	}
	?>
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">State Of Texas <span class="caret"></span></a>
		<ul class="dropdown-menu">
			<?php
			if($this->user_validation->validate("tceq", "index", false)){
				?>
				<li <?php if($controller=="tceq"){ echo "class='selected'"; } ?> onclick='self.location="<?php echo site_url("tceq");?>"'>
					<a href='<?php echo site_url("tceq");?>'>Target Components</a>
				</li>
				<?php
			}
			if($this->user_validation->validate("network", "index", false)){
				?>
				<li <?php if($controller=="network"){ echo "class='selected'"; } ?> onclick='self.location="<?php echo site_url("network");?>"'>
					<a href='<?php echo site_url("network");?>'>Networks</a>
				</li>
				<?php
			}
			if($this->user_validation->validate("coa", "index", false)){
				?>
				<li <?php if($controller=="coa"){ echo "class='selected'"; } ?> onclick='self.location="<?php echo site_url("coa");?>"'>
					<a href='<?php echo site_url("coa");?>'>Certificate of Analysis</a>
				</li>
				<?php
			}
			if($this->user_validation->validate("standards", "index", false)){
			?>
			<li <?php if($controller=="standards"){ echo "class='selected'"; } ?> onclick='self.location="<?php echo site_url("standards");?>"'>
				<a href='<?php echo site_url("standards");?>'>Standards Components</a>
			</li>
			<?php
			}
			?>
		</ul>
	</li>
	<?php

	/*	if($this->user_validation->validate("quick_looks", "index", false)){
		?>
		<li <?php if($controller=="quick_looks"){ echo "class='selected'"; } ?> onclick='self.location="<?php echo site_url("quick_looks");?>"'>
			<a href='<?php echo site_url("quick_looks");?>'>Quick Looks</a>
		</li>
		<?php
	}*/
	/*	if($this->user_validation->validate("qaqc", "index", false)){
		?>
		<li <?php if($controller=="qaqc"){ echo "class='selected'"; } ?> onclick='self.location="<?php echo site_url("qaqc");?>"'>
			<a href='<?php echo site_url("qaqc");?>'>QAQC</a>
		</li>
		<?php
	}*/
/*[[MENU]]*/