const ReqType = {
	GET: "GET",
	POST: "POST"
}



/**
 * @constructor
 */
var HTTP = function() {

    /**
     * Execute une requete http(s) en async
     * @constructor
     * @param {string} url - URL a requeter
     * @param {function} callback - Fonction anonyme appeler lors de la reponse
     * @param {ReqType} type - Type de requete
     */
    this.send = (url, callback, type = ReqType.GET) => {
        let xml = new XMLHttpRequest();
        xml.open(type, url, true);
        xml.onreadystatechange = () => {
            if (xmlhttp.status == 200 && xmlhttp.readyState == 4)
                callback(xmlhttp.responseText);
        }
        xmlhttp.send();
    };

};



HTTP = new HTTP();