
/**
 * Librairie de gestion des cookies.
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Cookie {

    /**
     * Définit un cookie.
     * 
     * @param {string} name Le nom du cookie.
     * @param {string} value La valeur du cookie.
     * @param {Number} days Le nombre de jours avant expiration.
     * @param {string} path Le chemin du cookie.
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
     * Récupère un cookie.
     * 
     * @param {string} name Le nom du cookie.
     * @returns {string} La valeur du cookie.
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
     * Vérifie si un cookie existe.
     * 
     * @param {string} name Le nom du cookie.
     * @returns {boolean} True si le cookie existe.
     */
    static has(name) {
        return document.cookie.includes(name);
    }


    /**
     * Supprime un cookie.
     * 
     * @param {string} name Le nom du cookie.
     * @returns {void}
     */
    static remove(name) {
        document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
    }

}