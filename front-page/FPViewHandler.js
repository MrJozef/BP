$(document).ready(function() {

    let controller = new FPController(),
    categoryButtons = $('header nav button'),
    main = $('main'),
    previousCategoryId,
    previousArticleId;

    categoryButtons.click(function() {
        let actualCategoryId = $(this).attr('value');

        if (previousCategoryId !== actualCategoryId) {
            controller.loadArticleNames(actualCategoryId);
            previousCategoryId = actualCategoryId;
        }


        setTimeout(function () {
            let articleButtons = $(main).find('button');

            articleButtons.click(function () {
                let actualArticleId = $(this).attr('value');

                if(previousArticleId !== actualArticleId) {
                    controller.loadArticle(actualArticleId);
                    previousArticleId = actualArticleId;
                }
            })
        },50);
    });
});