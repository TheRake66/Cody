/**
 * Librairie gérant les classes HTML.
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Class {

    /**
     * Ajoute une classe à un élément HTML.
     * 
     * @param {HTMLElement} el L'élément HTML.
     * @param {string} className La classe à ajouter.
     * @returns {void}
     */
    static add(el, className) {
        el.classList.add(className);
    }


    /**
     * Supprime une classe à un élément HTML.
     * 
     * @param {HTMLElement} el L'élément HTML.
     * @param {string} className La classe à supprimer.
     * @returns {void}
     */
    static remove(el, className) {
        el.classList.remove(className);
    }


    /**
     * Vérifie si une classe est présente sur un élément HTML.
     * 
     * @param {HTMLElement} el L'élément HTML.
     * @param {string} className La classe à ajouter.
     * @returns {boolean} True si la classe est présente, false sinon.
     */
    static has(el, className) {
        return el.classList.contains(className);
    }


    /**
     * Ajoute une classe à un élément HTML si elle n'est pas présente, sinon la supprime.
     * 
     * @param {HTMLElement} el L'élément HTML.
     * @param {string} className La classe à ajouter ou à supprimer.
     * @returns {void}
     */
    static toggle(el, className) {
        el.classList.toggle(className);
    }

}