import Html from './html.js';



/**
 * Librairie de conversion
 * 
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @category Librairie
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
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


    /**
	 * Convertit un tableau en CSV
	 * 
	 * @param {HTMLElement} table le tableau
	 * @param {string} spearator separateur de colonne
	 * @param {string} arround caractere autour de chaque cellules
     * @returns {void}
     */
     static tableToCSV(table, spearator = ';', arround = '"') {
        let csv = '';

        // Creer le header
        let heads = Html.queryAll('thead th', table);
        heads.forEach(cell => {
            csv += arround + cell.innerText + arround + spearator;
        });
        csv = csv.slice(0, -1) + '\n';

        // Creer les lignes
        let rows = Html.queryAll('tbody tr', table);
        rows.forEach(row => {
            if (row.style.display != 'none') {
                let cells = Html.queryAll('td', row);
                cells.forEach(cell => {
                    csv += arround + cell.innerText.replace(/\n/g, ' ') + arround + spearator;
                });
                csv = csv.slice(0, -1) + '\n';
            }
        });
        
        return csv;
    }

}