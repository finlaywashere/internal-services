<link href="/assets/css/bootstrap/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
<script src="/assets/js/bootstrap/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<ul class="navbar-nav">
		<?php
		require_once "private/config.php";
		GLOBAL $modules;
		foreach ($modules as $module){
			echo "<li class=\"nav-item\"><a class=\"nav-link\" href=\"".$module[1]."\">".$module[0]."</a></li>";
		}
		?>
		</ul>
		<span class="navbar-text float-end ms-auto">
			<div class="float-end">
				Welcome <?php echo get_username(); ?>!
				<a class="nav-link" href=/authentication/frontend/logout.php?referrer=/frontend/index.php>Logout</a>
			</div>
		</span>
	</ul>
</nav>
