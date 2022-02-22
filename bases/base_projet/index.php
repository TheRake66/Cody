<!-- Initialisation du kernel -->
<?php 
require_once '.kernel/init.php';
use Kernel\Router;
use Kernel\Html;
?>



<!-- Initialise la balise HTML et l'entete -->
<?= Html::begin() ?>

	<!-- Routage vers le bon controleur -->
	<body>
		<?php Router::routing(); ?>
	</body>
	
<!-- Ferme la balise HTML et fais le rendu -->
<?= Html::end() ?>



<!-- Extinction du kernel -->
<?php require_once '.kernel/quit.php'; ?>