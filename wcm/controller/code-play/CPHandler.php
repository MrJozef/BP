<?php
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/code-play/ControllerExample.php";
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/code-play/ControllerCssProperty.php";
include_once $_SERVER['DOCUMENT_ROOT']."/config.php";


if(isset($_POST['aExample'])) {

    try {
        DBWrap::connect(HOST, DB_NAME, USER, PASSWORD);

        $exampleControll = new ControllerExample();

        $outputFromDb = $exampleControll->aLoadExample($_POST['aExample']);
        $output['code'] = $outputFromDb['exam_code'];
        $output['desc'] = $outputFromDb['exam_description'];
        $output['propArray'] = $exampleControll->aLoadArrayProperties($_POST['aExample']);

        echo json_encode($output);
    }
    catch (MyException $e) {
        echo $e->errorMessage();
    }
}

if(isset($_POST['aExampleProp'])) {

    try {
        DBWrap::connect(HOST, DB_NAME, USER, PASSWORD);

        $exampleControll = new ControllerExample();

        $output = $exampleControll->aLoadExampleProperties($_POST['aExampleProp']);

        echo $output;
    }
    catch (MyException $e) {
        echo $e->errorMessage();
    }
}

if(isset($_POST['aPropDesc'])) {

    try {
        DBWrap::connect(HOST, DB_NAME, USER, PASSWORD);

        $exampleControll = new ControllerCssProperty();

        echo $exampleControll->aLoadPropDesc($_POST['aPropDesc']);
    }
    catch (MyException $e) {
        echo $e->errorMessage();
    }
}