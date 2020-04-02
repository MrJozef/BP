<?php


abstract class Manager
{

    protected function tryQueryDb($task, $taskParam = [], $errMsg) {
        try {
            return DBWrap::queryUniversal($task, $taskParam);
        }
        catch (\Throwable $exception) {
            throw new MyException($errMsg);
        }
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