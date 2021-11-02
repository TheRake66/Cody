<!-- ================================================== -->
<!-- Creation de la page d'accueil -->

<?php new Controleur\Haut(); ?>

<main>

	<div id="top">
		<div>
			<div>
				<h1>{PROJECT_NAME} fonctionne !</h1>
				<p>Votre projet fonctionne, ajouter de nouveaux composants et créer votre application comme vous le souhaitez. N'hésites pas à nous la partager !</p>
			</div>
		</div>
	</div>

	<div id="mid">
		<h1>Ressources</h1>
		<span>Quelques liens si vous avez besoin d'aide.</span>
		<div>
			<a href="https://github.com/TheRake66/Cody">
				<img src="image/github.png">
				<span>GitHub de Cody</span>
			</a>
			<a href="https://www.youtube.com/results?search_query=Cody">
				<img src="image/youtube.png">
				<span>Rechercher sur YouTube</span>
			</a>
			<a href="https://www.google.com/search?q=Cody">
				<img src="image/google.png">
				<span>Recherche sur Google</span>
			</a>
			<a href="https://github.com/TheRake66/Cody/documents/">
				<img src="image/cli.png">
				<span>Documentation des commandes</span>
			</a>
		</div>

		<h1>Commencement</h1>
		<span>Les étapes de base d'un nouveau projet :</span>
		<div>
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
	</div>
	
</main>

<?php new Controleur\Bas(); ?>

<!-- ================================================== -->



<!-- ================================================== -->
<!-- Inclusion des fichiers -->
<link rel="stylesheet/less" type="text/css" href="composant/accueil/style.accueil.less">
<script type='text/javascript' src='composant/accueil/script.accueil.js'></script>
<!-- ================================================== -->
