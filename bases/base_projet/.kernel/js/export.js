import Html from './html.js';



/**
 * Librairie d'exportation des donnees
 */
export default class Export {


    /**
	 * Telecharge un contenu
	 * 
	 * @param {any} content le contenu a telecharger
	 * @param {string} file nom du fichier
     * @returns {void}
     */
    static download(content, file = 'download.txt') {
        let a = Html.create('a', {
            href: 'data:text/plain;charset=utf-8,' + encodeURIComponent(content),
            download: file,
            style: 'display:none'
        });
        Html.append(a);
        a.click();
        Html.remove(a);
    }
    

    /**
     * Affiche du texte dans un nouvel onglet
     * 
     * @param {string} content le contenu de la page
     * @returns {void}
     */
    static fullScreen(content) {
        let tab = window.open('about:blank', '_blank');
        tab.document.write('<pre>' + content + '</pre>');
        tab.document.close();
    }


    /**
     * Definie un cookie
     * 
     * @param {string} name le nom du cookie
     * @param {string} value la valeur du cookie
     * @param {Number} days la durée de vie du cookie en jours
     * @param {string} path le chemin du cookie
     * @returns {void}
     */
    static setCookie(name, value = null, days = null, path = null) {
        let expires = null;
        if (days) {
            let date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = '; expires=' + date.toUTCString();
        }
        document.cookie = `${name}=${value || ''}${expires || ''}; path=${path || '/'}`;
    }


    /**
     * Recupere un cookie
     * 
     * @param {string} name le nom du cookie
     * @returns {string} la valeur du cookie
     */
    static getCookie(name) {
        let c = document.cookie
            .split(';')
            .filter(cookie => cookie.trim().startsWith(`${name}=`));
        if (c.length > 0) {
            console.log(c[0].split('='));
            return c[0].trim().split('=')[1];
        }
    }


    /**
     * Supprime un cookie
     * 
     * @param {string} name le nom du cookie
     * @returns {void}
     */
    static deleteCookie(name) {
        document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
    }

}