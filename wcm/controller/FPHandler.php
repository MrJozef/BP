<?php
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/ControllerArticle.php";
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/ControllerUser.php";



if(isset($_POST['aCategoryId'])) {
    $articControll = new ControllerArticle();

    try {
        DBWrap::connect('127.0.0.1', 'bp_db', 'root', '');
    }
    catch (MyException $e) {
        echo $e->errorMessage();
    }

    echo $articControll->aLoadAllArticNamesOfCat($_POST['aCategoryId']);
}