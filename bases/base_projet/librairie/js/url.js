class Url {

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
    };

};