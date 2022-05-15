/**
 * Librairie gerant les evenements
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Event {

    /**
     * Ajoute un evenement a un element HTML
     * 
     * @param {HTMLElement} el l'element HTML
     * @param {string} event le nom de l'element
     * @param {function} callback la fonction à exécuter
     * @param {boolean} capture si repercute l'element sur les element enfants
     * @param {boolean} once si l'element doit être écouté une seule fois
     * @param {boolean} passive si la fonction peut appeler la methode preventDefault()
     * @returns {void}
     */
    static add(el, event, callback, capture = false, once = false, passive = false) {
        el.addEventListener(event, callback, {
            capture: capture,
            once: once,
            passive: passive
        });
    }


    /**
     * Supprime un evenement d'un element HTML
     * 
     * @param {HTMLElement} el l'element HTML
     * @param {string} event le nom de l'evenement
     * @returns {void}
     */
    static remove(el, event) {
        el.removeEventListener(event);
    }

}