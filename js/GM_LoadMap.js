var infoWindow;



/************************************************************************************
 * Métode d'inici
 ************************************************************************************/

function addPolyForm(map) {
    var bermudaTriangle;
    var triangleCoords = [
                          new google.maps.LatLng(0,0),
                          new google.maps.LatLng(0, 100),
                          new google.maps.LatLng(100, 50)
                          ];

    bermudaTriangle = new google.maps.Polygon({
        paths: triangleCoords,
        strokeColor: "#FF0000",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#FF0000",
        fillOpacity: 0.35
    });

    bermudaTriangle.setMap(map);

    // Add a listener for the click event
    google.maps.event.addListener(bermudaTriangle, 'click', showArrays);
    infowindow = new google.maps.InfoWindow();
}

function showArrays(event) {

    // Since this Polygon only has one path, we can call getPath()
    // to return the MVCArray of LatLngs
    var vertices = this.getPath();

    var contentString = "<b>Triangle de les Bermudes:</b><br />";
    contentString += "Posici&ocaute seleccionada: <br />" + event.latLng.lat() + "," + event.latLng.lng() + "<br />";

    // Iterate over the vertices.
    for (var i=0; i < vertices.length; i++) {
        var xy = vertices.getAt(i);
        contentString += "<br />" + "Coordenada: " + i + "<br />" + xy.lat() +"," + xy.lng();
    }

    // Replace our Info Window's content and position
    infowindow.setContent(contentString);
    infowindow.setPosition(event.latLng);

    infowindow.open(map);
}