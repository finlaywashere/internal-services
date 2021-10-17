<div class="header">
	<div class="left">
		<ul>
			<?php
			require_once "private/config.php";
			GLOBAL $modules;
			foreach ($modules as $module){
				echo "<li><a href=\"".$module[1]."\">".$module[0]."</a></li>";
			}
			?>
		</ul>
	</div>
	<div class="right">
		<ul>
			<li>Welcome <?php echo $_COOKIE['username']; ?>!</li>
			<li><a href=/authentication/frontend/logout.php?referrer=/frontend/index.php>Logout</a></li>
		</ul>
	</div>
</div>
