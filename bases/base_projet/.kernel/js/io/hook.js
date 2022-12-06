import Dom from '../html/dom.js';
import Builder from '../html/builder.js';
import Finder from '../html/finder.js';



/**
 * Librairie gérant empêchant le changement de page.
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Hook {

    /**
	 * Ajoute l'écouteur de changement de page.
     * 
     * @returns {void}
     */
    static hook() {
        window.onbeforeunload = function() {
            return '';
        }
    }
    

    /**
     * Supprime l'écouteur de changement de page.
     * 
     * @returns {void}
     */
    static unhook() {
        window.onbeforeunload = {};
    }
    
}