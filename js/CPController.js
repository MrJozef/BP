const URL_TO_PHP_HANDLER = '../wcm/controller/code-play/CPHandler';

class CPController extends AjaxCrier {

    constructor() {
        super();
    }

    async loadFullExample(exampleId) {
        const dataForRequest1 = {aExample: exampleId};
        let data = await this._callAjax('json', URL_TO_PHP_HANDLER, dataForRequest1);

        const dataForRequest2 = {aExampleProp: exampleId};
        data.propertyList = await this._callAjax('html', URL_TO_PHP_HANDLER, dataForRequest2);

        const dataForRequest3 = {aExampleJs: exampleId};
        data.jsArea = await this._callAjax('html', URL_TO_PHP_HANDLER, dataForRequest3);

        return data;
    }

    async loadPropDesc(propertyId) {
        const dataForRequest = {aPropDesc: propertyId};
        return this._callAjax('html', URL_TO_PHP_HANDLER, dataForRequest);
    }

    async loadJsDesc(exampleId) {
        const dataForRequest = {aJsDesc: exampleId};
        return this._callAjax('html', URL_TO_PHP_HANDLER, dataForRequest);
    }
}