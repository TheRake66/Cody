const reqType = {
	GET: "GET",
	POST: "POST"
}


// Librairie Http
class Http {

    /**
     * Execute une requete http(s) en async
     * 
     * @param {string} url - URL a requeter
     * @param {function} callback - Fonction anonyme appeler lors de la reponse
     * @param {ReqType} type - Type de requete
     */
    static send(url, callback, type = reqType.GET) {
        let xml = new XMLHttpRequest();
        xml.open(type, url, true);
        xml.onreadystatechange = () => {
            if (xml.status == 200 && xml.readyState == 4)
                callback(xml.response);
        }
        xml.send();
    }

}