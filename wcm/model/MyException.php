<?php


class MyException extends Exception {

    public function errorMessage() {

        $errorMsg = '<div class="error"><p><span>chyba</span> - ' . $this->getMessage() . '</p></div>';
        return $errorMsg;
    }
}