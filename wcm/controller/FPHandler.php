<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/autoloader.php";

if(isset($_POST['aCategoryId'])) {

    $categControll = new ControllerCategory();
    $articControll = new ControllerArticle();

    $outputString = $categControll->aLoadDescCat($_POST['aCategoryId']);
    $outputString .= $articControll->aLoadAllArticNamesOfCat($_POST['aCategoryId']);

    echo $outputString;
}

if(isset($_POST['aArticleId'])) {

    $articControll = new ControllerArticle();
    $userControll = new ControllerUser();

    $article = $articControll->aLoadArticle($_POST['aArticleId']);
    if($article !== null) {
        $article['nick'] = $userControll->getUserNameById($article['id_author']);

        extract($article);
        require($_SERVER['DOCUMENT_ROOT']."/wcm/view/article.phtml");
    }
}