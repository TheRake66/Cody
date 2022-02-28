// Librairie Url
export default class Url {

	/**
	 * Accede a une url
	 * 
	 * @param {string} route la route
	 * @param {array} param les param
	 * @param {string} addback le back
	 */
	static go(route, param = [], addback = false) {
        window.location.href = Url.build(route, param, addback);
	}

	
	/**
	 * Recharge la page
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
		return Url.paramGet('b') ?? '';
	}

	
	/**
	 * Retourne l'url actuelle
	 * 
	 * @return {string} l'url
	 */
	static current() {
		return window.location;
	}

	
	/**
	 * Contruit une url
	 * 
	 * @param {string} route la route
	 * @param {array} param les param
	 * @param {string} addback le back
	 * @return {string} le nouvel url
	 */
	static build(route, param = [], addback = false) {
		let url = '?r=' + route;

        for (let name in param) {
            let value = param[name];
			url += '&' + name + '=' + encodeURIComponent(value);
        }

		if (addback) {
			url += '&b=' + encodeURIComponent(window.location);
		}

		return url;
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