<?php

include_once $_SERVER['DOCUMENT_ROOT']."/config.php";

function autoloader($class)
{
    if (preg_match('/^Controller/', $class))
        if(file_exists($_SERVER['DOCUMENT_ROOT'] . "/wcm/controller/" . $class . ".php")) {
            include_once $_SERVER['DOCUMENT_ROOT'] . "/wcm/controller/". $class. ".php";
        }
        else {
            include_once $_SERVER['DOCUMENT_ROOT'] . "/wcm/controller/code-play/" . $class . ".php";
        }
    else {
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/wcm/model/" . $class . ".php")) {
            include_once $_SERVER['DOCUMENT_ROOT'] . "/wcm/model/" . $class . ".php";
        } else {
            include_once $_SERVER['DOCUMENT_ROOT'] . "/wcm/model/code-play/" . $class . ".php";
        }
    }
}

spl_autoload_register("autoloader");

    //pripojenie sa k DB
    try {
        DBWrap::connect(HOST, DB_NAME, USER, PASSWORD);
    }
    catch (MyException $e) {
        echo $e->errorMessage();
    }