/*var $ville = $('#sortie_ville')
var $token = $('#sortie__token')


$ville.change(function ()
{
    console.log($ville.val())
    var $form = $(this).closest('form')
    var data = {}

    data[$token.attr('name')] = $token.val()
    data[$ville.attr('name')] = $ville.val()

    $.post($form.attr('action'), data).then(function (response)
    {
        $("#sortie_lieu").replaceWith(
            $(response).find("#sortie_lieu")

        )
        $("#sortie_codePostal").replaceWith(
            $(response).find("#sortie_codePostal")
        )
    })
})*/

$(document).on('change', '#sortie_lieu', function()
{
    fetch('http://localhost/sortir.com/public/api/lieu/'+$('#sortie_lieu').val()+'', {method: "GET"})
        .then(response=>response.json())
        .then(response=>{
            console.log(response);
            const $rue = document.querySelector("#sortie_rue");
            const $latitude = document.querySelector("#sortie_latitude");
            const $longitude = document.querySelector("#sortie_longitude");

            $rue.setAttribute("value", response.rue);
            $latitude.setAttribute("value", response.latitude);
            $longitude.setAttribute("value", response.longitude);

        })
})


var $ville = $('#sortie_ville')
$ville.change(function ()
{
    fetch('http://localhost/sortir.com/public/api/lieux/'+$ville.val()+'', {method: "GET"})
        .then(response=>response.json())
        .then(response=>{
            console.log(response);
            const $select = document.querySelector("#sortie_lieu");
            let options ="`<option value=\"\">Choisir un lieu</option> `";
            response.map(lieu => {
                options += `<option value="${lieu.id}">${lieu.nomLieu}</option> `
            })
            console.log(options)
            $select.innerHTML = options;
        })

})

$ville.change(function () {
    fetch('http://localhost/sortir.com/public/api/ville/' + $ville.val() + '', {method: "GET"})
        .then(response => response.json())
        .then(response => {
            console.log(response);
            const $codePostal = document.querySelector("#sortie_codePostal")
            $codePostal.setAttribute("value", response.codePostal);
        })

})



/*$(document).on('change', '#sortie_lieu', function()
{
    var $form = $(this).closest('form')
    var data = {}

    data[$token.attr('name')] = $token.val()
    data[$('#sortie_lieu').attr('name')] = $('#sortie_lieu').val()
    data[$ville.attr('name')] = $ville.val()

    console.log($('#sortie_lieu').val())

    $.post($form.attr('action'), data).then(function (response)
    {
        $("#sortie_rue").replaceWith(
            $(response).find("#sortie_rue")
        )
    })
});*/

