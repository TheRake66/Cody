<!-- ================================================== -->
<!-- Appel de l'autoloader et demarrage de la session -->
<?php
require_once 'librairies/autoloader.php';
require_once 'configurations/loader.php';
new Librairie\Autoloader();
session_start();
?>
<!-- ================================================== -->



<!DOCTYPE html>
<html lang='fr'>

	<head>
		<meta charset='utf-8' />
		<title>{PROJECT_NAME}</title>
		<link rel='icon' href='images/favicon.ico'/>
	</head>

	<body>
		<!-- ================================================== -->
		<!-- Routage vers le bon controleur -->
		<?php 
		require_once 'configurations/route.php';
		Librairie\Routeur::routing();
		?>
		<!--================================================== -->
	</body>
	
</html>
