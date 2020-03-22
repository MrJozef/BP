<?php
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/code-play/ManagerExample.php";
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/Controller.php";


class ControllerExample extends Controller
{
    public function __construct()
    {
        $this->myManager = new ManagerExample();
    }

    public function manageExample() {
        try {
            $this->dataForView['examples'] = $this->clearHTML($this->myManager->loadAllNames());

            extract($this->dataForView);
            require($_SERVER['DOCUMENT_ROOT']."/wcm/view/code-play/manage-example.phtml");
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }
}