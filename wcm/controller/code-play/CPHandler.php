<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/autoloader.php";

if(isset($_POST['aExample'])) {
    $exampleControll = new ControllerExample();

    $outputFromDb = $exampleControll->aLoadExample($_POST['aExample']);
    $output['code'] = $outputFromDb['exam_code'];
    $output['desc'] = $outputFromDb['exam_description'];
    $output['propArray'] = $exampleControll->aLoadArrayProperties($_POST['aExample']);

    echo json_encode($output);
}

if(isset($_POST['aExampleProp'])) {

    $exampleControll = new ControllerExample();

    $output = $exampleControll->aLoadExampleProperties($_POST['aExampleProp']);
    echo $output;
}

if(isset($_POST['aExampleJs'])) {

    $exampleControll = new ControllerExample();

    $output = $exampleControll->aLoadExampleJs($_POST['aExampleJs']);
    echo $output;
}

if(isset($_POST['aPropDesc'])) {

    $exampleControll = new ControllerCssProperty();
    echo $exampleControll->aLoadPropDesc($_POST['aPropDesc']);
}

if(isset($_POST['aJsDesc'])) {
    $exampleControll = new ControllerExample();
    echo $exampleControll->aLoadJsDesc($_POST['aJsDesc']);
}