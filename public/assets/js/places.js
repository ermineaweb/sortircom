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
        // parcours du résultat json
        console.log(places.filter(p => p.city == $("#city").val()));
        // on met a jour le champ select places
        // TODO
    });

    // à chaque changement de place, on met à jour les coordonnées de la place (address, latitude, longitude)
    $("#event_place").change(() => {
        console.log("hibhb" + $("#event_place").val());
    });

});