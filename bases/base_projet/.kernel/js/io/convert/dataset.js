/**
 * Librairie de conversion de tableau.
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0.0.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2021-2023 - Thibault BUSTOS (TheRake66)
 */
 export default class Dataset {

    /**
     * Remplit une tableau avec une valeur jusqu'à une taille donnée.
     * 
     * @example fill([1, 2, 3], 5, 0) => [1, 2, 3, 0, 0]
     * @example fill([1, 2, 3], 5) => [1, 2, 3, null, null]
     * @param {string} value La valeur à remplir.
     * @param {Number} length La taille de la chaine.
     * @param {any} value La valeur à remplir.
     * @returns {array} 
     */
    static fill(array, length = 50, value = null) {
        let current = array.length;
        return current < length ? array.concat(Array(length - current).fill(value)) : array;
    }    

}