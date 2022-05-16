/**
 * Librairie gerant la creation d'element HTML
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Builder {

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
    
}