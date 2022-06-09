
/**
 * Librairie de conversion des chaines de caractères.
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
export default class Encoded {

    /**
     * Retourne un tiret si la valeur est vide.
     * 
     * @example hyphen('test') => 'test'
     * @example hyphen('') => '-'
     * @example hyphen(null) => '-'
     * @example hyphen(undefined) => '-'
     * @example hyphen(false) => '-'
     * @example hyphen(0) => '-'
     * @param {Object} value La valeur à vérifier.
     * @returns {string|Object} La valeur ou le tiret.
     */
    static hyphen(value) {
        return value === '' || 
            value === null || 
            value === undefined || 
            value === false || 
            value === 0 
            ? '-' : value;
    }

}