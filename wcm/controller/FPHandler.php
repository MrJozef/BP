<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/autoloader.php";

if(isset($_POST['aCategoryId'])) {

    try {
        $categControll = new ControllerCategory();
        $articControll = new ControllerArticle();

        $outputString = $categControll->aLoadDescCat($_POST['aCategoryId']);
        $outputString .= $articControll->aLoadAllArticNamesOfCat($_POST['aCategoryId']);

        echo $outputString;
    }
    catch (MyException $e) {
        echo $e->errorMessage();
    }
}

if(isset($_POST['aArticleId'])) {
    try {
        $articControll = new ControllerArticle();
        $userControll = new ControllerUser();

        $article = $articControll->aLoadArticle($_POST['aArticleId']);
        if($article !== null) {
            $article['nick'] = $userControll->aGetUserNameById($article['id_author']);

            extract($article);
            require($_SERVER['DOCUMENT_ROOT']."/wcm/view/article.phtml");
        }
    }
    catch (MyException $e) {
        echo $e->errorMessage();
    }
}