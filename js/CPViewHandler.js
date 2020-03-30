const example = new Styler(),
    exampleControll = new CPController();

$(document).ready(function () {

    const navButton = $('header nav button'),
        description = $('main section p'),
        cssProperty = $('#css-form'),
        iframe = $('#result'),
        iframeBody = iframe.contents().find('body'),
        iframeHead = iframe.contents().find('head').html("<style></style>");
        iframeStyle = iframeHead.find('style');

    let previousExampleId = "";


    //tu nemoze byt arrow function! bude blbnut this
    navButton.click(function() {
        const actualExampleId = $(this).attr('value');

        if (previousExampleId !== actualExampleId) {
            previousExampleId = actualExampleId;

            exampleControll.loadFullExample(actualExampleId).then(data => {
                iframeBody.html(data.code);
                //!= je ok
                if(data.desc != "") {
                    description.text(data.desc);
                }
                else {
                    description.empty();
                }
                example.changeExample(data.propArray);

                cssProperty.html(data.propertyList);
                iframeStyle.text("");//musime vymazat style z pripadneho predchadzajuceho prikladu

                const propSelect = cssProperty.find('select'),
                    propInput = cssProperty.find('input'),
                    propButton = cssProperty.find('div button'),    //tymto vyberieme vsetko okrem reset tlacidlo
                    resetButton = cssProperty.find('[value="reset"]'),
                    showCodeButton = cssProperty.find('[value="showCode"]');

                propSelect.change(function() {
                    fMain($(this), iframeStyle);
                });

                propInput.on('change keyup', function() {       //todo toto sa pusta 2x jak to
                    fMain($(this), iframeStyle);
                });

                propButton.click(function () {
                    fShowPropDesc($(this));
                });

                resetButton.click(function () {
                    example.clearAll();
                    iframeStyle.text("");
                });

                showCodeButton.click(function () {
                    fShowCode(data.code);
                });
            })//todo .catch(data => $('body').append(data));?
        }
    });
});

//where to inject code
function fMain(input, where) {
    //vyhľadáme všetky inputy CSS vlastnosti - button + select/input + {select/input}.n -> this.siblings vyhľadá všetky inputy okrem this -> na nič
    const allPropInputs = input.parent().children(),
        button = $(allPropInputs[0]);       //button bude vzdy prvy
    let value = "",
        actualInput;

    for(i = 1; i < allPropInputs.length; i++) {
        actualInput = $(allPropInputs[i]);

        if (!actualInput.hasClass('unit')) {
            if (actualInput.val() === "") {         //ak napriklad vymazeme hodnotu input type=text, tak value bude musiet byt "" a nie "em"
                break;
            }
            value += " " + actualInput.val();
        }
        else {
            value += actualInput.val();
        }
    }

    //parametre budú vyzerať napr. takto: "h1", "padding-top", "2em"
    example.updateValue(button.attr('name'), button.text().replace(':', ''), value);
    where.text(example.getHTMLStyle());
}

function fShowPropDesc(propButton) {
    const propertyId = propButton.attr('value');

    exampleControll.loadPropDesc(propertyId).then(data => {
        $('body').append(data);

        $('#exit').click(function () {
            $('article').remove();
        });
    });
}

function fShowCode(HTMLCode) {
    let cssCode = example.getTextStyle(),
        htmlCode = fSpecialChars(HTMLCode),

    codeArticle =
        `<article><button type="button" id="exit">Exit</button>
            <div><h1>CSS kód:</h1><p><code style="white-space: pre-line">${cssCode}</code></p></div>
            <div><h1>HTML kód:</h1><p><code style="white-space: pre-line">${htmlCode}</code></p></div>
        </article>`;//todo style dat do css code

    $('body').append(codeArticle);

    $('#exit').click(function () {
        $('article').remove();
    });
}

function fSpecialChars(str) {

    return str.replace(/&/g, "&amp;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;");
}