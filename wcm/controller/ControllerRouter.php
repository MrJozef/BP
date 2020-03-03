<?php
include_once "Controller.php";
include_once "ControllerUser.php";

class ControllerRouter extends Controller
{
    public function process($param)
    {
        $goodUrl = $this->parseURL($param[0]);
        if (empty($goodUrl[0])) {
            $this->reroutToIndex();
        }
        else {
            $controllerName =  $this->getControllerFullName(array_shift($goodUrl));

            if(file_exists("../../controller/" . $controllerName . ".php")) {
                $controller = new $controllerName;
                $controller->process($goodUrl);

            }
            else {
                $this->rerout("error404.phtml");
            }
        }
    }

    private function parseURL($url) {
        $arrayOfUrl = parse_url($url);  //tato funkcia nam URL adresu rozlozi do pola, v kt. su samostatne - port, cesta, host...
        $path = trim($arrayOfUrl["path"]);
        $path = ltrim($path, "/");

        //musime koncovky .php alebo .phtml zrusit
        $forReplace = [".php", ".phtml", "wcm/", "view/"];
        $path = str_replace($forReplace, "", $path);

        $arrayPath = explode("/", $path);
        return $arrayPath;//napr. [0] => user [1] => login
    }

    private function getControllerFullName($viewName) {
        $controllName = "Controller" . ucfirst($viewName);
        return $controllName;
    }
}