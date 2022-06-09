/**
 * Librairie gérant les évènements.
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
 export default class Event {

    /**
     * Ajoute un évènement à un élément HTML.
     * 
     * @param {string} event Le nom de l'élément.
     * @param {function} callback La fonction anonyme à appeler.
     * @param {HTMLElement} el L'élément HTML.
     * @param {boolean} capture Si répercute l'évènement sur les éléments enfants.
     * @param {boolean} once Si l'évènement doit être déclenché une seule fois.
     * @param {boolean} passive Si la fonction peut appeler la methode preventDefault().
     * @returns {void}
     */
    static add(event, callback, el = document.body, capture = false, once = false, passive = false) {
        el.addEventListener(event, callback, {
            capture: capture,
            once: once,
            passive: passive
        });
    }


    /**
     * Supprime un évènement d'un élément HTML.
     * 
     * @param {HTMLElement} el L'élément HTML.
     * @param {string} event Le nom de l'évènement.
     * @returns {void}
     */
    static remove(el, event) {
        el.removeEventListener(event);
    }

}