<?php 
require_once '.kernel/init.php';
use Kernel\Router;
use Kernel\Html;
?>



<?= Html::begin() ?>

	<!-- Routage vers le bon controleur -->
	<body>
		<?php Router::routing(); ?>
	</body>
	
<?= Html::end() ?>



<?php require_once '.kernel/quit.php'; ?>