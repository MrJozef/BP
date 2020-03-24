const URL_TO_PHP_HANDLER = '../wcm/controller/FPHandler';

class FPController extends AjaxCrier {

    constructor() {
        super();
    }

    //nacita nazvy clankov z danej kategorie a popis ku kategorii
    async loadArticleNames(categoryId) {
        const dataForPHP = {aCategoryId: categoryId};

        return this.callAjax(URL_TO_PHP_HANDLER, dataForPHP);
    }

    //nacita dany clanok
    async loadArticle(articleId) {
        const dataForPHP = {aArticleId: articleId};

        return this.callAjax(URL_TO_PHP_HANDLER, dataForPHP);
    }
}