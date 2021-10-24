<!-- ================================================== -->
<!-- Appel de l'autoloader et demarrage de la session -->
<?php
require_once 'librairie/autoloader.php';
new Librairie\Autoloader();
session_start();
?>
<!-- ================================================== -->



<!DOCTYPE html>
<html lang='fr'>

	<head>
		<meta charset='utf-8' />
		<title>{PROJECT_NAME}</title>
		<link rel='icon' href='image/favicon.ico'/>
		
		<link rel="stylesheet/less" type="text/css" href="style/global.less">
		<script type='text/javascript' src='script/global.js'></script>
	</head>

	<body>
		<!-- ================================================== -->
		<!-- Routage vers le bon controleur  -->
		<?php
		require_once 'route.php';
		Librairie\Routeur::routing();
		?>
		<!-- ================================================== -->
	</body>
	
	<script src="https://cdn.jsdelivr.net/npm/less@4.1.1" ></script>
	
</html>