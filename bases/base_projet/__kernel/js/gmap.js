// Different type de map
const mapType = {
	SATELLITE: "satellite",
	ROADMAP: "roadmap",
	HYBRIDE: "hybrid",
	TERRAIN: "terrain",
}


// Librairie GMap
class Gmap {

    /**
     * Array contenant les marker
     */
    markers = [];


    /**
     * Map
     */
    map;


    /**
     * Constructeur
     */
    constructor() {
        // Ajoute les librairie gmap
        let jquery = document.createElement("script");
        jquery.src = 'http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js';
        document.head.append(jquery);
        let api = document.createElement("script");
        api.src = 'https://maps.googleapis.com/maps/api/js';
        document.head.append(jquery);
    }


    /**
     * Execute une requete http(s) en async
     * 
     * @param {Element} cont - Conteneur qui contiendra la map
     * @param {float} lt - Latitude de depart
     * @param {float} lg - Longitude de depart
     * @param {int} zm - Zoom de depart
     * @param {MapType} type - Type de map
     */
    createMap(cont, lt = 0, lg = 0, zm = 2, type = mapType.ROADMAP) {
        this.map = new google.maps.Map(
            cont, {
                center: {
                    lat: lt,
                    lng: lg
                },
                zoom: zm,
                mapTypeId: type
            }
        );
    }


    /**
     * Ajoute un marker sur la map
     * 
     * @param {float} lt - Latitude du marker
     * @param {float} lg - Longitude du marker
     * @param {string} name - Nom du marker
     * @param {string} image - Image du marker
     * @return {Marker} Marker cree
     */
    addMarker(lt, lg, name, image) {
        let marker = new google.maps.Marker({ 
            position: new google.maps.LatLng(lt, lg),
            map: this.map,
            title: name,
            icon: image
        })
        this.markers.push(marker);
        return marker;
    }


    /**
     * Supprime un marker
     * 
     * @param {Marker} marker - Marker a supprimer
     */
    clearMarker(marker) {
        let index = this.markers.indexOf(marker);
        if (index > -1) {
            marker.setMap(null);
            this.markers.splice(index, 1);
        }
    }


    /**
     * Supprime tous les markers
     */
    clearMarkers() {
        this.markers.forEach(element => {
            element.setMap(null);
        });
        this.markers.length = 0;
    }

}