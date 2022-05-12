<!-- Initialisation du noyau -->
<?php 
require_once(__DIR__ . '/.kernel/init.php');
use Kernel\Router;
use Kernel\Html\Doctype;
?>



<!-- Ouvre la balise HTML et l'entete -->
<?= Doctype::open() ?>

	<!-- Routage vers le bon controleur -->
	<body>
		<?php Router::routing(); ?>
	</body>
	
<!-- Ferme la balise HTML et fais le rendu -->
<?= Doctype::close() ?>



<!-- Extinction du noyau -->
<?php require_once(__DIR__ . '/.kernel/quit.php');?>