$(document).ready(function() {

    let controller = new FPController();

    let categoryButtons = $('header nav button');
    let main = $('main');

    categoryButtons.click(function() {
        let categoryId = $(this).attr('value');
        controller.loadArticleNames(categoryId);

        setTimeout(function () {

            let articleButtons = $(main).find('button');

            articleButtons.click(function () {
                let articleId = $(this).attr('value');
                controller.loadArticle(articleId);
            })
        },50);
    });
});