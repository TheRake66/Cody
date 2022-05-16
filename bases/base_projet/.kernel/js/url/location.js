import Http from '../communication/http.js';
import DOM from '../html/dom.js';
import Parser from './parser.js';



/**
 * Librairie de traitement des URL
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Location {

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
	static travel(url) {
		window.location.href = url;
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
			window.location.href = Location.build(route, params, addback);
		} else if (method === Http.METHOD_POST) {
			let f = DOM.create('form', {
				method: 'post',
				action: Url.build(route)
			});
			Object.entries(obj).forEach(entry => {
				const [key, value] = entry;
				f.append(DOM.create('input', {
					type: 'hidden',
					name: key,
					value: value
				}));
			});
			if (addback) {
				f.append(DOM.create('input', {
					type: 'hidden',
					name: 'redirect_url',
					value: Parser.getCurrent()
				}));
			}
			DOM.append(f);
			f.submit();
			f.remove();
		}
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
		let url = Parser.getRoot() + route;
		if (addback) {
			params.redirect_url = Parser.current();
		}
		if (Object.keys(params).length !== 0 || addback) {
			url += '?' + (new URLSearchParams(params)).toString();
		}
		return url;
	}

}