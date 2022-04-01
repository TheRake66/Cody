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
     * @param {HTMLElement} parent le parent de l'élément HTML (par défaut body)
     * @returns {HTMLCollectionOf<any>} l'élément
     */
    static tag(tag, parent = document.body) {
        return parent.getElementsByTagName(tag);
    }


    /**
     * Retourne un élément HTML depuis un selecteur CSS
     * 
     * @param {string} selector le sélecteur css
     * @param {HTMLElement} parent le parent de l'élément HTML (par défaut body)
     * @returns {HTMLElement} l'élément
     */
    static query(selector, parent = document.body) {
        return parent.querySelector(selector);
    }
    

    /**
     * Retourne des éléments HTML depuis leur selecteur CSS
     * 
     * @param {string} selector le sélecteur css
     * @param {HTMLElement} parent le parent de l'élément HTML (par défaut body)
     * @returns {NodeListOf<Element>} les éléments
     */
    static queryAll(selector, parent = document.body) {
        return parent.querySelectorAll(selector);
    }


    /**
     * Vide le contenu d'un élément HTML
     * 
     * @param {HTMLElement} el l'élément HTML (par défaut body)
     */
    static clear(el = document.body) { 
        el.innerHTML = '';
    }
    

    /**
     * Detruit un élément HTML
     * 
     * @param {HTMLElement} el l'élément HTML (par défaut body)
     */
    static destroy(el = document.body) {
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
     * Insert du code HTML dans un élément HTML a une position donnée
     * 
     * @param {HTMLElement} html le contenu HTML
     * @param {HTMLElement} el l'élément HTML (par défaut body)
     * @param {HTMLElement} position l'endroit où insérer le contenu HTML par défaut (beforeend)
     */
    static insert(html, el = document.body, position = 'beforeend') {
        el.insertAdjacentHTML(position, html);
    }


    /**
     * Vide le contenu d'un élément HTML puis insert du code HTML dedans
     * 
     * @param {HTMLElement} html le contenu HTML
     * @param {HTMLElement} el l'élément HTML (par défaut body)
     */
    static replace(html, el) {
        el.innerHTML = html;        
    }


    /**
     * Insert un élément HTML dans le DOM
     * 
     * @param {HTMLElement} el l'élément HTML à insérer
     * @param {HTMLElement} parent le parent de l'élément HTML (par défaut body)
     * @returns {HTMLElement} l'élément HTML inséré
     */
    static append(el, parent = document.body) {
        parent.appendChild(el);
        return el;
    }


    /**
     * Retourne les coordonnées d'une cellule d'un tableau
     * 
     * @param {Element} el la cellule (ou un de ses enfants)
     * @returns {Object} les coordonnées de la cellule (x, y)
     */
    static cellCoor(el) {
        let coor = {
            x: 0,
            y: 0
        };
        while (el.tagName !== 'TD') {
            el = el.parentElement;
        }
        coor.x = el.cellIndex;
        coor.y = el.parentElement.rowIndex;
        return coor;
    }
    
}