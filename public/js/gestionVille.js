window.onload = () => {
    const FiltersForm = document.querySelector("#form_ville");

    const envoyer = document.querySelector("#nomVille_bouton")

    envoyer.addEventListener("click", () => {

        // On récupère les données du formulaire
        const Form = new FormData(FiltersForm);

        // On fabrique la "queryString"
        const Params = new URLSearchParams();


        Form.forEach((value, key) => {
            Params.append(key, value);
            console.log(key, value)
        });

        // On récupère l'url active
        const Url = new URL(window.location.href);
        console.log(Url)

        // On lance la requête ajax
        fetch(Url.pathname + "?" + Params.toString() + "&ajax=1", {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        }).then(response =>
            response.json()
        ).then(data => {
            // On va chercher la zone de contenu
            const content = document.querySelector("#table_ville");

            // On remplace le contenu
            content.innerHTML = data.content;

            // On met à jour l'url
            history.pushState({}, null, Url.pathname + "?" + Params.toString());
        }).catch(e => alert(e));

    });

}