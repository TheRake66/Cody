<!-- Initialisation du noyau -->
<?php 
require_once(__DIR__ . '/.kernel/init.php');
use Kernel\URL\Router;
use Kernel\HTML\Doctype;
?>



<!-- Ouvre la balise HTML et l'entete -->
<?= Doctype::open() ?>

	<!-- Routage vers le point d'entree -->
	<body>
		<?php Router::app(); ?>
	</body>
	
<!-- Ferme la balise HTML et fais le rendu -->
<?= Doctype::close() ?>



<!-- Extinction du noyau -->
<?php require_once(__DIR__ . '/.kernel/quit.php');?>