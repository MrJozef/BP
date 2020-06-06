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
            footerHeight = footer.outerHeight(),
            errorHeight = $('.error').length ? $('.error').outerHeight() : 0,
            successHeight = $('.success').length ? $('.success').outerHeight() : 0;

        main.offset({ top: headerHeight + errorHeight + successHeight, left: 0 });
        if (browserWidth >= DESKTOP_MIN_WIDTH) {
            main.css('padding-bottom', footerHeight);
        }
        else {
            main.css('padding-bottom', 0);
        }

    }).trigger('resize');
});