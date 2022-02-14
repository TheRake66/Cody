<?php 
require_once '.kernel/init.php';
use Kernel\Router;
use Kernel\Html;
?>



<!DOCTYPE html>
<html lang='fr'>

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
	<?= Html::importScript('debug/app/global_brefore.js') ?>
	
	<!-- Routage vers le bon controleur -->
	<body>
		<?php Router::routing(); ?>
	</body>
	
	<!-- Inclusion du script global de fin -->
	<?= Html::importScript('debug/app/global_after.js') ?>
		
	<!-- Inclusion du style global -->
	<?= Html::importStyle('debug/app/global.less') ?>
		
</html>



<?php require_once '.kernel/quit.php'; ?>