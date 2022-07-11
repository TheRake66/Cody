import Dom from '../html/dom.js';
import Builder from '../html/builder.js';
import Finder from '../html/finder.js';



/**
 * Librairie de gestion des fils d'exécution.
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Thread {

    /**
	 * Met une pause dans l'exécution du script.
	 * 
     * @param {int} time Le temps de pause en millisecondes.
     * @return {Promise} La promesse de la pause.
     */
    static sleep(time = 500) {
        return new Promise(r => setTimeout(r, time));
    }

}