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
		<meta charset='utf-8' />
		<title>{PROJECT_NAME}</title>
		<link rel='icon' href='image/favicon.ico'/>
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
	<!-- Inclusion des fichiers globaux et de Less -->
	<link rel="stylesheet/less" type="text/css" href="composant/global.less">
<<<<<<< Updated upstream
	<link rel="stylesheet/less" type="text/css" href="composant/theme.less">
=======
>>>>>>> Stashed changes
	<script type='text/javascript' src='composant/global.js'></script>
	<script src="https://cdn.jsdelivr.net/npm/less@4.1.1" ></script>
	<!-- ================================================== -->
	
</html>