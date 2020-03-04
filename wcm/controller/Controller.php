<?php


abstract class Controller
{
    protected $myManager;

    //pouziva sa v .phtml sablonach a nielen na vypisy v $_POST, ale aj v $_SESSION
    public function clearPost($nameOfPost) {
        if(isset($_POST[$nameOfPost])) {
            return $this->clearHTML($_POST[$nameOfPost]);
        }
        elseif(isset($_SESSION[$nameOfPost])) {
            return $this->clearHTML($_SESSION[$nameOfPost]);
        }

        return null;
    }

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