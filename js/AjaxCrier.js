//town crier - in the past, a person whose job was to make official announcements in a town or village by calling them out in public
//https://dictionary.cambridge.org/dictionary/english/town-crier

class AjaxCrier {

    constructor() {
    }

    //dataForPhp - musí to byť objekt!


    async _callAjax(outputDataType, urlToPhpHandler, dataForPhp) {
        return $.ajax ({
            type: 'post',
            data: dataForPhp,
            url: urlToPhpHandler,
            dataType: outputDataType,
            async: 'true',
            error: function () {
                alert('Prepáčte, nepodarilo sa načítať dáta zo serveru.');
            },
            success: function (data) {
                return data;
            }
        });
    }
}