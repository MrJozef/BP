const BORDER_WIDTH = 4,
    DESKTOP_MIN_WIDTH = 900;    //px

$(document).ready(function () {
    let nav = $('nav'),
        showNavButton = $('#showNav'),
        closeNavButton = $('#closeNav'),
        mainDiv = $('main > div:first-child'),
        iframe = $('#result'),
        browser = $(window);

    showNavButton.click(function () {
        nav.removeClass('invisible');
    });

    closeNavButton.click(function () {
        nav.addClass('invisible');
    });


    browser.on('resize', function() {
        let browserWidth = browser.width(),
            browserHeight = browser.height(),
            headerHeight = $('header').outerHeight();

        if (browserWidth < DESKTOP_MIN_WIDTH) {
            let resultHeight = (browserHeight - headerHeight) / 2;

            mainDiv.outerHeight(resultHeight);
            mainDiv.outerWidth(browserWidth - BORDER_WIDTH * 2);
            iframe.outerHeight(resultHeight);
            iframe.outerWidth(browserWidth - BORDER_WIDTH * 2);

            iframe.offset({ top: (headerHeight + resultHeight), left: BORDER_WIDTH });
        }
        else {
            let resultHeight = browserHeight - headerHeight,
                resultWidth = browserWidth / 2;

            mainDiv.outerHeight(resultHeight);
            mainDiv.outerWidth(resultWidth - BORDER_WIDTH);
            iframe.outerHeight(resultHeight);
            iframe.outerWidth(resultWidth - BORDER_WIDTH);

            iframe.offset({ top: headerHeight, left: resultWidth });
        }
    }).trigger('resize');
});