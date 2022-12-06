
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
     * @param {Number} num Le prix à convertir.
     * @returns {string} Le prix au format Européen.
     */
    static euro(num) {
        return new Intl.NumberFormat('fr-FR', {
            style: 'currency', 
            currency: 'EUR'
        }).format(num);
    }

    /**
     * Convertit un nombre en format occidentale.
     * 
     * @example occident('123456789.124') => '123 456 789,123'
     * @param {Number} num Le nombre à convertir.
     * @returns {string} La valeur convertie.
     */
    static occident(num) {
        return new Intl.NumberFormat('fr-FR').format(num);
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


    /**
     * Retourne un nombre aléatoire entre deux valeurs.
     * 
     * @param {Number} max La valeur minimale (incluse).
     * @param {Number} min La valeur maximale (exclue).
     * @returns {Number} Le nombre aléatoire.
     */
    static random(max = 100, min = 0) {
        if (max > min) {
            return Math.floor(Math.random() * (max - min)) + min;
        } else {
            throw 'La valeur maximale doit être inférieur à la valeur minimale !';
        }
    }


    /**
     * Tire un élément aléatoire d'un tableau.
     * 
     * @param {Array} array Le tableau de valeur.
     * @returns {any} L'item tiré.
     */
    static lottery(array) {
        return array[this.random(array.length)];
    }

}