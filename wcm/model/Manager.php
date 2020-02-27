<?php


abstract class Manager
{
    private function checkLength($x, $maxLength, $minLength) {
        if(strlen($x) >= $minLength && strlen($x) <= $maxLength) {
            return true;
        }
        return false;
    }
}