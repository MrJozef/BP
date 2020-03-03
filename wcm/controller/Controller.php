<?php


abstract class Controller
{
    protected $data = [];
    protected $view = "";

    abstract public function process($param);

    public function printView() {//todo toto by som mal asi zmenit, lebo je to v podstate skopcene
        if($this->view) {
            extract($this->clearHTML($this->data));//ochrana pred XSS v pripade prvkov, kt. vyberame z db
            extract($this->data, EXTR_PREFIX_ALL, "html");//z db vyberieme prvky s tagmi a neosetrime ich (pouzivame iba pre clanok)

            require("wcm/user/view/" . $this->view);
        }
    }

    //funkcia na obranu pred XSS
    private function clearHTML($x) {
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
        return NULL;
    }

    public function rerout($partOfUrl) {
        header("Location: /wcm/view/$partOfUrl");
        header("Connection: close");
        exit;
    }

    public function reroutToIndex() {
        header("Location: /index.php");
        header("Connection: close");
        exit;
    }
}