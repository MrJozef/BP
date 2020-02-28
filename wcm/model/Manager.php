<?php


abstract class Manager
{
    protected function checkLengthWMsg($x, $maxLength, $minLength, $errorMsg) {

        if ($this->checkLength($x, $maxLength, $minLength)) {
            return true;
        }
        $this->createMessage($errorMsg);
        return false;
    }

    protected function checkLength($x, $maxLength, $minLength) {

        if(strlen($x) >= $minLength && strlen($x) <= $maxLength) {
            return true;
        }
        return false;
    }

    protected function createMessage($msg) {
        echo $msg;//todo toto vytuningovat asi by bolo dobre
    }
}