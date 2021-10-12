<?php

// ####################################################################################################
// Fonction d'autoconnexion via jeton expirable
if (!isset($_SESSION['utilisateur']) && isset($_COOKIE['jeton'])) {
	$jeton = $_COOKIE['jeton'];
	$user = daoUtilisateur::autoConnexion($jeton);
	$expired = true;

	if (!is_null($user)) {
		// Recupere la date d'expiration
		$date = daoJeton::getDateExpire($jeton);
		if (!is_null($date)) {
			$now = new DateTime('now');
			$expire = new DateTime($date);
			// Compare la date avec la date d'expiration
			if ($now < $expire) {
				// Si pas expirer on prolonge la date
				daoJeton::setDateExpire($jeton);
			} else {
				// Si expirer on renouvele le jeton
				$newjeton = Security::genererRandom(100);
				daoJeton::updateJeton($jeton, $newjeton);
				$jeton = $newjeton;
			}
			setcookie("jeton", $jeton, time() + 31*60*60*24); // 31 jours
			$_SESSION['utilisateur'] = $user;
			$expired = false;
		}
	}

	if ($expired) {
		Dispatcher::messageFail("Session expirée veuillez vous reconnecter !", "connexion");
	}
}
// ####################################################################################################





// ####################################################################################################
// Header present dans haut.php
$formHeader = new Formulaire('post', 'index.php', 'fBandeau', 'fBandeau');
$formHeader->ajouterComposantTab();
$formHeader->creerFormulaire();
// ####################################################################################################





// ####################################################################################################
// Menu de navigation
$menuNav = new Formulaire('post', 'index.php', 'fMennuNav', 'fMennuNav');

if (isset($_GET['messageSuccess'])) {
		$menuNav->ajouterComposantLigne($menuNav->creerLabel("messageSuccess", "messageSuccess", $_GET['messageSuccess']));
} elseif (isset($_GET['messageFail'])) {
		$menuNav->ajouterComposantLigne($menuNav->creerLabel("messageFail", "messageFail", $_GET['messageFail']));
}

$menuNav->ajouterComposantTab();
$menuNav->creerFormulaire();
// ####################################################################################################





// ####################################################################################################
// Footer present dans bas.php
$formFooter = new Formulaire('post', 'index.php', 'fFooter', 'fFooter');
$formFooter->ajouterComposantTab();
$formFooter->creerFormulaire();
// ####################################################################################################




// ####################################################################################################
// Dispatch vers le bon controleur
require_once Dispatcher::controleur();
// ####################################################################################################




