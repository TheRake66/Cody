import Finder from './finder.js';
import Builder from './builder.js';



/**
 * Librairie gérant le DOM (Document Object Model).
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Dom {
    
    /**
     * Vide le contenu d'un élément HTML.
     * 
     * @param {HTMLElement} el l'élément HTML.
     * @returns {void}
     */
    static clear(el = document.body) { 
        el.innerHTML = '';
    }
    

    /**
     * Détruit un élément HTML.
     * 
     * @param {HTMLElement} el l'élément HTML.
     * @returns {void}
     */
    static destroy(el = document.body) {
        el.remove();
    }


    /**
     * Insert du code HTML dans un élément HTML a une position donnée.
     * 
     * @param {string} html Le contenu HTML.
     * @param {HTMLElement} el L'élément HTML.
     * @param {string} position L'endroit où insérer le contenu HTML.
     * @returns {void}
     */
    static insert(html, el = document.body, position = 'beforeend') {
        el.insertAdjacentHTML(position, html);
    }


    /**
     * Vide le contenu d'un élément HTML puis insert du code HTML dedans.
     * 
     * @param {HTMLElement} html Le contenu HTML.
     * @param {HTMLElement} el L'élément HTML.
     * @returns {void}
     */
    static replace(html, el) {
        el.innerHTML = html;        
    }


    /**
     * Insert un élément HTML dans le DOM.
     * 
     * @param {HTMLElement} el L'élément HTML à insérer.
     * @param {HTMLElement} parent Le parent de l'élément HTML.
     * @returns {void}
     */
    static append(el, parent = document.body) {
        parent.appendChild(el);
    }


    /**
     * Insert un élément HTML dans le DOM après un autre élément HTML.
     * 
     * @param {HTMLElement} el L'élément HTML à insérer.
     * @param {HTMLElement} sibling L'élément HTML après lequel insérer l'élément.
     */
    static after(el, sibling) {
        sibling.parentNode.insertBefore(el, sibling.nextSibling);
    }


    /**
     * Insert un élément HTML dans le DOM avant un autre élément HTML.
     * 
     * @param {HTMLElement} el L'élément HTML à insérer.
     * @param {HTMLElement} sibling L'élément HTML avant lequel insérer l'élément.
     */
    static before(el, sibling) {
        sibling.parentNode.insertBefore(el, sibling);
    }


    /**
     * Supprime un élément HTML dans le DOM.
     * 
     * @param {HTMLElement} el L'élément HTML à supprimer.
     * @param {HTMLElement} parent Le parent de l'élément HTML.
     * @returns {void}
     */
    static remove(el, parent = document.body) {
        parent.removeChild(el);
    }


    /**
     * Insert un composant dans le DOM via son code HTML.
     * 
     * @param {string} component Le code HTML du composant.
     * @param {HTMLElement} parent Le parent du composant.
     * @param {string} position L'endroit où insérer le composant.
     * @returns {void}
     */
    static inject(component, parent = document.body, position = 'beforeend') {
        Dom.insert(component, parent, position);

        if (less !== undefined) {
            less.registerStylesheets();
            less.refresh();
        }

        element = position == 'beforeend' ? 
            parent.lastElementChild : 
            parent.firstElementChild;
            
        Finder.queryAll('script', element).forEach(script => {
            let newScript = Builder.create('script', { 
                type: 'module' 
            }, script.innerHTML)
            script.parentNode.replaceChild(newScript, script);
        });
    }
}