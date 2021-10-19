<?php
// ####################################################################################################
// Inclue l'autoloader
require_once 'librairies/autoLoader.php';
// Demarre la session
session_start();
// ####################################################################################################
?>



<!DOCTYPE html>
<html lang='fr'>

	<head>
		<meta charset='utf-8' />
		<title>Cody-PHP</title>
		<link rel='icon' href='images/favicon.ico'/>
	</head>

	<body>
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
					setcookie('jeton', $jeton, time() + 31*60*60*24); // 31 jours
					$_SESSION['utilisateur'] = $user;
					$expired = false;
				}
			}

			if ($expired) {
				Dispatcher::messageFail('Session expirée veuillez vous reconnecter !', 'connexion');
			}
		}
		// ####################################################################################################





		// ####################################################################################################
		// Redirige vers le bon controleur
		require_once Dispatcher::controleur();	
		// ####################################################################################################
		?>
	</body>
	
</html>
