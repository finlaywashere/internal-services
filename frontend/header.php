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
</div>
