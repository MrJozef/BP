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
                alert('Prepáčte, nepodarilo sa načítať dáta zo serveru.');
            },
            success: function (data) {
                $('main').html(data);
            }
        });
    }

    loadArticle(articleId) {
        return $.ajax ({
            type: 'post',
            data: {aArticleId: articleId},
            url: '../wcm/controller/FPHandler',
            dataType: 'html',
            async: 'true',
            error: function () {
                alert('Prepáčte, nepodarilo sa načítať dáta zo serveru.');
            },
            success: function (data) {
                $('footer').append(data);
            }
        });
    }
}