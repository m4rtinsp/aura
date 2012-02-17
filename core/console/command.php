<?php
	$path = explode('index.php', $_SERVER["SCRIPT_NAME"]);
	$path = $path[0];
	
	if (strpos($path, 'command.php') !== false) {
		$path = explode('core/console/command.php', $path);
		header("Location: {$path[0]}");
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>Aura Framework - Console</title>
		<link rel="stylesheet" type="text/css" href="core/system/assets/web/aura_php_framework.css" />
		<script type="text/javascript" language="JavaScript" src="core/console/jquery-1.5.2.min.js"></script>
		<script type="text/javascript" language="JavaScript" src="core/console/aura_framework_console.js"></script>
		<script type="text/javascript" language="JavaScript">
			AuraFramework.path = '<?= $path ?>';
			AuraFramework.version = '<?= AURA_FRAMEWORK_VERSION ?>';
			AuraFramework.php_version = '<?= AURA_PHP_MIN_VERSION ?>';
			AuraFramework.smarty_version = '<?= SMARTY ?>';
		</script>
	</head>
	<body>
		<div class='aura-default-box'>
			<h1>Aura Framework - Console</h1>
			
			<div id="console">
				<div>
					<ul></ul>
				</div>
				<input type="text" class="command" id="command" />
			</div>
		</div>
	</body>
</html>
