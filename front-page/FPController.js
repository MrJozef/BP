class FPController {

    constructor() {
    }

    //nacita nazvy clankov z danej kategorie
    loadArticleNames(categoryId) {

        return $.ajax ({
            type: 'post',
            data: {aCategoryId: categoryId},
            url: '../wcm/controller/FPHandler',
            dataType: 'html',
            async: 'true',
            error: function () {
                alert('Prepáčte, nepodarilo sa načítať dáta zo serveru.');//todo nejak vysperkovat chybu?
            }
        });
    }
}