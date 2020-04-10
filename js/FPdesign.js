const DESKTOP_MIN_WIDTH = 900;    //px

$(document).ready(function () {
    const browser = $(window),
        header = $('header'),
        main = $('main'),
        nav = $('nav ul'),
        navButtons = nav.find('button');

    browser.on('resize', function() {
        let browserHeight = browser.outerHeight(),
            browserWidth = browser.outerWidth();

        if(browserWidth >= DESKTOP_MIN_WIDTH) {
            header.outerHeight(browserHeight);
            nav.outerWidth(header.innerWidth());
        }
        else {
            header.css('height', 'auto');
            nav.css('width', 'auto');
        }
    }).trigger('resize');

    navButtons.click(function () {
        $('html').animate({
            scrollTop: main.offset().top
        }, 800);
    });
});