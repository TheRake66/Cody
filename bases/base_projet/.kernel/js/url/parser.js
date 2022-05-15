
/**
 * Librairie gerant les parties de l'url
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Parser {

	/**
	 * Retourne le parametre de retour
	 * 
	 * @return {string} le retour
	 */
	static getBack() {
		return Url.paramGet('redirect_url') ?? undefined;
	}


	/**
	 * Retourne le protocol actuel (http ou https)
	 * 
	 * @returns {string} le protocole
	 */
	static getProtocol() {
		return window.location.protocol.replace(':', '');
	}


	/**
	 * Retourne l'adresse du serveur (https://localhost:6600)
	 * 
	 * @returns {string} l'adresse
	 */
	static getHost() {
		return window.location.origin;
	}


	/**
	 * Retourne l'url sans les parametres
	 * 
	 * @returns {string} l'url sans les parametres
	 */
	static getRoot() {
		return Url.getHost();
	}

	
	/**
	 * Retourne le chemin de l'url
	 * 
	 * @returns {string} le chemin
	 */
	static getPath() {
		return window.location.pathname;
	}

	
	/**
	 * Retourne l'url actuelle
	 * 
	 * @return {string} l'url
	 */
	static getCurrent() {
		return window.location.href;
	}

}