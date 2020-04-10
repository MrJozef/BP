const DESKTOP_MIN_WIDTH = 900;    //px

$(document).ready(function () {

    const browser = $(window),
        header = $('header'),
        footer = $('footer'),
        main = $('main');

    browser.on('resize', function() {
        const browserHeight = browser.outerHeight(),
            browserWidth = browser.outerWidth(),
            headerHeight = header.outerHeight(),
            footerHeight = footer.outerHeight();

        if(browserWidth >= DESKTOP_MIN_WIDTH) {
            main.outerHeight(browserHeight - headerHeight - footerHeight);
            main.offset({ top: headerHeight, left: 0 });
        }
        else {
            main.css('height', 'auto');
            main.offset({ top: headerHeight, left: 0 });
        }
    }).trigger('resize');
});