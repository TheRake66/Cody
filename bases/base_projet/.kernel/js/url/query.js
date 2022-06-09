
/**
 * Librairie gérant les paramètres de l'URL.
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Query {

    /**
	 * Remplace un paramètre de l'URL.
	 * 
	 * @param {string} name Le nom du paramètre.
	 * @param {string} value Sa nouvelle valeur.
	 * @return {string} La nouvelle URL.
     */
    static change(name, value) {
		let queryString = window.location.search;
		let urlParams = new URLSearchParams(queryString);
		urlParams.set(name, value);
		return queryString;
    }


	/**
	 * Ajoute un paramètre à l'URL.
	 * 
	 * @param {string} name Le nom du paramètre.
	 * @param {string} value Sa valeur.
	 * @return {string} La nouvelle URL.
	 */
	static add(name, value) {
		let queryString = window.location.search;
		let urlParams = new URLSearchParams(queryString);
		urlParams.append(name, value);
		return queryString;
	}


    /**
	 * Retourne la valeur d'un paramètre de l'URL.
     * 
     * @param {string} name Le nom du paramètre.
     * @returns {string} La valeur du paramètre.
     */
    static get(name) {
		let queryString = window.location.search;
		let urlParams = new URLSearchParams(queryString);
		return urlParams.get(name);
    }


    /**
	 * Retourne la valeur d'un paramètre de l'URL.
     * 
     * @param {string} name Le nom du paramètre.
     * @returns {string} La nouvelle URL.
     */
    static remove(name) {
		let queryString = window.location.search;
		let urlParams = new URLSearchParams(queryString);
		urlParams.delete(name);
		return queryString;
    }

}