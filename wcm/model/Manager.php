<?php


abstract class Manager
{
    protected function checkLength($x, $maxLength, $minLength, $errorMsg) {
        if(strlen($x) >= $minLength && strlen($x) <= $maxLength) {
            return true;
        }
        return false;
    }
}