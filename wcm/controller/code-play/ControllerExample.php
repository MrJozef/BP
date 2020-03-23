<?php
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/code-play/ManagerExample.php";
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/Controller.php";


class ControllerExample extends Controller
{
    public function __construct()
    {
        $this->myManager = new ManagerExample();
    }

    public function showExamples() {
        try {
            $this->dataForView['examples'] = $this->clearHTML($this->myManager->loadAllNames());

            extract($this->dataForView);
            require($_SERVER['DOCUMENT_ROOT'] . "/wcm/view/code-play/example-menu.phtml");
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function manageExample($propArrForSelect) {
        try {
            if(isset($_SESSION['example'])) {
                $dataFromManager = $this->myManager->loadExample($_SESSION['example']);
                $this->dataForView['example'] = $dataFromManager['example'];
                $this->dataForView['cssUse'] = $dataFromManager['cssUse'];
            }
            else {
                if (isset($_POST['example-name'])) {
                    $this->dataForView['example'] = ['exam_name' => $_POST['example-name'], 'exam_description' => $_POST['example-description'], 'exam_code' => $_POST['example-code']];
                }
                else {
                    $this->dataForView['example'] = ['exam_name' => "", 'exam_description' => "", 'exam_code' => ""];
                }

                $this->dataForView['cssUse'] = [];
            }

            $this->dataForView['cssProp'] = $propArrForSelect;

            extract($this->dataForView);
            require($_SERVER['DOCUMENT_ROOT'] . "/wcm/view/code-play/example-form.phtml");
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function saveNewExample() {

    }


}