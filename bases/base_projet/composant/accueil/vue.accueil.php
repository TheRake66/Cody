<!-- ================================================== -->
<!-- Creation de la page d'accueil -->

<?php new Controleur\Haut(); ?>

<main>
	
	<div id="logo">
		<img src="image/logo.png">
		<div id="borgrad">
			<h1>{PROJECT_NAME} fonctionne !</h1>
		</div>
	</div>

	<h1>Ressources</h1>
	<span>Quelques liens si vous avez besoin d'aide :</span>
	<div id="wrap">
		<a href="https://github.com/TheRake66/Cody-PHP">
			<img src="image/github.png">
			<span>GitHub de Cody-PHP</span>
		</a>
		<a href="https://www.youtube.com/results?search_query=Cody-PHP">
			<img src="image/youtube.png">
			<span>Rechercher sur YouTube</span>
		</a>
		<a href="https://www.google.com/search?q=Cody-PHP">
			<img src="image/google.png">
			<span>Recherche sur Google</span>
		</a>
		<a href="https://github.com/TheRake66/Cody-PHP/documents/">
			<img src="image/cli.png">
			<span>Documentation des commandes</span>
		</a>
	</div>

	<h1>Commencement</h1>
	<span>Les étapes de base d'un nouveau projet :</span>
	<div id="wrap">
		<div id="cd">
			<img src="image/plus.png">
			<span>Changer de dossier</span>
		</div>
		<div id="ls">
			<img src="image/plus.png">
			<span>Lister les projets existants</span>
		</div>
		<div id="new">
			<img src="image/plus.png">
			<span>Créer un projet</span>
		</div>
		<div id="com">
			<img src="image/plus.png">
			<span>Créer un composant</span>
		</div>
		<div id="obj">
			<img src="image/plus.png">
			<span>Créer un objet</span>
		</div>
	</div>

	<div id="console">
		<span id="barre">0 1 r</span>
		<span id="input"></span>
	</div>


	<div id="icone">
		<a href="https://www.facebook.com/TheRake66/">
			<img src="image/facebook.png">
		</a>
		<a href="https://www.instagram.com/therake6666/">
			<img src="image/instagram.png">
		</a>
		<a href="https://twitter.com/ThibaultBustos">
			<img src="image/twitter.png">
		</a>
		<a href="https://www.linkedin.com/in/thibault-bustos-6a000a198/">
			<img src="image/linkedin.png">
		</a>
		<a href="https://github.com/TheRake66">
			<img src="image/github2.png">
		</a>
	</div>

</main>

<?php new Controleur\Bas(); ?>

<!-- ================================================== -->



<!-- ================================================== -->
<!-- Inclusion des fichiers -->
<link rel="stylesheet/less" type="text/css" href="composant/accueil/style.accueil.less">
<script type='text/javascript' src='composant/accueil/script.accueil.js'></script>
<!-- ================================================== -->
