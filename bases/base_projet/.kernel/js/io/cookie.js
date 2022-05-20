
/**
 * Librairie de gestion des cookies
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Cookie {

    /**
     * Definie un cookie
     * 
     * @param {string} name le nom du cookie
     * @param {string} value la valeur du cookie
     * @param {Number} days la durée de vie du cookie en jours
     * @param {string} path le chemin du cookie
     * @returns {void}
     */
    static set(name, value = null, days = null, path = null) {
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
    static get(name) {
        let c = document.cookie
            .split(';')
            .filter(cookie => cookie.trim().startsWith(`${name}=`));
        if (c.length > 0) {
            return c[0].trim().split('=')[1];
        }
    }


    /**
     * Verifie si un cookie existe
     * 
     * @param {string} name le nom du cookie
     * @returns {boolean} true si le cookie existe
     */
    static has(name) {
        return document.cookie.includes(name);
    }


    /**
     * Supprime un cookie
     * 
     * @param {string} name le nom du cookie
     * @returns {void}
     */
    static remove(name) {
        document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
    }

}