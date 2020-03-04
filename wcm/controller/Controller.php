<?php


abstract class Controller
{
    //todo funkcia na osetrenie?
    protected $myManager;

    //funkcia na obranu pred XSS - todo nie som uplne presvedceny, ci je toto dobre, este skontrolovat
    protected function clearHTML($x) {

        if(isset($x)) {
            if (is_string($x)) {
                return htmlspecialchars($x, ENT_QUOTES);
            }
            elseif (is_array($x)) {
                foreach($x as $index => $data)
                {
                    $x[$index] = $this->clearHTML($data);
                }
            }
            else {
                return $x;
            }
        }

        return $x;
    }
}