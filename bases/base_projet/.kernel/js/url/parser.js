import Query from './query.js';



/**
 * Librairie gérant les parties de l'URL.
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Parser {

	/**
	 * Retourne l'URL de redirection.
	 * 
	 * @return {string} 
	 */
	static back() {
		return Query.paramGet('redirect_url') ?? undefined;
	}


	/**
	 * Retourne le protocol actuel (http ou https).
	 * 
	 * @returns {string} Le protocole.
	 */
	static protocol() {
		return window.location.protocol.replace(':', '');
	}


	/**
	 * Retourne l'adresse du serveur (https://localhost:6600).
	 * 
	 * @returns {string} L'adresse du serveur.
	 */
	static host() {
		return window.location.origin;
	}


	/**
	 * Retourne l'URL sans les parametres
	 * 
	 * @returns {string} L'URL sans les parametres.
	 */
	static root() {
		return Parser.host();
	}

	
	/**
	 * Retourne le chemin de l'URL.
	 * 
	 * @returns {string} Le chemin de l'URL.
	 */
	static path() {
		return window.location.pathname;
	}

	
	/**
	 * Retourne l'URL actuelle.
	 * 
	 * @return {string} L'URL actuelle.
	 */
	static current() {
		return window.location.href;
	}

}