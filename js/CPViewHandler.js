const example = new Styler(),
    exampleControll = new CPController();

$(document).ready(function () {

    const nav= $('header nav'),
        navButton = nav.find('ul button'),
        description = $('main div section p'),
        cssSection = $('#css-form'),
        jsSection = $('#js-section'),
        iframe = document.querySelector('#result');
    let previousExampleId = "";


    //ak bol pouzity link z clanku -> toto zapracuje iba raz pri nacitani stranky
    const urlParam = new URLSearchParams(window.location.search);
    if(urlParam.has('example')) {
        previousExampleId = urlParam.get('example');
        fOpenExample(previousExampleId);
        nav.addClass('invisible');
    }

    //tu nemoze byt arrow function! bude blbnut this
    navButton.click(function() {
        const actualExampleId = $(this).attr('value');
        nav.addClass('invisible');

        if (previousExampleId !== actualExampleId) {
            previousExampleId = actualExampleId;
            fOpenExample(actualExampleId);
        }
    });


    function fOpenExample(exampleId) {

        exampleControll.loadFullExample(exampleId).then(data => {
            let jsCode = "";

            if (data.code !== null) {       //toto sa stane, ked napr. do url adresy napiseme neexistujuci priklad

                //!= je ok
                if(data.desc != "") {
                    description.text(data.desc);
                    description.removeClass('invisible');
                }
                else {
                    description.empty();
                    description.addClass('invisible');
                }
                example.changeExample(data.propArray);

                if (data.propertyList !== "") {
                    cssSection.html(data.propertyList);
                    cssSection.removeClass('invisible');
                }
                else {
                    cssSection.html("");
                    cssSection.addClass('invisible');
                }

                if (data.jsArea !== "") {
                    jsSection.html(data.jsArea);
                    jsSection.removeClass('invisible');
                    jsCode = jsSection.find('textarea').val();
                }
                else {
                    jsSection.html("");
                    jsSection.addClass('invisible');
                }

                //css == "" pretoze musime vymazat style z pripadneho predchadzajuceho prikladu
                //vytvorenie blobu --> akoby injection kódu do ifram-u
                iframe.src = fCreateBlob({html: data.code, css: "", js: jsCode});

                const propSelect = cssSection.find('select'),
                    propInput = cssSection.find('input'),
                    propButton = cssSection.find('section div button'),    //tymto vyberieme vsetko okrem reset tlacidla
                    resetButton = cssSection.find('[value="reset"]'),
                    showCodeButton = cssSection.find('[value="showCode"]'),
                    jsTextarea = jsSection.find('textarea'),
                    resetJsButton = jsSection.find('[value="resetJs"'),
                    showJsDescButton = jsSection.find('[name="showJsDesc"]');


                propSelect.change(function() {
                    iframe.src = fCreateBlob({html: data.code, css: fCssMain($(this)), js: jsCode});
                });

                propInput.on('change keyup', function() {
                    iframe.src = fCreateBlob({html: data.code, css: fCssMain($(this)), js: jsCode});
                });

                propButton.click(function () {
                    fShowPropDesc($(this));
                });

                resetButton.click(function () {
                    example.clearAll();
                    iframe.src = fCreateBlob({html: data.code, css: "", js: jsCode});
                });

                showCodeButton.click(function () {
                    fShowCode(data.code);
                });

                jsTextarea.on('change keyup', function () {
                    iframe.src = fCreateBlob({html: data.code, css: example.getHTMLStyle(), js: jsTextarea.val()});
                });

                resetJsButton.click(function () {
                    jsTextarea.val(jsCode);
                    iframe.src = fCreateBlob({html: data.code, css: example.getHTMLStyle(), js: jsCode});
                });

                showJsDescButton.click(function () {
                    fShowJsDesc($(this));
                });
            }
        });
    }
});


//inšpirácia, bez ktorej by táto funkcia nevznikla: https://dev.to/pulljosh/how-to-load-html-css-and-js-code-into-an-iframe-2blc
function fCreateBlob({ html, css, js }) {

    const source = `
        <html lang="sk">
            <head>
                <meta charset="UTF-8">
                <title>Code-Play iframe (not iFrame)</title>
                <style>${css}</style>
                <script src="https://code.jquery.com/jquery-latest.min.js"></script>
                <script>${js}</script>
            </head>
            <body>
                ${html}
            </body>
        </html>
        `;

    const blob = new Blob([source], { type: 'text/html' });
    return URL.createObjectURL(blob);
}


function fCssMain(input) {
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
    return example.getHTMLStyle();
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
        `<article>
            <div><button type="button" id="exit">Exit</button></div>
            <div><h1>CSS kód:</h1><p><code>${cssCode}</code></p></div>
            <div><h1>HTML kód:</h1><p><code>${htmlCode}</code></p></div>
        </article>`;

    $('body').append(codeArticle);

    $('#exit').click(function () {
        $('article').remove();
    });
}

function fShowJsDesc(button) {
    const exampleId = button.attr('value');

    exampleControll.loadJsDesc(exampleId).then(data => {
        $('body').append(data);

        $('#exit').click(function () {
            $('article').remove();
        });
    });
}

function fSpecialChars(str) {

    return str.replace(/&/g, "&amp;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;");
}