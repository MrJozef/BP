<?php
const ERROR_UNIVERSAL = "Dopyt na databázu zlyhal! Vaša požiadavka nemohla byť dokončená.";


abstract class Manager
{

    protected function tryQueryDb($task, $taskParam = [], $errMsg) {
        try {
            DBWrap::queryUniversal($task, $taskParam);
        }
        catch(PDOException $exception) {
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