
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
	 * Coupe une chaine de caractères si elle est trop longue.
	 * 
	 * @example cut('Lorem ipsum dolor sit amet', 10) => Lorem ipsum...
	 * @example cut('Lorem', 10) => Lorem
	 * @param {string} text Le texte à couper.
	 * @param {number} max La taille maximum.
	 * @returns {string} Le texte coupé ou non.
	 */
    static cut(value, length = 50) {
        return value.length > length ? 
            value.substr(0, length) + '...' : 
            value;
    }


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
	

    /**
     * Génère une chaine de caractères aléatoires.
     * 
	 * @example random(10) => 'a1b2c3d4e5f6g7h8i9j0'
	 * @example random(10, 'ABCD') => 'ADCBADBCDA'
     * @param {number} size La taille de la chaine.
     * @param {string} charset Les caractères à utiliser.
     * @returns {string} La chaine de caractères aléatoires.
     */
    static random(size = 32, charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') {
        let result = '';
        for (let i = size; i > 0; --i) {
            result += charset[Math.floor(Math.random() * charset.length)];
        }
        return result;
    }


    /**
     * Retourne null si la valeur est vide, sinon retourne la valeur.
     * 
	 * @example null('Lorem ipsum dolor sit amet') => 'Lorem ipsum dolor sit amet'
	 * @example null('') => null
     * @param {any} value La valeur à vérifier.
     * @return {any} La valeur ou null.
     */
    static null(value) {
        return value === '' || 
        value === null || 
        value === undefined || 
        value === false || 
        value === 0 
        ? null : value;
    }

}