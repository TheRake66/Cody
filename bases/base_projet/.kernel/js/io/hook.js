/**
 * Librairie gérant empêchant le changement de page.
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0.0.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2021-2023 - Thibault BUSTOS (TheRake66)
 */
export default class Hook {

    /**
	 * Ajoute l'écouteur de changement de page.
     * 
     * @returns {void}
     */
    static set() {
        window.onbeforeunload = () => {
            return '';
        };
    }
    

    /**
     * Supprime l'écouteur de changement de page.
     * 
     * @returns {void}
     */
    static unset() {
        window.onbeforeunload = {};
    }

}