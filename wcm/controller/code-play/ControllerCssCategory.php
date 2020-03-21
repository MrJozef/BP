<?php
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/code-play/ManagerCssCategory.php";


class ControllerCssCategory extends Controller
{
    public function __construct()
    {
        $this->myManager = new ManagerCssCategory();
    }

    public function showCssCateg() {
        $this->loadCssCategMenu();

        if(isset($_SESSION['css-categ'])) {
            $this->editCssCateg($_SESSION['css-categ']);
        }
        else {
            $this->showNewCssCategForm();
        }
    }

    public function saveNewCssCateg() {
        try {
            $this->myManager->save(trim($_POST['css-categ-name']));
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function saveEditedCssCateg() {
        try {
            $this->myManager->saveEdited($_SESSION['css-categ'], trim($_POST['css-categ-name']));
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function deleteCssCateg() {
        try {
            $this->myManager->delete($_SESSION['css-categ']);
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    private function showNewCssCategForm() {
        if (isset($_POST['css-categ-name'])) {
            $this->dataForView['cssCateg'] = ['css-categ-name' => $_POST['css-categ-name']];
        }
        else {
            $this->dataForView['cssCateg'] = ['css-categ-name' => ""];
        }
        extract($this->dataForView);
        require($_SERVER['DOCUMENT_ROOT']."/wcm/view/code-play/create-css-category.phtml");
    }

    private function editCssCateg($cssCategId) {

        try {
            $this->dataForView['cssCateg'] = $this->clearHTML($this->myManager->loadCategory($cssCategId));
            extract($this->dataForView);
            require($_SERVER['DOCUMENT_ROOT']."/wcm/view/code-play/edit-css-category.phtml");
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    private function loadCssCategMenu() {
        try {
            $menu = $this->myManager->loadAllNames();
            $this->dataForView['cssCateg'] = $this->clearHTML($menu);
            extract($this->dataForView);
            require($_SERVER['DOCUMENT_ROOT']."/wcm/view/code-play/css-category-ul.phtml");
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }
}