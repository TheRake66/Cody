
/**
 * Librairie de conversion des nombres.
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Number {

    /**
     * Convertit un prix au format Européen.
     * 
     * @example euro('123456789') => '123 456 789 €'
     * @param {Number} num Le prix.
     * @returns {string} Le prix au format Européen.
     */
    static euro(num) {
        return new Intl.NumberFormat("fr-FR", {
            style: "currency", 
            currency: "EUR"
        }).format(num);
    }


    /**
     * Vérifie si un nombre est pair.
     * 
     * @example even(2) => true
     * @example even(3) => false
     * @param {Number} num Le nombre.
     * @returns {boolean} True si le nombre est pair.
     */
    static even(num) {
        return num % 2 === 0;
    }


    /**
     * Vérifie si un nombre est impair.
     * 
     * @example even(2) => false
     * @example even(3) => true
     * @param {Number} num Le nombre.
     * @returns {boolean} True si le nombre est impair.
     */
    static odd(num) {
        return num % 2 !== 0;
    }

}