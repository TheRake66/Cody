
/**
 * Librairie gerant les parametres de l'url
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Query {

    /**
	 * Remplace un parametre de l'url
	 * 
	 * @param {string} name le nom du parametre
	 * @param {string} value sa nouvelle valeur
	 * @return {string} le nouvel url
     */
    static change(name, value) {
		let queryString = window.location.search;
		let urlParams = new URLSearchParams(queryString);
		urlParams.set(name, value);
		return queryString;
    }


	/**
	 * Ajoute un parametre de l'url
	 * 
	 * @param {string} name le nom du parametre
	 * @param {string} value sa nouvelle valeur
	 * @return {string} le nouvel url
	 */
	static add(name, value) {
		let queryString = window.location.search;
		let urlParams = new URLSearchParams(queryString);
		urlParams.append(name, value);
		return queryString;
	}


    /**
	 * Retourne un parametre de l'url
     * 
     * @param {string} name nom du parametre
     * @returns {string} valeur du parametre
     */
    static get(name) {
		let queryString = window.location.search;
		let urlParams = new URLSearchParams(queryString);
		return urlParams.get(name);
    }


    /**
	 * Supprime un parametre de l'url
     * 
     * @param {string} name nom du parametre
     * @returns {string} le nouvel url
     */
    static remove(name) {
		let queryString = window.location.search;
		let urlParams = new URLSearchParams(queryString);
		urlParams.delete(name);
		return queryString;
    }

}