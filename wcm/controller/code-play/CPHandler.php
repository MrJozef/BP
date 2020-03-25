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
        //$output['propertyList'] = $exampleControll->aLoadExampleProperties($_POST['aExample']);

        echo json_encode($output);
    }
    catch (MyException $e) {
        echo $e->errorMessage();
    }
}