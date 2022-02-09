<?php require_once '.kernel/init.php'; ?>



<!DOCTYPE html>
<html lang='fr' style="opacity: 0;">

	<!-- Definition du head -->
	<head>
		<meta charset="UTF-8">
		<meta name="description" content="{PROJECT_NAME}">
		<meta name="keywords" content="{PROJECT_NAME}">
		<meta name="author" content="{USER_NAME}">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="theme-color" content="#3B78FF">
		<meta name="msapplication-navbutton-color" content="#3B78FF">
		<meta name="apple-mobile-web-app-status-bar-style" content="#3B78FF">
		<title>{PROJECT_NAME}</title>
		<link rel='icon' href='favicon.ico'/>
	</head>
		
	<!-- Inclusion du script global de debut -->
	<script type='text/javascript' src='debug/app/global_brefore.js'></script>
	
	<!-- Routage vers le bon controleur -->
	<body>
		<?php Kernel\Router::routing(); ?>
	</body>
	
	<!-- Inclusion du script global de fin -->
	<script type='text/javascript' src='debug/app/global_after.js'></script>
		
	<!-- Inclusion du style global -->
	<link rel="stylesheet/less" type="text/css" href="debug/app/global.less">
	
	<!-- Inclusion de Less -->
	<script type='text/javascript' src='.kernel/less@4.1.1.js'></script>
	<script>
		async function load() {
			await new Promise(r => setTimeout(r, 200));
			document.getElementsByTagName('html')[0].style.opacity = 1;
		}
		document.addEventListener("DOMContentLoaded", load);
	</script>
		
</html>



<?php require_once '.kernel/quit.php'; ?>