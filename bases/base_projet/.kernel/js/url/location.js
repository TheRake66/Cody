import Http from '../communication/http.js';
import Builder from '../html/builder.js';
import Dom from '../html/dom.js';
import Parser from './parser.js';



/**
 * Librairie de traitement des URL.
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Location {

	/**
	 * Accède à une URL.
	 * 
	 * @param {string} url L'URL.
	 * @returns {void}
	 */
	static change(url) {
		window.location.href = url;
	}

	
	/**
	 * Recharge la page.
	 * 
     * @returns {void}
	 */
	static reload() {
		window.location.reload();
	}


	/**
	 * Accède à une URL de l'application.
	 * 
	 * @param {string} route La route.
	 * @param {array} param Les parametres de la requête.
	 * @param {boolean} addback Si on ajoute l'URL de redirection.
	 * @param {string} method La méthode de la requête.
     * @returns {void}
	 */
	static go(route, params = [], addback = false, method = Http.METHOD_GET) {
		if (method === Http.METHOD_GET) {
			window.location.href = Location.build(route, params, addback);
		} else if (method === Http.METHOD_POST) {
			let f = Builder.create('form', {
				method: 'post',
				action: Location.build(route)
			});
			Object.entries(obj).forEach(entry => {
				const [key, value] = entry;
				f.append(Builder.create('input', {
					type: 'hidden',
					name: key,
					value: value
				}));
			});
			if (addback) {
				f.append(Builder.create('input', {
					type: 'hidden',
					name: 'redirect_url',
					value: Parser.current()
				}));
			}
			Builder.append(f);
			f.submit();
			f.remove();
		}
	}

	
	/**
	 * Contruit une URL.
	 * 
	 * @param {string} route La route.
	 * @param {array} params Les parametres de la requête.
	 * @param {string} addback Si on ajoute l'URL de redirection.
	 * @return {string} L'URL.
	 */
	static build(route, params = {}, addback = false) {
		let url = Parser.root() + route;
		if (addback) {
			params.redirect_url = Parser.current();
		}
		if (params && Object.keys(params).length !== 0 || addback) {
			url += '?' + (new URLSearchParams(params)).toString();
		}
		return url;
	}

}