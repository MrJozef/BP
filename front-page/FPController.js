class FPController {

    constructor() {
    }

    //nacita nazvy clankov z danej kategorie a popis ku kategorii
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

    //nacita dany clanok
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

                if($('main article').length) {
                    $('article').remove();
                    $('main').append(data);
                }
                else {
                    $('main').append(data);
                }
            }
        });
    }
}