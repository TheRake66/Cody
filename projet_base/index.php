<?php
// ####################################################################################################
// Inclue l'autoloader
require_once 'librairies/autoLoader.php';
// Demarre la session
session_start();
// ####################################################################################################
?>



<!DOCTYPE html>
<html lang="fr">

	<head>
		<meta charset="utf-8" />
		<title>GFFramework</title>
		<link rel="icon" href="images/favicon.ico"/>
	</head>

	<body>
		<?php 
		// ####################################################################################################
		// Inclue le controleur principal
		require_once 'controleurs/controleurPrincipal.php'; 
		// ####################################################################################################
		?>
	</body>
	
</html>
