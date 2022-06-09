/**
 * Librairie de gestion du DOM.
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Finder {
    
    /**
     * Retourne un élément HTML via son ID.
     * 
     * @param {string} id L'id de l'élément HTML.
     * @returns {HTMLElement} L'élément HTML.
     */
    static id(id) {
        return document.getElementById(id);
    }


    /**
     * Retourne des éléments HTML via leur tag.
     * 
     * @param {string} tag Le tag de l'élément HTML.
     * @param {HTMLElement} parent Le parent de l'élément HTML.
     * @returns {HTMLCollectionOf<any>} Les éléments HTML.
     */
    static tag(tag, parent = document.body) {
        return parent.getElementsByTagName(tag);
    }


    /**
     * Retourne des éléments HTML via un selecteur CSS.
     * 
     * @param {string} selector Le selecteur CSS.
     * @param {HTMLElement} parent Le parent de l'élément HTML.
     * @returns {HTMLElement} L'élément HTML.
     */
    static query(selector, parent = document.body) {
        return parent.querySelector(selector);
    }
    

    /**
     * Retourne des éléments HTML via un selecteur CSS.
     * 
     * @param {string} selector Le selecteur CSS.
     * @param {HTMLElement} parent Le parent de l'élément HTML.
     * @returns {NodeListOf<Element>} Les éléments HTML.
     */
    static queryAll(selector, parent = document.body) {
        return parent.querySelectorAll(selector);
    }

}