import HTTP from '../communication/http.js';
import Builder from '../html/builder.js';
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
	 * Accede a une url
	 * 
	 * @param {string} url l'url
	 * @returns {void}
	 */
	static change(url) {
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
	static go(route, params = [], addback = false, method = HTTP.METHOD_GET) {
		if (method === HTTP.METHOD_GET) {
			window.location.href = Location.build(route, params, addback);
		} else if (method === HTTP.METHOD_POST) {
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
	 * Contruit une url
	 * 
	 * @param {string} route la route
	 * @param {array} params les parametres
	 * @param {string} addback si on ajoute le parametre de retour
	 * @return {string} l'url
	 */
	static build(route, params = {}, addback = false) {
		let url = Parser.root() + route;
		if (addback) {
			params.redirect_url = Parser.current();
		}
		if (Object.keys(params).length !== 0 || addback) {
			url += '?' + (new URLSearchParams(params)).toString();
		}
		return url;
	}

}