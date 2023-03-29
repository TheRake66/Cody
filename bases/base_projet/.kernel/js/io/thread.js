/**
 * Librairie de gestion des fils d'exécution.
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0.0.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2021-2023 - Thibault BUSTOS (TheRake66)
 */
export default class Thread {

    /**
	 * Met une pause dans l'exécution du script.
	 * 
     * @param {Number} time Le temps de pause en millisecondes.
     * @return {Promise} La promesse de la pause.
     */
    static sleep(time = 500) {
        return new Promise(r => setTimeout(r, time));
    }

}