/*
Permet de récupérer la liste des places d'une ville via AJAX,
puis les coordonnées de la place sont mises à jours
 */
$(document).ready(() => {
    // si on veut modifier les places récupérées, on changera la requête qui est exécutée dans le controlle sur cette adresse :
    const url = "http://localhost/sortircom/sortircom/public/index.php/place/api";

    // on récupère une seule fois la liste des places en JSON, au chargement de la page
    let places = "";
    $.getJSON(url, (result) => {
        places = result;
    });

    // à chaque changement de la ville, on met a jour le champ select place
    $("#city").change(() => {
        // on récupère les places associées à la nouvelle ville sélectionnée
        let newPlaces = places.filter(p => p.city == $("#city").val());
        let $el = $("#event_place");
        // on supprime les anciennes places
        $el.empty();
        $.each(newPlaces, (key, val) => {
            $el.append($("<option></option>").attr("value", val.id).text(val.name));
        });
    });

    // à chaque changement de place, on met à jour les coordonnées de la place (address, latitude, longitude)
    $("#event_place").change(() => {
        let place = places.find(p => p.id == $("#event_place").val());
        $("#address").html(place.address);
        $("#latitude").html(place.latitude);
        $("#longitude").html(place.longitude);
    });
});