$(document).ready(function() {

    let controller = new FPController(),
    categoryButtons = $('header nav button'),
    main = $('main'),
    previousCategoryId,
    previousArticleId;

    categoryButtons.click(function() {
        const actualCategoryId = $(this).attr('value');

        if (previousCategoryId !== actualCategoryId) {
            controller.loadArticleNames(actualCategoryId).then(data => $(main).html(data));
            previousCategoryId = actualCategoryId;
        }


        setTimeout(function () {
            const articleButtons = $(main).find('button');

            articleButtons.click(function () {
                const actualArticleId = $(this).attr('value');

                if(previousArticleId !== actualArticleId) {
                    controller.loadArticle(actualArticleId).then(data => {
                        if($('main article').length) {
                            $('article').remove();
                            $(main).append(data);
                        }
                        else {
                            $(main).append(data);
                        }
                    });

                    previousArticleId = actualArticleId;
                }
            })
        },50);
    });
});