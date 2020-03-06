<?php


abstract class Manager
{
    protected function checkLengthWMsg($x, $maxLength, $minLength, $errorMsg) {

        if ($this->checkLength($x, $maxLength, $minLength)) {
            return true;
        }
        throw new MyException($errorMsg);
    }

    protected function checkLength($x, $maxLength, $minLength) {

        if(strlen($x) >= $minLength && strlen($x) <= $maxLength) {
            return true;
        }
        return false;
    }
}