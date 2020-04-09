$(document).ready(function () {
    let nav = $('nav'),
        showNavButton = $('#showNav'),
        closeNavButton = $('#closeNav'),
        mainDiv = $('main > div:first-child'),
        iframe = $('#result'),
        browser = $(window),
        browserWidth,
        browserHeight;

    showNavButton.click(function () {
        nav.removeClass('invisible');
    });

    closeNavButton.click(function () {
        nav.addClass('invisible');
    });


    browser.on('resize', function() {
        browserWidth = browser.width();
        browserHeight = browser.height();

        if (browserWidth < 980) {
            let resultHeight = (browserHeight - $('header').outerHeight()) / 2;
            mainDiv.outerHeight(resultHeight);
            iframe.outerHeight(resultHeight);
        }
        else {
            alert("nie ok");
        }
    }).trigger('resize');
});