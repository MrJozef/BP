const example = new Styler();

$(document).ready(function () {

    const exampleControll = new CPController(),
        navButton = $('header nav button'),
        description = $('main section p'),
        cssProperty = $('#css-form'),
        iframe = $('#result'),
        iframeBody = iframe.contents().find('body'),
        iframeHead = iframe.contents().find('head').html("<style></style>")
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
                    propButton = cssProperty.find('button');

                propSelect.change(function() {
                    fMain($(this), iframeStyle);
                });

                propInput.on('change keyup', function() {       //todo toto sa pusta 2x jak to
                    fMain($(this), iframeStyle);
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
            if (actualInput.val() === "") {         //ak napriklad vymazeme hodnotu input type=text, tak value bude musiet byt ""
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

function fShowPropDesc(property) {
    alert("nieco");
}