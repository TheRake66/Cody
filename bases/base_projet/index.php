<?php require_once '__kernel/init.php'; ?>



<!DOCTYPE html>
<html lang='fr'>

	<!-- Definition du head -->
	<head>
		<meta charset="UTF-8">
		<meta name="description" content="{PROJECT_NAME}">
		<meta name="keywords" content="{PROJECT_NAME}">
		<meta name="author" content="{USER_NAME}">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>{PROJECT_NAME}</title>
		<link rel='icon' href='image/favicon.ico'/>
	</head>
		
	<!-- Inclusion du script global de debut -->
	<script type='text/javascript' src='composant/global_brefore.js'></script>
	
	<!-- Routage vers le bon controleur -->
	<body>
		<?php Kernel\Router::routing(); ?>
	</body>
	
	<!-- Inclusion du script global de fin -->
	<script type='text/javascript' src='composant/global_after.js'></script>
		
	<!-- Inclusion du style global -->
	<link rel="stylesheet/less" type="text/css" href="composant/global.less">
	
	<!-- Inclusion de Less -->
	<script type='text/javascript' src="kernel/less@4.1.1.js"></script>
		
</html>