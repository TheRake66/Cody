/**
 * Librairie de traitement des URL
 */
export default class Url {

	/**
	 * Accede a une url
	 * 
	 * @param {string} route la route
	 * @param {array} param les param
	 * @param {string} addback le back
     * @returns {void}
	 */
	static go(route, param = [], addback = false) {
        window.location.href = Url.build(route, param, addback);
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
	static back() {
		return Url.paramGet('redirectUrl') ?? '';
	}

	
	/**
	 * Retourne l'url actuelle
	 * 
	 * @return {string} l'url
	 */
	static current() {
		return window.location.href;
	}


	/**
	 * Retourne l'url sans les parametres
	 * 
	 * @returns {string} l'url sans les parametres
	 */
	static root() {
		return Url.current().split('?')[0];
	}


	/**
	 * Formatte un tableau ou un objet en paramettre
	 * 
	 * @param {array} param l'objet ou le tableau a convertir
	 * @returns {string} les parametres formates
	 */
	static objectToParam(param) {
		let str = ''
		for (let name in param) {
            let value = param[name];
			str += (str !== '' ? '&' : '') + name + '=' + encodeURIComponent(value ?? '');
        }
		return str;
	}

	
	/**
	 * Contruit une url
	 * 
	 * @param {string} route la route
	 * @param {array} param les param
	 * @param {string} addback le back
	 * @return {string} le nouvel url
	 */
	static build(route, param = {}, addback = false) {
		let _ = {};
        _['routePage'] = route;
		if (addback) {
			_['redirectUrl'] = encodeURIComponent(Url.current());
		}
        return `${Url.root()}?${Url.objectToParam(Object.assign({}, _, param))}`;
	}


    /**
	 * Remplace un parametre de l'url
	 * 
	 * @param {string} name le nom du parametre
	 * @param {string} value sa nouvelle valeur
	 * @return {string} le nouvel url
     */
    static changeGet(name, value) {
        let regex = new RegExp("([?;&])" + name + "[^&;]*[;&]?");
        let query = window.location.search.replace(regex, "$1").replace(/&$/, '');
        return (query.length > 2 ? query + "&" : "?") + (value ? name + "=" + value : '');
    }


    /**
     * Retourne un parametre passe en GET
     * 
     * @param {string} name nom du parametre
     * @returns {string} valeur du parametre
     */
    static paramGet(name) {
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
    static paramRem(name) {
        let queryString = window.location.search;
        let urlParams = new URLSearchParams(queryString);
        urlParams.delete(name);
        return queryString;
    }

}