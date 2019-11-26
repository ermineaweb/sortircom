/*
Permet de récupérer la liste des places d'une ville via AJAX,
puis les coordonnées de la place sont mises à jours
 */
$(document).ready(() => {
    // si on veut modifier les places récupérées, on changera la requête qui est exécutée dans le controlle sur cette adresse :
    const url = "http://localhost/sortircom/sortircom/public/index.php/place/api";

    // on récupère une seule fois la liste des places en JSON, au chargement de la page
    let places = null;

    $.getJSON(url, (result) => {
        places = result;
    });

    $("#city").onload(() => {
        alert("it woek");
        // on met a jour le champ select places
        let newOptions = places.filter(p => p.city == $("#city").val());
        let $el = $("#event_place");
        $el.empty(); // remove old options
        $.each(newOptions, (key, val) => {
            $el.append($("<option></option>").attr("value", val.id).text(val.name));
        });
    });

    // à chaque changement de place, on met à jour les coordonnées de la place (address, latitude, longitude)
    $("#event_place").on('change load', () => {
        let place = places.find(p => p.id == $("#event_place").val());
        $("#address").html(place.address);
        $("#latitude").html(place.latitude);
        $("#longitude").html(place.longitude);
    });
});