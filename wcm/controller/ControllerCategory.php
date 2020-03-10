<?php
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/ManagerCategory.php";


class ControllerCategory extends Controller
{
    public function __construct()
    {
        $this->myManager = new ManagerCategory();
    }

    public function saveNewCat() {
        try {
            $this->myManager->save($_POST['category-name'], $_POST['category-desc'], $_POST['category-visibility']);
        }
        catch(MyException $e) {
            $this->throwErrorMsg($e->errorMessage());
        }
    }

    public function loadCatMenu() {
        $this->loadNamesOfCat();
        extract($this->dataForView);
        require($_SERVER['DOCUMENT_ROOT']."/wcm/view/category-ul.phtml");
    }

    public function loadCatSelect() {
        $this->loadNamesOfCat();
        extract($this->dataForView);
        require($_SERVER['DOCUMENT_ROOT']."/wcm/view/category-select.phtml");
    }

    private function loadNamesOfCat() {
        $result = $this->myManager->getNamesAndId();
        $this->dataForView['categNames'] = $this->clearHTML($result);
    }
}