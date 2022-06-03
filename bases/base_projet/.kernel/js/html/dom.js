/**
 * Librairie gerant le DOM (Document Object Model)
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Dom {
    
    /**
     * Vide le contenu d'un élément HTML
     * 
     * @param {HTMLElement} el l'élément HTML (par défaut body)
     * @returns {void}
     */
    static clear(el = document.body) { 
        el.innerHTML = '';
    }
    

    /**
     * Detruit un élément HTML
     * 
     * @param {HTMLElement} el l'élément HTML (par défaut body)
     * @returns {void}
     */
    static destroy(el = document.body) {
        el.remove();
    }


    /**
     * Insert du code HTML dans un élément HTML a une position donnée
     * 
     * @param {string} html le contenu HTML
     * @param {HTMLElement} el l'élément HTML (par défaut body)
     * @param {string} position l'endroit où insérer le contenu HTML par défaut (beforeend)
     * @returns {void}
     */
    static insert(html, el = document.body, position = 'beforeend') {
        el.insertAdjacentHTML(position, html);
    }


    /**
     * Vide le contenu d'un élément HTML puis insert du code HTML dedans
     * 
     * @param {HTMLElement} html le contenu HTML
     * @param {HTMLElement} el l'élément HTML (par défaut body)
     * @returns {void}
     */
    static replace(html, el) {
        el.innerHTML = html;        
    }


    /**
     * Insert un élément HTML dans le DOM
     * 
     * @param {HTMLElement} el l'élément HTML à insérer
     * @param {HTMLElement} parent le parent de l'élément HTML (par défaut body)
     * @returns {void}
     */
    static append(el, parent = document.body) {
        parent.appendChild(el);
    }


    /**
     * Supprime un élément HTML dan le DOM
     * 
     * @param {HTMLElement} el l'élément HTML à supprimer
     * @param {HTMLElement} parent le parent de l'élément HTML (par défaut body)
     */
    static remove(el, parent = document.body) {
        parent.removeChild(el);
    }

}