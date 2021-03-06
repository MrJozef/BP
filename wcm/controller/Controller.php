<?php


abstract class Controller
{
    protected $dataForView;
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

    //funkcia na obranu pred XSS
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

    protected function throwSuccMsg($succMsg) {
        $this->dataForView['message'] = $succMsg;
        extract($this->dataForView);
        require($_SERVER['DOCUMENT_ROOT']."/wcm/view/success-msg.phtml");
    }

    protected function throwErrorMsg($errorMsg) {
        $this->dataForView['message'] = $errorMsg;
        extract($this->dataForView);
        require($_SERVER['DOCUMENT_ROOT']."/wcm/view/error-msg.phtml");
    }
}