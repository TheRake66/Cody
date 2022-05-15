
/**
 * Librairie de conversion
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Encoded {

    /**
     * Retourne un tiret si la valeur est vide
     * 
     * @example emptyToDash('test') => 'test'
     * @example emptyToDash('') => '-'
     * @param {Object} value la valeur
     * @returns {string|Object} le tiret si la valeur est vide, la valeur sinon
     */
    static emptyToHyphen(value) {
        return value === '' || 
            value === null || 
            value === undefined || 
            value === false || 
            value === 0 
            ? '-' : value;
    }

}