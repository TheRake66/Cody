/**
 * Librairie de conversion
 */
export default class Convert {

    /**
     * Convertit un prix en format francais
     * 
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
     * @param {Number} num le nombre
     * @returns {boolean} true si le nombre est pair
     */
    static isEven(num) {
        return num % 2 === 0;
    }


    /**
     * Verifie si un nombre est impair
     * 
     * @param {Number} num le nombre
     * @returns {boolean} true si le nombre est impair
     */
    static isOdd(num) {
        return num % 2 !== 0;
    }


    /**
     * Retourne un tiret si la valeur est vide
     * 
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