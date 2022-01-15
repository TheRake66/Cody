// Librairie Url
class Url {

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
	 * Defini un href avec le parametre de retour
	 * 
	 * @return {string} le href
	 */
	static back() {
		return 'href="' + Url.paramGet('back') + '"';
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
		let url = '?redirect=' + route;

        for (let name in param) {
            let value = param[name];
			url += '&' + name + '=' + encodeURIComponent(value);
        }

		if (addback) {
			url += '&back=' + encodeURIComponent(window.location);
		}

		return url;
	}


    /**
	 * Remplace un parametre de l'url
	 * 
	 * @param {string} param le nom du parametre
	 * @param {string} remplace sa nouvelle valeur
	 * @return {string} le nouvel url
     */
    static changeGet(param, remplace) {
        let regex = new RegExp("([?;&])" + param + "[^&;]*[;&]?");
        let query = window.location.search.replace(regex, "$1").replace(/&$/, '');

        return (query.length > 2 ? query + "&" : "?") + (remplace ? param + "=" + remplace : '');
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