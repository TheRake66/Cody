/**
 * Librairie gérant les attributs HTML.
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0.0.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2021-2023 - Thibault BUSTOS (TheRake66)
 */
export default class Attribute {

    /**
     * Désactive un élément HTML.
     * 
     * @param {HTMLElement} el L'élément HTML.
     * @returns {void}
     */
    static disable(el) {
        el.disabled = true;
    }


    /**
     * Active un élément HTML.
     * 
     * @param {HTMLElement} el L'élément HTML.
     * @returns {void}
     * @returns {void}
     */
    static enable(el) {
        el.disabled = false;
    }


    /**
     * Passe un élément HTML en mode lecture seule.
     * 
     * @param {HTMLElement} el L'élément HTML.
     * @returns {void}
     */
    static readonly(el) {
        el.readOnly = true;
    }


    /**
     * Passe un élément HTML en mode écriture.
     * 
     * @param {HTMLElement} el L'élément HTML.
     * @returns {void}
     */
    static writable(el) {
        el.readOnly = false;
    }


    /**
     * Cache un élément HTML.
     * 
     * @param {HTMLElement} el L'élément HTML.
     * @returns {void}
     */
    static hide(el) {
        el.style.display = 'none';
    }


    /**
     * Affiche un élément HTML.
     * 
     * @param {HTMLElement} el L'élément HTML.
     * @param {string} display Le style d'affichage.
     * @returns {void}
     */
    static show(el, display = 'revert') {
        el.style.display = display;
    }

    
    /**
     * Vérifie si un élément HTML est visible.
     * 
     * @param {HTMLElement} el L'élément HTML.
     * @returns {boolean} True si visible, false sinon.
     */
    static visible(el) {
        return el.style.display !== 'none';
    }


    /**
     * Définit un attribut HTML.
     * 
     * @param {HTMLElement} el L'élément HTML.
     * @param {string} name Le nom de l'attribut.
     * @param {string} value La valeur de l'attribut.
     * @returns {void}
     */
    static set(el, name, value = '') {
        el.setAttribute(name, value);
    }


    /**
     * Récupère la valeur d'un attribut HTML.
     * 
     * @param {HTMLElement} el L'élément HTML.
     * @param {string} name Le nom de l'attribut.
     * @returns {string|null} La valeur de l'attribut.
     */
    static get(el, name) {
        return el.getAttribute(name);
    }


    /**
     * Supprime un attribut HTML.
     *
     * @param {HTMLElement} el L'élément HTML.
     * @param {string} name Le nom de l'attribut.
     * @returns {void}
     */
    static remove(el, name) {
        el.removeAttribute(name);
    }


    /**
     * Vérifie si un attribut HTML existe.
     * 
     * @param {HTMLElement} el L'élément HTML.
     * @param {string} name Le nom de l'attribut.
     * @returns {boolean} True si l'attribut existe, false sinon.
     */
    static has(el, name) {
        return el.hasAttribute(name);
    }

}