const URL_TO_PHP_HANDLER = '../wcm/controller/FPHandler';
const OUTPUT_DATA_TYPE = 'html';

class FPController extends AjaxCrier {

    constructor() {
        super();
    }

    //nacita nazvy clankov z danej kategorie a popis ku kategorii
    async loadArticleNames(categoryId) {
        const dataForPHP = {aCategoryId: categoryId};

        return this._callAjax(OUTPUT_DATA_TYPE, URL_TO_PHP_HANDLER, dataForPHP);
    }

    //nacita dany clanok
    async loadArticle(articleId) {
        const dataForPHP = {aArticleId: articleId};

        return this._callAjax(OUTPUT_DATA_TYPE, URL_TO_PHP_HANDLER, dataForPHP);
    }
}