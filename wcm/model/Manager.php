<?php
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/DBWrap.php";


abstract class Manager
{

    protected function tryQueryDb($task, $taskParam = [], $errMsg) {
        DBWrap::queryUniversal($task, $taskParam);
    }

    protected function checkLengthWException($x, $maxLength, $minLength, $errorMsg) {

        if (!$this->checkLength($x, $maxLength, $minLength)) {
            throw new MyException($errorMsg);
        }
    }

    protected function checkLength($x, $maxLength, $minLength) {

        if(strlen($x) >= $minLength && strlen($x) <= $maxLength) {
            return true;
        }
        return false;
    }
}