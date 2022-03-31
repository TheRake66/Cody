/**
 * Librairie de gestion du DOM
 */
export default class Html {
    
    
    /**
     * Retourne un élément HTML via son ID
     * 
     * @param {string} id l'id de l'élément
     * @returns {HTMLElement} l'élément
     */
    static id(id) {
        return document.getElementById(id);
    }


    /**
     * Retourne des éléments HTML via leur tag
     * 
     * @param {string} id l'id de l'élément
     * @param {HTMLElement} parent le parent de l'élément HTML par défaut (body)
     * @returns {HTMLCollectionOf<any>} l'élément
     */
    static tag(tag, parent = document.body) {
        return parent.getElementsByTagName(tag);
    }


    /**
     * Retourne un élément HTML depuis un selecteur CSS
     * 
     * @param {string} selector le sélecteur css
     * @param {HTMLElement} parent le parent de l'élément HTML par défaut (body)
     * @returns {HTMLElement} l'élément
     */
    static query(selector, parent = document.body) {
        return parent.querySelector(selector);
    }
    

    /**
     * Retourne des éléments HTML depuis leur selecteur CSS
     * 
     * @param {string} selector le sélecteur css
     * @param {HTMLElement} parent le parent de l'élément HTML par défaut (body)
     * @returns {NodeListOf<Element>} les éléments
     */
    static queryAll(selector, parent = document.body) {
        return parent.querySelectorAll(selector);
    }


    /**
     * Vide le contenu d'un élément HTML
     * 
     * @param {HTMLElement} el l'élément HTML
     */
    static clear(el) { 
        el.innerHTML = '';
    }
    

    /**
     * Detruit un élément HTML
     * 
     * @param {HTMLElement} el l'élément HTML
     */
    static destroy(el) {
        el.remove();
    }


    /**
     * Crée un élément HTML
     * 
     * @param {string} tag le tag de l'élément
     * @param {Object} attr les attributs de l'élément
     * @param {string} content le contenu HTML de l'élément
     * @returns {HTMLElement} l'élément créé
     */
    static create(tag, attr = null, content = null) {
        let el = document.createElement(tag);
        if (attr) {
            for (let key in attr) {
                el.setAttribute(key, attr[key]);
            }
        }
        if (content) {
            el.innerHTML = content;
        }
        return el;
    }


    /**
     * Insert du code HTML dans le DOM
     * 
     * @param {HTMLElement} html le contenu HTML
     * @param {HTMLElement} parent le parent de l'élément HTML par défaut (body)
     */
    static insert(html, parent = document.body) {
        parent.insertAdjacentHTML('beforeend', html);
    }


    /**
     * Insert un élément HTML dans le DOM
     * 
     * @param {HTMLElement} el l'élément HTML à insérer
     * @param {HTMLElement} parent le parent de l'élément HTML par défaut (body)
     * @returns {HTMLElement} l'élément HTML inséré
     */
    static append(el, parent = document.body) {
        parent.appendChild(el);
        return el;
    }
    
}