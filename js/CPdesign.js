const BORDER_WIDTH = 4,
    ARTICLE_BORDER = 7,
    DESKTOP_MIN_WIDTH = 900;    //px

$(document).ready(function () {
    let nav = $('nav'),
        navDiv = nav.find('div'),
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

        //velkost + pozicia menu
        navDiv.outerHeight(browserHeight);

        //pozicovanie iframu, #css-form a js-section
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

function setHeightOfArticle(thisArticle) {

    $(window).on('resize', function() {
        const articleMenu = thisArticle.find('> div:first-of-type'),
            articleMain = thisArticle.find('> div:nth-of-type(2)');

        articleMain.outerHeight($(window).outerHeight() - articleMenu.outerHeight() - 2 * ARTICLE_BORDER);
    }).trigger('resize');
}