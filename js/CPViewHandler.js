$(document).ready(function () {

    const exampleControll = new CPController(),
        navButton = $('header nav button'),
        description = $('main section p'),
        cssProperty = $('#css-form'),
        iframe = $('#result'),
        iframeBody = $(iframe).contents().find('body'),
        iframeHead = $(iframe).contents().find('head');

    //tu nemoze byt arrow function!
    navButton.click(function() {
        const actualExampleId = $(this).attr('value');

        exampleControll.loadFullExample(actualExampleId).then(data => {
            iframeBody.html(data.code);

            //this is fine
            if(data.desc != "") {
                description.text(data.desc);
            }
            else {
                description.empty();
            }
        });

    });
});