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
            $this->dataForView['examples'] = $this->clearHTML($this->myManager->loadExampleNames());

            extract($this->dataForView);
            require($_SERVER['DOCUMENT_ROOT'] . "/wcm/view/code-play/example-menu.phtml");
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function manageExample($propArrayForSelect) {
        try {
            if(isset($_SESSION['example'])) {
                $dataFromManager = $this->myManager->loadExample($_SESSION['example']);
                $this->dataForView['example'] = $dataFromManager['example'];
                $this->dataForView['cssUse'] = $dataFromManager['cssUse'];
            }
            else {
/*                if (isset($_POST['example-name'])) {
                    $this->dataForView['example'] = ['exam_name' => $_POST['example-name'], 'exam_description' => $_POST['example-description'], 'exam_code' => $_POST['example-code']];
                }
                else {*/
                    $this->dataForView['example'] = ['exam_name' => "", 'exam_description' => "", 'exam_code' => ""];
               // }

                $this->dataForView['cssUse'] = [];
            }

            $this->dataForView['cssProp'] = $propArrayForSelect;

            extract($this->dataForView);
            require($_SERVER['DOCUMENT_ROOT'] . "/wcm/view/code-play/example-form.phtml");
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function saveNewUse() {
        try {
            $this->myManager->saveUse($_POST['id_prop'], $_SESSION['example'], $_POST['for-element']);
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function saveEditedUse() {
        try {
            $this->myManager->saveEditedUse($_POST['use-edit'], $_POST['id_prop'], $_POST['for-element']);
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function deleteUse() {
        try {
            $this->myManager->deleteUse($_POST['use-delete']);
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function saveNewExample() {
        try {
            return $this->myManager->saveNewExample($_POST['example-name'], $_POST['example-description'], $_POST['example-code']);
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
        return false;
    }

    public function saveEditedExample() {
        try {
            $this->myManager->saveEditedExample($_POST['example-name'], $_POST['example-description'], $_POST['example-code'], $_SESSION['example']);
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function deleteExample() {
        try {
            $this->myManager->deleteExample($_SESSION['example']);
        }
        catch (MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }
}