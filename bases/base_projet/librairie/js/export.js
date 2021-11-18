class Export {

    /**
	 * Exporte un tableau en CSV
	 * 
	 * @param {string} id l'id du table
	 * @param {string} file nom du fichier
	 * @param {string} spearator separateur de colonne
	 * @param {string} arround caractere autour de chaque cellules
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


        // Encore en utf8
        let uint8 = new Uint8Array(csv.length);
        for (let i = 0; i < uint8.length; i++) {
            uint8[i] = csv.charCodeAt(i);
        }
        let blob = new Blob([uint8], {type: 'text/plain;charset=utf8'});
        let url = URL.createObjectURL(blob);


        // Creer le lien de telechargement
        let a = document.createElement('a')
        a.style = 'display: none';
        document.body.append(a);
        a.href = url;
        a.download = file;
        a.click();
        window.URL.revokeObjectURL(url);
        a.remove();
    };

};