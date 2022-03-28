/**
 * Librairie d'exportation des donnees
 */
export default class Export {

    /**
	 * Exporte un tableau en CSV
	 * 
	 * @param {string} id l'id du table
	 * @param {string} file nom du fichier
	 * @param {string} spearator separateur de colonne
	 * @param {string} arround caractere autour de chaque cellules
     * @returns void
     */
    static tableToCSV(id, file = 'export.csv', spearator = ';', arround = '"') {
        let csv = '';
        let table = document.getElementById(id);

        // Creer le header
        let heads = table.querySelectorAll('thead th');
        heads.forEach(cell => {
            csv += arround + cell.innerText + arround + spearator;
        });
        csv = csv.slice(0, -1) + '\n';

        // Creer les lignes
        let rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            if (row.style.displqy != 'none') {
                let cells = row.querySelectorAll('td');
                cells.forEach(cell => {
                    csv += arround + cell.innerText.replace(/\n/g, ' ') + arround + spearator;
                });
                csv = csv.slice(0, -1) + '\n';
            }
        });

        Export.telecharge(csv, file);
    }


    /**
	 * Telecharge un contenu
	 * 
	 * @param {any} content le contenu a telecharger
	 * @param {string} file nom du fichier
     * @returns void
     */
    static download(content, file) {
        let element = document.createElement('a');
        element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(content));
        element.setAttribute('download', file);
        element.style.display = 'none';
        document.body.appendChild(element);
        element.click();
        document.body.removeChild(element);
    }
    

    /**
     * Affiche du texte dans un nouvel onglet
     * 
     * @param {string} content le contenu de la page
     * @returns void
     */
    static fullScreen(content) {
        let tab = window.open('about:blank', '_blank');
        tab.document.write('<pre>' + content + '</pre>');
        tab.document.close();
    }
    
}