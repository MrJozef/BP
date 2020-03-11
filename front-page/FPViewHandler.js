$(document).ready(function() {

    let controller = new FPController();

    let categoryButtons = $('header nav button');
    let main = $('main');

    categoryButtons.click(function() {
        let categoryId = $(this).attr('value');

        controller.loadArticleNames(categoryId).done(function (data) {
            main.html(data);
            //todo tunak nacitat article buttons
        });
    });
});