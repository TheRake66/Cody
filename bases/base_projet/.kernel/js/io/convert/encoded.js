/**
 * Librairie de conversion des chaines de caractères.
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0.0.0
 * @category Framework source
 * @license MIT License
 * @copyright © 2021-2023 - Thibault BUSTOS (TheRake66)
 */
 export default class Encoded {

	/**
	 * Coupe une chaine de caractères si elle est trop longue.
	 * 
	 * @example cut('Lorem ipsum dolor sit amet', 10) => Lorem ipsum...
	 * @example cut('Lorem', 10) => Lorem
	 * @param {string} text Le texte à couper.
	 * @param {Number} max La taille maximum.
	 * @returns {string} Le texte coupé ou non.
	 */
    static cut(value, length = 50) {
        return value.length > length ? 
            value.substr(0, length - 3) + '...' : 
            value;
    }


    /**
     * Remplit une chaine de caractères avec un caractère.
     * 
     * @example fill('test', 10) => 'test     '
     * @example fill('test', 10, '0') => 'test00000'
     * @param {string} value La valeur à remplir.
     * @param {Number} length La taille de la chaine.
     * @param {string} char Le caractère à utiliser.
     * @returns {string} La chaine remplie.
     */
    static fill(value, length = 50, char = ' ') {
        return value.toString().padEnd(length, char);
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


    /**
     * Met au pluriel une chaine de caractères si la valeur est supérieur à 1.
     * 
	 * @example plural(2, 'chien') => chiens
	 * @example plural(1, 'chien') => chien
	 * @example plural(2, 'bocal', 'bocaux') => bocaux
     * @param {any} value La valeur à vérifier.
     * @param {string} singular Le mot au singulier.
     * @param {string} plural Le mot au pluriel. Si null, le mot au singulier est suivi d'un "s".
     * @returns {string} Le mot au singulier ou au pluriel.
     */
    static plural(value, singular, plural = null) {
        return value > 1 ? plural || singular + 's' : singular;
    }

}