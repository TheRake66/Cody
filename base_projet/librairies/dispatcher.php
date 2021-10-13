<?php

// ####################################################################################################
class Dispatcher {

    // -------------------------------------------------------
	public const SITE_NAME = 'gfframework'; 
	public const DEF_DISPATCH = 'accueil'; 
    // -------------------------------------------------------



    // -------------------------------------------------------
	// Recupere dans l'url le menu ou en defini un si existe pas
	public static function url() {
		if(isset($_GET[self::SITE_NAME]) && !empty($_GET[self::SITE_NAME])) {
			$_SESSION[self::SITE_NAME] = $_GET[self::SITE_NAME];
		} elseif(!isset($_SESSION[self::SITE_NAME])) {
			$_SESSION[self::SITE_NAME] = self::DEF_DISPATCH;
		}
	}
    // -------------------------------------------------------



    // -------------------------------------------------------
	public static function dispatch($type){
		self::url();

		/*
		Redirige via le label d'un code type utilisateur d'une classe daoUtilisateur
		Exemple : Un utilisateur de type SC (avec le label Secrétaire) redirigera 
		sur le controleur ./controleurs/Secrétaire/controleurFoo.php*/

		if (isset($_SESSION['utilisateur'])) {
			$newpath =  $type . "/" . daoTypeUtilisateur::statusByCode($_SESSION['utilisateur']->CodeTypeUtilisateur) . "/" . substr($type, 0, -1) . ucfirst($_SESSION[self::SITE_NAME]) . ".php";
			if (is_file($newpath)) {
				return $newpath;
			}
		}

		$file = $type . "/" . substr($type, 0, -1) . ucfirst($_SESSION[self::SITE_NAME]) . ".php";
		if (is_file($file)) {
			return $file;
		}

		$_SESSION[self::SITE_NAME] = self::DEF_DISPATCH;
		self::header($_SESSION[self::SITE_NAME]);
	}
    // -------------------------------------------------------



    // -------------------------------------------------------
	public static function vue() {
		return self::dispatch("vues");
	}

	public static function controleur() {
		return self::dispatch("controleurs");
	}

	public static function header($unMenu, $scrollTo = "") {
		header('location: index.php?' . self::SITE_NAME . '=' . $unMenu . "#" . $scrollTo);
		exit;
	}

	public static function message($typeMessage, $unMessage, $unMenu, $scrollTo = "") {
		self::header($unMenu . "&" . $typeMessage . "=" . urlencode($unMessage), $scrollTo);
	}

	public static function messageSuccess($unMessage, $unMenu, $scrollTo = "") {
		self::message('messageSuccess', $unMessage, $unMenu, $scrollTo);
	}

	public static function messageFail($unMessage, $unMenu, $scrollTo = "") {
		self::message('messageFail', $unMessage, $unMenu, $scrollTo);
	}

	public static function messageInfo($unMessage, $unMenu, $scrollTo = "") {
		self::message('messageInfo', $unMessage, $unMenu, $scrollTo);
	}
    // -------------------------------------------------------
	
	
}
// ####################################################################################################
