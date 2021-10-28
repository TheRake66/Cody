<br>
<p align="center">
    <img src="https://github.com/TheRake66/Cody-PHP/blob/main/images/logo.png" alt="logo" width=300 height=157>
<p align="center">
Framework PHP/JavaScript/HTML/Less en français.
<br>
<br>
<a href="https://github.com/TheRake66/Cody-PHP/releases/tag/cody"><img alt="Télécharger" src="https://github.com/TheRake66/Cody-PHP/blob/main/images/bouton.png"></a>
<br>
<br>
<a href="https://github.com/TheRake66/Cody-PHP/blob/main/LICENSE"><img alt="GitHub" src="https://img.shields.io/github/license/TheRake66/Cody-PHP"></a>
<img alt="Taille du code" src="https://img.shields.io/github/languages/code-size/TheRake66/Cody-PHP">
<a href="https://github.com/TheRake66/Cody-PHP/stargazers"><img alt="GitHub stars" src="https://img.shields.io/github/stars/TheRake66/Cody-PHP"></a>
<a href="https://packagist.org/packages/TheRake66/Cody-PHP"><img alt="Packagist Version" src="https://img.shields.io/packagist/v/TheRake66/Cody-PHP?color=green"></a>
</p>
</p>
<br>


### Nécessite le SDK .NET 5.0.11 (Runtime) :  https://dotnet.microsoft.com/download/dotnet/5.0


<br> 

# Qu'est-ce que c'est ?
Cody-PHP est un framework destiné à la création du site web en PHP, Javascript, HTML et Less compatible Windows, Linux et Mac.
Il permet des créer simplement et rapidement un site design et complet grace à sa structure MVC.
Et tout ça sans aucune installation, pratique pour développer sur une machine où l'on n'a pas accès au compte administrateur !

L'architecture Model View Controler apporte un cadre de travail propre travail propre alliant traitement, gestion de la base de données et affichage dynamique.

Il inclut quelques librairies comme la gestion des bases de données, l'autoloader, un système de routage et bien d'autres. Malgré ses librairies complètes, un projet vierge pèse seulement 500 Ko !

Un petit aperçu :<br>
<br>
<img alt="Console" src="https://github.com/TheRake66/Cody-PHP/blob/main/images/cmd.png">



<br>

# Liste des commandes

**Pour la version _3.5.22.0_.**<br>
```
aide                            
```
Affiche la liste des commandes disponible.<br>

```
cd [*chemin]
```
Change le dossier courant ou affiche la liste des fichiers et des dossiers du dossier courant.<br>

```
cls
```
Nettoie la console.<br>

```
com [-s|-a|-l] [nom]
```
Ajoute, liste, ou supprime un composant (controleur, vue, style, script) avec le nom spécifié.<br>

```
die
```
Quitte Cody-PHP.<br>

```
dl [url] [fichier]
```
Télécharge un fichier avec l'URL spécifiée.<br>

```
exp
```
Ouvre le projet dans l'explorateur de fichiers.<br>

```
lib [-s|-a|-l] [nom]
```
Ajoute, liste, ou supprime une librairie.<br>

```
ls
```
Affiche la liste des projets.<br>

```
maj
```
Met à jour Cody-PHP via le depot GitHub.<br>

```
new [nom]
```
Créer un nouveau projet avec le nom spécifié puis défini le dossier courant.<br>

```
obj [-s|-a|-l] [nom]
```
Ajoute, liste, ou supprime un objet (classe dto, classe dao) avec le nom spécifié.<br>

```
rep
```
Ouvre la dépôt GitHub de Cody-PHP.<br>

```
run
```
Ouvre le projet dans le navigateur.<br>

```
tra [-s|-a|-l] [nom]
```
Ajoute, liste, ou supprime un trait.<br>

```
vs
```
Ouvre le projet dans Visual Studio Code.<br>

```
wamp
```
Lance WAMP Serveur et défini le dossier courant sur le www.<br>



<br>

# Projet vierge
Lors de la création d'un nouveau projet un projet vierge sera créer, il contiendra toutes les librairies nécessaires ainsi que quelques composants et objets de base.
Il intégrera aussi cette page d'accueil :<br>
<br>
<img alt="Page d'accueil" src="https://github.com/TheRake66/Cody-PHP/blob/main/images/projet.png">
<br>
<br>
Architecture d'un projet vierge :<br>
```
projet
│   .htaccess
│   index.php
│   project.json
│
├───composant
│   │   component.json
│   │   global.js
│   │   global.less
│   │   route.php
│   │   theme.less
│   │
│   ├───accueil
│   │       cont.accueil.php
│   │       script.accueil.js
│   │       style.accueil.less
│   │       vue.accueil.php
│   │
│   ├───bas
│   │       cont.bas.php
│   │       script.bas.js
│   │       style.bas.less
│   │       vue.bas.php
│   │
│   ├───haut
│   │       cont.haut.php
│   │       script.haut.js
│   │       style.haut.less
│   │       vue.haut.php
│   │
│   ├───menu
│   │       cont.menu.php
│   │       script.menu.js
│   │       style.menu.less
│   │       vue.menu.php
│   │
│   └───panneau
│           cont.panneau.php
│           script.panneau.js
│           style.panneau.less
│           vue.panneau.php
│
├───document
│       bdd.sql
│       jmerise.mcd
│       mea.png
│       mlr.png
│
├───image
│       cli.png
│       facebook.png
│       favicon.ico
│       github.png
│       github2.png
│       google.png
│       instagram.png
│       linkedin.png
│       logo.png
│       plus.png
│       texture.svg
│       twitter.png
│       youtube.png
│
├───librairie
│   │   library.json
│   │
│   ├───js
│   │       gmap.js
│   │       http.js
│   │
│   └───php
│           autoloader.php
│           convert.php
│           database.php
│           debug.php
│           routeur.php
│           security.php
│
└───modele
    │   database.json
    │   object.json
    │   trait.json
    │
    ├───dao
    │       jeton.php
    │       typeutilisateur.php
    │       utilisateur.php
    │
    ├───dto
    │       jeton.php
    │       typeutilisateur.php
    │       utilisateur.php
    │
    └───reflect
            hydrate.php
```



<br>

# Système de routage
**Pour les versions de PHP antérieures à la version 8 veuillez vous référer à la section package.**<br>
Inclus dans un projet vierge, le fichier de routage (route.php) permet d'appeler le\les bon(s) controleur(s).
Pour ce faire il faut lui donner une adresse de route, puis une fonction anonyme comme ceci :<br>
```php
# Route vers Accueil
Routeur::go('accueil', function() {
	new Controleur\Accueil();
});

# Route vers Introuvable
Routeur::go('404', function() {
	new Controleur\Introuvable();
});
``` 
Vous pouvez également définir une route en cas de route introuvale :<br>
```php
# Route vers Introuvable
Routeur::introuvable('404');
``` 
Et aussi définir une route par défaut si aucune route n'est demandée ou si aucune route pour un cas de route introuvable n'est définie :<br>
```php
# Route vers Accueil
Routeur::defaut('accueil');
```
**Si aucune de ces deux routes n'est définie, le routeur utilisera la première route existante.**<br> 



<br>

# Les packages
Via la commande ```dl```, vous pouvez installer des packages développés par un tiers comme ceci :<br> 
```
dl https://raw.githubusercontent.com/TheRake66/Cody-PHP/main/documents/routeur.php librairie/php/routeur.php
```
Afin de télécharger le routeur supportant les versions de PHP antérieures a la version 8.



<br>

# L'autoloader
Également inclus dans un projet vierge, l'autoloader permet de charger au fur et à mesure les classes demandées.
Pour la classe :<br>
```php
namespace Controleur;

class Accueil {

}
```
Il chargera le fichier :<br>
```php
require_once "composant/accueil/cont.accueil.php";
```



<br>

# Licence
*Licence MIT<br>
<br>
Copyright (c) 2021 TheRake66<br>
<br>
Permission est accordée, sans frais, à toute personne obtenant une copie
de ce logiciel et des fichiers de documentation associés (le "Logiciel"), pour traiter
dans le Logiciel sans restriction, y compris, sans limitation, les droits
utiliser, copier, modifier, fusionner, publier, distribuer, sous-licencier et/ou vendre
copies du Logiciel et de permettre aux personnes auxquelles le Logiciel est
fourni à cet effet, sous réserve des conditions suivantes :<br>
<br>
L'avis de droit d'auteur ci-dessus et cet avis d'autorisation doivent être inclus dans tous les
des copies ou des parties substantielles du Logiciel.<br>
<br>
LE LOGICIEL EST FOURNI « EN L'ÉTAT », SANS GARANTIE D'AUCUNE SORTE, EXPRESSE OU
IMPLICITE, Y COMPRIS MAIS NON LIMITÉ AUX GARANTIES DE QUALITÉ MARCHANDE,
APTITUDE A UN USAGE PARTICULIER ET NON CONTREFAÇON. EN AUCUN CAS, LE
LES AUTEURS OU TITULAIRES DE DROITS D'AUTEUR SERONT RESPONSABLES DE TOUTE RÉCLAMATION, DOMMAGES OU AUTRE
RESPONSABILITÉ, QUE CE SOIT DANS UNE ACTION CONTRACTUELLE, DÉLICTUELLE OU AUTRE, DÉCOULANT DE,
HORS OU EN RELATION AVEC LE LOGICIEL OU L'UTILISATION OU D'AUTRES OPÉRATIONS DANS LE
LOGICIEL.<br>*
