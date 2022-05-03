<!-- Initialisation du noyau -->
<?php 
require_once '.kernel/init.php';
use Kernel\Router;
use Kernel\Html\Doctype;
?>



<!-- Ouvre la balise HTML et l'entete -->
<?= Doctype::begin() ?>

	<!-- Routage vers le bon controleur -->
	<body>
		<?php Router::routing(); ?>
	</body>
	
<!-- Ferme la balise HTML et fais le rendu -->
<?= Doctype::end() ?>



<!-- Extinction du noyau -->
<?php require_once '.kernel/quit.php'; ?>