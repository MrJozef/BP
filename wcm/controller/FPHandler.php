<?php
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/ControllerArticle.php";
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/ControllerUser.php";


if(isset($_POST['aCategoryId'])) {

    try {
        DBWrap::connect('127.0.0.1', 'bp_db', 'root', '');

        $articControll = new ControllerArticle();
        echo $articControll->aLoadAllArticNamesOfCat($_POST['aCategoryId']);
    }
    catch (MyException $e) {
        echo $e->errorMessage();
    }
}

if(isset($_POST['aArticleId'])) {
    try {
        DBWrap::connect('127.0.0.1', 'bp_db', 'root', '');

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