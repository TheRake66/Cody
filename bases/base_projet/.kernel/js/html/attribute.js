/**
 * Librairie gerant les attributs HTML
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Attribute {

    /**
     * Desactive un élément HTML
     * 
     * @param {HTMLElement} el l'élément HTML
     * @returns {void}
     */
    static disable(el) {
        el.disabled = true;
    }


    /**
     * Active un élément HTML
     * 
     * @param {HTMLElement} el l'élément HTML
     * @returns {void}
     * @returns {void}
     */
    static enable(el) {
        el.disabled = false;
    }


    /**
     * Passe un élément HTML en mode lecture seule
     * 
     * @param {HTMLElement} el l'élément HTML
     * @returns {void}
     */
    static readonly(el) {
        el.readOnly = true;
    }


    /**
     * Passe un élément HTML en mode écriture
     * 
     * @param {HTMLElement} el l'élément HTML
     * @returns {void}
     */
    static writable(el) {
        el.readOnly = false;
    }


    /**
     * Cache un élément HTML
     * 
     * @param {HTMLElement} el l'élément HTML
     * @returns {void}
     */
    static hide(el) {
        el.style.display = 'none';
    }


    /**
     * Affiche un élément HTML
     * 
     * @param {HTMLElement} el l'élément HTML
     * @returns {void}
     */
    static show(el) {
        el.style.display = 'unset';
    }

}