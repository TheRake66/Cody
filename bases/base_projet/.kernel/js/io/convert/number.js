import Html from './html.js';



/**
 * Librairie de conversion
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Number {

    /**
     * Convertit un prix au format europeen
     * 
     * @example convertToEuro('123456789') => '123 456 789 €'
     * @param {Number} num le prix
     * @returns {string} le prix en format francais
     */
    static toEuro(num) {
        return new Intl.NumberFormat("fr-FR", 
        {
            style: "currency", 
            currency: "EUR"
        }).format(num);
    }


    /**
     * Verifie si un nombre est pair
     * 
     * @example isEven(2) => true
     * @example isEven(3) => false
     * @param {Number} num le nombre
     * @returns {boolean} true si le nombre est pair
     */
    static isEven(num) {
        return num % 2 === 0;
    }


    /**
     * Verifie si un nombre est impair
     * 
     * @example isOdd(2) => false
     * @example isOdd(3) => true
     * @param {Number} num le nombre
     * @returns {boolean} true si le nombre est impair
     */
    static isOdd(num) {
        return num % 2 !== 0;
    }

}