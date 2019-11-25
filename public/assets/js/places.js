$(document).ready(function () {
    const url = "http://localhost/sortircom/sortircom/public/index.php/api/places";

    $("#idcity").change(function () {
        $.getJSON(url, function (result) {
            // parcours du résultat json
            $.each(result, function (key, value) {
                // on parcours chaque place
                $.each(value, function (key, place) {
                    let idcity = $("#idcity").children("option:selected").val();
                    if (idcity == 1) {
                        console.log(key + " " + place);
                        console.log(place.address);
                    }
                })
            });
        });
    });

});