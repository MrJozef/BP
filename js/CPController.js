const URL_TO_PHP_HANDLER = '../wcm/controller/code-play/CPHandler';
const OUTPUT_DATA_TYPE = 'json';

class CPController extends AjaxCrier {

    constructor() {
        super();
    }

    async loadFullExample(exampleId) {
        const dataPhp = {aExample: exampleId};
        return this._callAjax(OUTPUT_DATA_TYPE, URL_TO_PHP_HANDLER, dataPhp);
    }
}