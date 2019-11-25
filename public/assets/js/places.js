$(document).ready(function () {
    const url = "http://localhost/sortircom/sortircom/public/index.php/api/places";

    $("#idcity").change(function () {
        $.getJSON(url, function (result) {
            $.each(result, function (i, field) {
                $("#maclasse").append(field + " ");
            });
        });
    });
});