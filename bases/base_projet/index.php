<!-- ================================================== -->
<!-- Appel de l'autoloader et demarrage de la session -->
<?php
require_once 'librairie/php/autoloader.php';
new Librairie\Autoloader();
session_start();
?>
<!-- ================================================== -->



<!DOCTYPE html>
<html lang='fr'>

	<!-- ================================================== -->
	<!-- Definition du head -->
	<head>
		<meta charset="UTF-8">
		<meta name="description" content="{PROJECT_NAME}">
		<meta name="keywords" content="{PROJECT_NAME}">
		<meta name="author" content="{USER_NAME}">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>{PROJECT_NAME}</title>
		<link rel='icon' href='image/favicon.ico'/>
		
		<!-- Inclusion du script global de debut -->
		<script type='text/javascript' src='composant/global_brefore.js'></script>
	</head>
	<!-- ================================================== -->


	<body>
		<!-- ================================================== -->
		<!-- Routage vers le bon controleur  -->
		<?php
		require_once 'composant/route.php';
		Librairie\Routeur::routing();
		?>
		<!-- ================================================== -->
	</body>
	
	
	<!-- ================================================== -->
	<!-- Inclusion du script global de fin -->
	<script type='text/javascript' src='composant/global_after.js'></script>
		
	<!-- Inclusion du style global -->
	<link rel="stylesheet/less" type="text/css" href="composant/global.less">
	
	<!-- Inclusion de Less -->
	<script src="https://cdn.jsdelivr.net/npm/less@4.1.1" ></script>
	<!-- ================================================== -->
		
</html>