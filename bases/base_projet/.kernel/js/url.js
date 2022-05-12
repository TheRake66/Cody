import Http from './http.js';
import Html from './html.js';



/**
 * Librairie de traitement des URL
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Librairie
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Url {

    /**
     * Les methodes d'envoie
     * 
     * @type {string}
     */
    static METHOD_GET = 'GET';
    static METHOD_POST = 'POST';


	/**
	 * Accede a une url
	 * 
	 * @param {string} url l'url
	 * @returns {void}
	 */
	static location(url) {
		window.location.href = url;
	}

	
	/**
	 * Contruit une url
	 * 
	 * @param {string} route la route
	 * @param {array} params les parametres
	 * @param {string} addback si on ajoute le parametre de retour
	 * @return {string} l'url
	 */
	static build(route, params = {}, addback = false) {
		let url = Url.getRoot() + route;
		if (addback) {
			params.redirectUrl = Url.current();
		}
		if (Object.keys(params).length !== 0 || addback) {
			url += '?' + (new URLSearchParams(params)).toString();
		}
		return url;
	}


	/**
	 * Accede a une url dans l'application
	 * 
	 * @param {string} route la route vers le composant
	 * @param {array} param les parametres
	 * @param {boolean} addback si on ajoute le parametre de retour
	 * @param {string} method la methode (GET, POST)
     * @returns {void}
	 */
	static go(route, params = [], addback = false, method = Url.METHOD_GET) {
		if (method === Http.METHOD_GET) {
			window.location.href = Url.build(route, params, addback);
		} else if (method === Http.METHOD_POST) {
			let f = Html.create('form', {
				method: 'post',
				action: Url.build(route)
			});
			Object.entries(obj).forEach(entry => {
				const [key, value] = entry;
				f.append(Html.create('input', {
					type: 'hidden',
					name: key,
					value: value
				}));
			});
			if (addback) {
				f.append(Html.create('input', {
					type: 'hidden',
					name: 'redirectUrl',
					value: Url.getCurrent()
				}));
			}
			Html.append(f);
			f.submit();
			f.remove();
		}
	}

	
	/**
	 * Recharge la page
	 * 
     * @returns {void}
	 */
	static reload() {
		window.location.reload();
	}


	/**
	 * Retourne le parametre de retour
	 * 
	 * @return {string} le retour
	 */
	static getBack() {
		return Url.paramGet('redirectUrl') ?? undefined;
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


    /**
	 * Remplace un parametre de l'url
	 * 
	 * @param {string} name le nom du parametre
	 * @param {string} value sa nouvelle valeur
	 * @return {string} le nouvel url
     */
    static changeParam(name, value) {
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
	static addParam(name, value) {
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
    static getParam(name) {
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
    static removeParam(name) {
		let queryString = window.location.search;
		let urlParams = new URLSearchParams(queryString);
		urlParams.delete(name);
		return queryString;
    }

}