/**
 * Librairie de gestion du DOM
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Find {
    
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

}