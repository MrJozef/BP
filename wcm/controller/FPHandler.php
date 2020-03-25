<?php
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/ControllerArticle.php";
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/ControllerCategory.php";
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/ControllerUser.php";
include_once $_SERVER['DOCUMENT_ROOT']."/config.php";


if(isset($_POST['aCategoryId'])) {

    try {
        DBWrap::connect(HOST, DB_NAME, USER, PASSWORD);

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
        DBWrap::connect(HOST, DB_NAME, USER, PASSWORD);

        $articControll = new ControllerArticle();
        $userControll = new ControllerUser();

        $article = $articControll->aLoadArticle($_POST['aArticleId']);
        $article['nick'] = $userControll->aGetUserNameById($article['id_author']);

        extract($article);
        require($_SERVER['DOCUMENT_ROOT']."/wcm/view/article.phtml");
    }
    catch (MyException $e) {
        echo $e->errorMessage();
    }
}